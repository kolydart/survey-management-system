<?php

namespace App\Services;

use App\Mail\ErrorNotification;
use App\Questionnaire;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Spatie\Activitylog\Models\Activity;

/**
 * Service to handle duplicate questionnaire detection
 * Centralizes all duplicate detection logic from controllers
 */
class DuplicateDetectionService
{
    /**
     * Check for cookie-based duplicates and send email notification
     * Extracted from CollectController to centralize duplicate detection logic
     *
     * @param Request $request
     * @param Questionnaire $questionnaire
     * @return array|null Returns duplicate info if found, null otherwise
     */
    public function checkCookieDuplicate(Request $request, Questionnaire $questionnaire): ?array
    {
        try {
            $surveyId = $request->survey_id;
            $cookieValue = Cookie::get('survey_' . $surveyId);

            if ($cookieValue) {
                $oldQuestionnaireId = Cookie::get('questionnaire');

                $duplicate = [
                    'type' => 'cookie',
                    'old_questionnaire_id' => $oldQuestionnaireId,
                    'new_questionnaire_id' => $questionnaire->id,
                    'survey_id' => $surveyId,
                ];

                // Send email notification
                $this->sendDuplicateNotification($duplicate);

                return $duplicate;
            }

            // Set cookies for future detection (48 hours = 2880 minutes)
            Cookie::queue(Cookie::make('survey_' . $surveyId, true, 2880));
            Cookie::queue(Cookie::make('questionnaire', $questionnaire->id, 2880));

            return null;

        } catch (\Exception $e) {
            $message = 'Could not handle cookies. Error vWhDRFPtoQMnGMes. Code: ' . $e->getCode();
            Log::channel('cookies')->error($message);

            try {
                Mail::to(User::getAdminEmail())
                    ->send(new ErrorNotification($message, 'Cookie Handling Error'));
            } catch (\Exception $mailException) {
                Log::error('Failed to send cookie error notification: ' . $mailException->getMessage());
            }

            return null;
        }
    }

    /**
     * Find duplicates based on activity log (IP + User Agent fingerprinting)
     * Fixes the broken get_duplicates() method by querying activity_log instead of loguseragents table
     *
     * @param int $survey_id
     * @return array Array of duplicate groups
     */
    public function findByActivityLog(int $survey_id): array
    {
        $questionnaires_arr = Questionnaire::where('survey_id', $survey_id)
            ->pluck('id')
            ->toArray();

        if (empty($questionnaires_arr)) {
            return [];
        }

        $activityLogs = Activity::query()
            ->where('subject_type', 'App\Questionnaire')
            ->whereIn('subject_id', $questionnaires_arr)
            ->where('description', 'questionnaire_submit')
            ->get()
            ->groupBy(function ($activity) {
                $props = $activity->properties;
                return ($props['ip'] ?? '') . '|' . ($props['user_agent'] ?? '');
            });

        $duplicates = [];
        foreach ($activityLogs as $logs) {
            if ($logs->count() > 1) {
                $first = $logs->first();
                $props = $first->properties;

                $duplicates[] = [
                    'type' => 'ipsw',
                    'value' => [
                        'ipv6' => $props['ip'] ?? '',
                        'user_agent' => $props['user_agent'] ?? '',
                    ],
                    'count' => $logs->count(),
                    'loguseragents' => $logs->map(function ($activity) {
                        return $this->activityToLoguseragentFormat($activity);
                    }),
                ];
            }
        }

        return $duplicates;
    }

    /**
     * Find duplicates using Levenshtein distance for content similarity
     * Most robust method - detects near-duplicates across different browsers/IPs
     *
     * @param int $survey_id
     * @param float $threshold Similarity threshold (0-100), default 85%
     * @return array Array of duplicate pairs with similarity scores
     */
    public function findByContentSimilarity(int $survey_id, float $threshold = 85): array
    {
        // Cache results for 1 hour
        $cacheKey = "duplicates_similarity_{$survey_id}_{$threshold}";

        return Cache::remember($cacheKey, 3600, function () use ($survey_id, $threshold) {
            $questionnaires = Questionnaire::where('survey_id', $survey_id)
                ->with('responses:id,questionnaire_id,question_id,answer_id,content')
                ->get();

            $duplicates = [];
            $checked = [];

            // Compare each questionnaire pair
            foreach ($questionnaires as $i => $q1) {
                foreach ($questionnaires as $j => $q2) {
                    if ($i >= $j) {
                        continue; // Skip self and already checked pairs
                    }

                    $pairKey = min($q1->id, $q2->id) . '_' . max($q1->id, $q2->id);
                    if (isset($checked[$pairKey])) {
                        continue;
                    }

                    $similarity = $this->calculateSimilarity($q1, $q2);

                    if ($similarity >= $threshold) {
                        $duplicates[] = [
                            'type' => 'similarity',
                            'questionnaire_1_id' => $q1->id,
                            'questionnaire_2_id' => $q2->id,
                            'similarity_score' => round($similarity, 2),
                            'loguseragents' => collect([
                                $this->questionnaireToLoguseragentFormat($q1, $similarity),
                                $this->questionnaireToLoguseragentFormat($q2, $similarity),
                            ]),
                            'count' => 2,
                        ];
                    }

                    $checked[$pairKey] = true;
                }
            }

            return $duplicates;
        });
    }

