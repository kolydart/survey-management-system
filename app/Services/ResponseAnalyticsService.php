<?php

namespace App\Services;

use App\Response;
use App\Item;
use App\Survey;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Builder;

/**
 * Service to handle response analytics and querying
 * Centralizes response filtering logic used throughout the application
 */
class ResponseAnalyticsService
{
    /**
     * Get responses for a survey item with proper filtering
     *
     * @param Item $item
     * @param int|null $answerId Filter by specific answer
     * @return Builder
     */
    public function getItemResponses(Item $item, ?int $answerId = null): Builder
    {
        $query = Response::query()
            ->whereIn('questionnaire_id', $item->survey->questionnaires->pluck('id'))
            ->where('question_id', $item->question_id);

        if ($answerId) {
            $query->where('answer_id', $answerId);
        }

        return $query;
    }

    /**
     * Get content responses (non-empty text responses) for an item
     *
     * @param Item $item
     * @return Collection
     */
    public function getContentResponses(Item $item): Collection
    {
        return $this->getItemResponses($item)
            ->whereNotNull('content')
            ->where('content', '!=', '')
            ->get();
    }

    /**
     * Get content values as array for statistical analysis
     *
     * @param Item $item
     * @return array
     */
    public function getContentValues(Item $item): array
    {
        return $this->getItemResponses($item)
            ->whereNotNull('content')
            ->where('content', '!=', '')
            ->pluck('content')
            ->toArray();
    }

    /**
     * Get response count for specific answer
     *
     * @param Item $item
     * @param int $answerId
     * @return int
     */
    public function getAnswerResponseCount(Item $item, int $answerId): int
    {
        return $this->getItemResponses($item, $answerId)->count();
    }

    /**
     * Get total response count for an item
     *
     * @param Item $item
     * @return int
     */
    public function getTotalResponseCount(Item $item): int
    {
        return $this->getItemResponses($item)->count();
    }

    /**
     * Get response distribution for all answers of an item
     *
     * @param Item $item
     * @return array
     */
    public function getResponseDistribution(Item $item): array
    {
        $answers = $item->get_answers();
        $totalResponses = $this->getTotalResponseCount($item);
        $distribution = [];

        foreach ($answers as $answer) {
            $count = $this->getAnswerResponseCount($item, $answer->id);
            
            $distribution[] = [
                'answer_id' => $answer->id,
                'answer_title' => $answer->title,
                'count' => $count,
                'percentage' => $totalResponses > 0 ? round(($count / $totalResponses) * 100, 2) : 0,
            ];
        }

        return $distribution;
    }

    /**
     * Get survey completion analytics
     *
     * @param Survey $survey
     * @return array
     */
    public function getSurveyAnalytics(Survey $survey): array
    {
        $questionnaires = $survey->questionnaires;
        $totalQuestionnaires = $questionnaires->count();
        
        if ($totalQuestionnaires === 0) {
            return [
                'total_questionnaires' => 0,
                'total_responses' => 0,
                'completion_rates' => [],
                'average_completion' => 0,
            ];
        }

        $completionRates = [];
        $totalCompletionSum = 0;

        foreach ($questionnaires as $questionnaire) {
            $filledPercent = (float) $questionnaire->filled_percent;
            $completionRates[] = [
                'questionnaire_id' => $questionnaire->id,
                'completion_rate' => $filledPercent,
                'is_complete' => $filledPercent >= 100,
            ];
            
            $totalCompletionSum += $filledPercent;
        }

        $totalResponses = Response::whereIn('questionnaire_id', $questionnaires->pluck('id'))->count();

        return [
            'total_questionnaires' => $totalQuestionnaires,
            'total_responses' => $totalResponses,
            'completion_rates' => $completionRates,
            'average_completion' => round($totalCompletionSum / $totalQuestionnaires, 2),
            'fully_completed_count' => collect($completionRates)->where('is_complete', true)->count(),
        ];
    }

    /**
     * Get response trends over time
     *
     * @param Survey $survey
     * @param string $period 'day', 'week', 'month'
     * @return array
     */
    public function getResponseTrends(Survey $survey, string $period = 'day'): array
    {
        $dateFormat = match($period) {
            'day' => '%Y-%m-%d',
            'week' => '%Y-%u',
            'month' => '%Y-%m',
            default => '%Y-%m-%d'
        };

        $trends = Response::query()
            ->whereHas('questionnaire', function($query) use ($survey) {
                $query->where('survey_id', $survey->id);
            })
            ->selectRaw("DATE_FORMAT(created_at, '{$dateFormat}') as period")
            ->selectRaw('COUNT(*) as response_count')
            ->selectRaw('COUNT(DISTINCT questionnaire_id) as questionnaire_count')
            ->groupBy('period')
            ->orderBy('period')
            ->get()
            ->toArray();

        return $trends;
    }

    /**
     * Get most popular answers across survey
     *
     * @param Survey $survey
     * @param int $limit
     * @return array
     */
    public function getMostPopularAnswers(Survey $survey, int $limit = 10): array
    {
        return Response::query()
            ->join('answers', 'responses.answer_id', '=', 'answers.id')
            ->join('questions', 'responses.question_id', '=', 'questions.id')
            ->whereHas('questionnaire', function($query) use ($survey) {
                $query->where('survey_id', $survey->id);
            })
            ->selectRaw('answers.id, answers.title as answer_title, questions.title as question_title')
            ->selectRaw('COUNT(*) as response_count')
            ->groupBy('answers.id', 'answers.title', 'questions.title')
            ->orderByDesc('response_count')
            ->limit($limit)
            ->get()
            ->toArray();
    }

    /**
     * Get response quality metrics
     *
     * @param Survey $survey
     * @return array
     */
    public function getResponseQualityMetrics(Survey $survey): array
    {
        $questionnaires = $survey->questionnaires;
        $totalResponses = Response::whereIn('questionnaire_id', $questionnaires->pluck('id'))->count();
        
        $contentResponses = Response::whereIn('questionnaire_id', $questionnaires->pluck('id'))
            ->whereNotNull('content')
            ->where('content', '!=', '')
            ->count();

        $emptyResponses = $totalResponses - $contentResponses;

        // Calculate response length statistics for text responses
        $contentLengths = Response::whereIn('questionnaire_id', $questionnaires->pluck('id'))
            ->whereNotNull('content')
            ->where('content', '!=', '')
            ->selectRaw('LENGTH(content) as content_length')
            ->pluck('content_length')
            ->toArray();

        $avgContentLength = !empty($contentLengths) ? round(array_sum($contentLengths) / count($contentLengths), 2) : 0;

        return [
            'total_responses' => $totalResponses,
            'content_responses' => $contentResponses,
            'empty_responses' => $emptyResponses,
            'content_response_rate' => $totalResponses > 0 ? round(($contentResponses / $totalResponses) * 100, 2) : 0,
            'average_content_length' => $avgContentLength,
            'min_content_length' => !empty($contentLengths) ? min($contentLengths) : 0,
            'max_content_length' => !empty($contentLengths) ? max($contentLengths) : 0,
        ];
    }

    /**
     * Get duplicate response detection data
     *
     * @param Survey $survey
     * @return array
     */
    public function getDuplicateResponseAnalysis(Survey $survey): array
    {
        $questionnaires = $survey->questionnaires->pluck('id');
        
        // Find potential duplicates based on response patterns
        $duplicatePatterns = Response::query()
            ->whereIn('questionnaire_id', $questionnaires)
            ->selectRaw('questionnaire_id, GROUP_CONCAT(CONCAT(question_id, ":", answer_id) ORDER BY question_id) as response_pattern')
            ->groupBy('questionnaire_id')
            ->having(\DB::raw('COUNT(*)'), '>', 1)
            ->get()
            ->groupBy('response_pattern')
            ->filter(function($group) {
                return $group->count() > 1; // More than one questionnaire with same pattern
            });

        $duplicateAnalysis = [];
        foreach ($duplicatePatterns as $pattern => $questionnaires) {
            $duplicateAnalysis[] = [
                'pattern' => $pattern,
                'questionnaire_count' => $questionnaires->count(),
                'questionnaire_ids' => $questionnaires->pluck('questionnaire_id')->toArray(),
            ];
        }

        return [
            'duplicate_patterns' => $duplicateAnalysis,
            'total_duplicate_groups' => count($duplicateAnalysis),
            'total_duplicate_questionnaires' => collect($duplicateAnalysis)->sum('questionnaire_count'),
        ];
    }