    /**
     * Calculate similarity percentage between two questionnaires
     *
     * @param Questionnaire $q1
     * @param Questionnaire $q2
     * @return float Similarity percentage (0-100)
     */
    private function calculateSimilarity(Questionnaire $q1, Questionnaire $q2): float
    {
        $texts1 = $q1->responses->whereNotNull('content')->where('content', '!=', '')->pluck('content');
        $texts2 = $q2->responses->whereNotNull('content')->where('content', '!=', '')->pluck('content');

        if ($texts1->isEmpty() || $texts2->isEmpty()) {
            // If no text responses, compare answer_ids
            $answers1 = $q1->responses->pluck('answer_id')->filter()->toArray();
            $answers2 = $q2->responses->pluck('answer_id')->filter()->toArray();

            if (empty($answers1) || empty($answers2)) {
                return 0;
            }

            // Calculate Jaccard similarity for answer selections
            $intersection = count(array_intersect($answers1, $answers2));
            $union = count(array_unique(array_merge($answers1, $answers2)));

            return $union > 0 ? ($intersection / $union) * 100 : 0;
        }

        // Calculate text similarity using similar_text
        $similarities = [];
        foreach ($texts1 as $index => $text1) {
            if (isset($texts2[$index])) {
                similar_text($text1, $texts2[$index], $percent);
                $similarities[] = $percent;
            }
        }

        return count($similarities) > 0 ? array_sum($similarities) / count($similarities) : 0;
    }

    /**
     * Convert Activity model to loguseragent format for view compatibility
     *
     * @param Activity $activity
     * @return object
     */
    private function activityToLoguseragentFormat(Activity $activity): object
    {
        $props = $activity->properties;

        return (object) [
            'id' => $activity->id,
            'item_id' => $activity->subject_id,
            'created_at' => $activity->created_at,
            'ipv6' => $props['ip'] ?? '',
            'os' => '',
            'os_version' => '',
            'browser' => '',
            'browser_version' => '',
            'device' => '',
            'language' => '',
            'uri' => '',
            'form_submitted' => 1,
            'user' => null,
        ];
    }

    /**
     * Convert Questionnaire model to loguseragent format for view compatibility
     *
     * @param Questionnaire $questionnaire
     * @param float $similarity
     * @return object
     */
    private function questionnaireToLoguseragentFormat(Questionnaire $questionnaire, float $similarity = 0): object
    {
        return (object) [
            'id' => $questionnaire->id,
            'item_id' => $questionnaire->id,
            'created_at' => $questionnaire->created_at,
            'ipv6' => 'Similarity: ' . round($similarity, 2) . '%',
            'os' => '',
            'os_version' => '',
            'browser' => '',
            'browser_version' => '',
            'device' => '',
            'language' => '',
            'uri' => '',
            'form_submitted' => 1,
            'user' => null,
        ];
    }

    /**
     * Send duplicate notification email to admin
     *
     * @param array $duplicate
     * @return void
     */
    private function sendDuplicateNotification(array $duplicate): void
    {
        try {
            $adminUrl = url('/admin/questionnaires/');
            $message = sprintf(
                "Survey %d questionnaire filled twice in the same browser.\n" .
                "Old questionnaire: %s%s\n" .
                "New questionnaire: %s%s\n",
                $duplicate['survey_id'],
                $adminUrl,
                $duplicate['old_questionnaire_id'] ?? 'unknown',
                $adminUrl,
                $duplicate['new_questionnaire_id']
            );

            Mail::to(User::getAdminEmail())
                ->send(new ErrorNotification($message, 'Duplicate Survey Submission'));

        } catch (\Exception $e) {
            Log::error('Failed to send duplicate notification: ' . $e->getMessage());
        }
    }
}