    /**
     * Get response validation errors
     *
     * @param Survey $survey
     * @return array
     */
    public function getResponseValidationErrors(Survey $survey): array
    {
        $errors = [];
        $questionnaires = $survey->questionnaires;

        foreach ($questionnaires as $questionnaire) {
            // Check for responses to questions not in survey
            $outliers = $questionnaire->outliers();
            
            if ($outliers && $outliers->count() > 0) {
                $errors[] = [
                    'type' => 'orphaned_responses',
                    'questionnaire_id' => $questionnaire->id,
                    'description' => 'Responses to questions not in survey template',
                    'question_ids' => $outliers->pluck('id')->toArray(),
                ];
            }

            // Check for missing required responses
            $requiredQuestions = $survey->items()->where('label', '!=', 1)->get();
            $answeredQuestions = $questionnaire->responses->pluck('question_id')->unique();
            $missingQuestions = $requiredQuestions->pluck('question_id')->diff($answeredQuestions);

            if ($missingQuestions->count() > 0) {
                $errors[] = [
                    'type' => 'missing_responses',
                    'questionnaire_id' => $questionnaire->id,
                    'description' => 'Missing responses to required questions',
                    'question_ids' => $missingQuestions->toArray(),
                ];
            }
        }

        return $errors;
    }

    /**
     * Export response data in various formats
     *
     * @param Survey $survey
     * @param string $format
     * @param array $options
     * @return string|array
     */
    public function exportResponseData(Survey $survey, string $format = 'csv', array $options = [])
    {
        $includeContent = $options['include_content'] ?? true;
        $includeTimestamps = $options['include_timestamps'] ?? true;

        $responses = Response::query()
            ->join('questionnaires', 'responses.questionnaire_id', '=', 'questionnaires.id')
            ->join('questions', 'responses.question_id', '=', 'questions.id')
            ->join('answers', 'responses.answer_id', '=', 'answers.id')
            ->where('questionnaires.survey_id', $survey->id)
            ->select([
                'questionnaires.id as questionnaire_id',
                'questionnaires.name as questionnaire_name',
                'questions.title as question_title',
                'answers.title as answer_title',
                'responses.content',
                'responses.created_at'
            ])
            ->orderBy('questionnaires.id')
            ->orderBy('questions.id')
            ->get();

        switch ($format) {
            case 'json':
                return $responses->toJson(JSON_PRETTY_PRINT);
            
            case 'xml':
                return $this->exportToXml($responses);
            
            case 'csv':
            default:
                return $this->exportToCsv($responses, $includeContent, $includeTimestamps);
        }
    }

    /**
     * Export responses to CSV format
     */
    protected function exportToCsv(Collection $responses, bool $includeContent, bool $includeTimestamps): string
    {
        $headers = ['Questionnaire ID', 'Questionnaire Name', 'Question', 'Answer'];
        
        if ($includeContent) {
            $headers[] = 'Content';
        }
        
        if ($includeTimestamps) {
            $headers[] = 'Created At';
        }

        $csv = implode(',', $headers) . "\n";

        foreach ($responses as $response) {
            $row = [
                $response->questionnaire_id,
                '"' . str_replace('"', '""', $response->questionnaire_name ?? '') . '"',
                '"' . str_replace('"', '""', $response->question_title) . '"',
                '"' . str_replace('"', '""', $response->answer_title) . '"',
            ];

            if ($includeContent) {
                $row[] = '"' . str_replace('"', '""', $response->content ?? '') . '"';
            }

            if ($includeTimestamps) {
                $row[] = $response->created_at;
            }

            $csv .= implode(',', $row) . "\n";
        }

        return $csv;
    }

    /**
     * Export responses to XML format
     */
    protected function exportToXml(Collection $responses): string
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n<responses>\n";

        foreach ($responses as $response) {
            $xml .= "  <response>\n";
            $xml .= "    <questionnaire_id>{$response->questionnaire_id}</questionnaire_id>\n";
            $xml .= "    <questionnaire_name>" . htmlspecialchars($response->questionnaire_name ?? '') . "</questionnaire_name>\n";
            $xml .= "    <question>" . htmlspecialchars($response->question_title) . "</question>\n";
            $xml .= "    <answer>" . htmlspecialchars($response->answer_title) . "</answer>\n";
            $xml .= "    <content>" . htmlspecialchars($response->content ?? '') . "</content>\n";
            $xml .= "    <created_at>{$response->created_at}</created_at>\n";
            $xml .= "  </response>\n";
        }

        $xml .= "</responses>\n";

        return $xml;
    }
}