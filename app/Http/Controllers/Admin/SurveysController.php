<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreSurveysRequest;
use App\Http\Requests\Admin\UpdateSurveysRequest;
use App\Item;
use App\Survey;
use App\Services\ChartDataService;
use App\Services\DuplicateDetectionService;
use App\Services\SurveyStatisticsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class SurveysController extends Controller
{
    public function __construct(
        protected SurveyStatisticsService $statisticsService,
        protected ChartDataService $chartService,
        protected DuplicateDetectionService $duplicateService
    ) {
    }
    /**
     * Display a listing of Survey.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (! Gate::allows('survey_access')) {
            return abort(401);
        }

        if (request('show_deleted') == 1) {
            if (! Gate::allows('survey_delete')) {
                return abort(401);
            }
            $surveys = Survey::onlyTrashed()->get();
        } else {
            $surveys = Survey::latest()->get();
        }

        return view('admin.surveys.index', compact('surveys'));
    }

    /**
     * Show the form for creating new Survey.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (! Gate::allows('survey_create')) {
            return abort(401);
        }

        $institutions = \App\Institution::latest()->get()->pluck('title', 'id')->prepend(trans('quickadmin.qa_please_select'), '');
        $categories = \App\Category::get()->pluck('title', 'id');

        $groups = \App\Group::get()->pluck('title', 'id');

        return view('admin.surveys.create', compact('institutions', 'categories', 'groups'));
    }

    /**
     * Store a newly created Survey in storage.
     *
     * @param  \App\Http\Requests\StoreSurveysRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreSurveysRequest $request)
    {
        if (! Gate::allows('survey_create')) {
            return abort(401);
        }
        $survey = Survey::create($request->all());
        $survey->category()->sync(array_filter((array) $request->input('category')));
        $survey->group()->sync(array_filter((array) $request->input('group')));

        return redirect()->route('admin.surveys.index');
    }

    /**
     * Show the form for editing Survey.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (! Gate::allows('survey_edit')) {
            return abort(401);
        }

        $institutions = \App\Institution::get()->pluck('title', 'id')->prepend(trans('quickadmin.qa_please_select'), '');
        $categories = \App\Category::get()->pluck('title', 'id');

        $groups = \App\Group::get()->pluck('title', 'id');

        $survey = Survey::findOrFail($id);

        return view('admin.surveys.edit', compact('survey', 'institutions', 'categories', 'groups'));
    }

    /**
     * Update Survey in storage.
     *
     * @param  \App\Http\Requests\UpdateSurveysRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateSurveysRequest $request, $id)
    {
        if (! Gate::allows('survey_edit')) {
            return abort(401);
        }
        $survey = Survey::findOrFail($id);
        $survey->update($request->all());
        $survey->category()->sync(array_filter((array) $request->input('category')));
        $survey->group()->sync(array_filter((array) $request->input('group')));

        return redirect()->route('admin.surveys.show', $id);
    }

    /**
     * Display Survey.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (! Gate::allows('survey_view')) {
            return abort(401);
        }

        // Determine view mode (backward compatible)
        $viewMode = request()->get('view', 'graph');

        // Backward compatibility: rawdata=true maps to text mode
        if (request()->has('rawdata') && request()->get('rawdata')) {
            $viewMode = 'text';
        }

        // Handle export modes (JSON/CSV/JSON-Results/CSV-Results)
        if (in_array($viewMode, ['json', 'csv', 'json-results', 'csv-results'])) {
            return $this->exportRawData($id, $viewMode);
        }

        // Eager load survey with its relationships including questionnaires with responses
        $survey = Survey::with([
            'category',
            'group',
            'questionnaires' => function($query) {
                $query->latest()->with([
                    'responses' => function($q) {
                        $q->select('id', 'questionnaire_id', 'question_id', 'answer_id');
                    }
                ]);
            }
        ])->findOrFail($id);

        // Get questionnaires from the already loaded relationship
        $questionnaires = $survey->questionnaires;

        // Optimize items loading - avoid loading all responses
        $items = \App\Item::with([
            'survey.questionnaires', // Needed for get_answers() method
            'question' => function($query) {
                $query->with(['answerlist.answers']);
            }
        ])
            ->where('survey_id', $id)
            ->orderBy('order')
            ->get();

        // Make duplicate detection optional or async
        $duplicates = [];
        if (request()->has('check_duplicates')) {
            $method = request()->get('method', 'activity_log');

            $duplicates = match($method) {
                'activity_log' => $this->duplicateService->findByActivityLog($id),
                'similarity' => $this->duplicateService->findByContentSimilarity($id),
                default => []
            };
        }

        return view('admin.surveys.show', compact(
            'survey',
            'questionnaires',
            'items',
            'duplicates'
        ))->with([
            'statisticsService' => $this->statisticsService,
            'chartService' => $this->chartService,
            'viewMode' => $viewMode
        ]);
    }

    /**
     * Remove Survey from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (! Gate::allows('survey_delete')) {
            return abort(401);
        }
        $survey = Survey::findOrFail($id);
        $survey->delete();

        return redirect()->route('admin.surveys.index');
    }

    /**
     * Delete all selected Survey at once.
     *
     * @param Request $request
     */
    public function massDestroy(Request $request)
    {
        if (! Gate::allows('survey_delete')) {
            return abort(401);
        }
        if ($request->input('ids')) {
            $entries = Survey::whereIn('id', $request->input('ids'))->get();

            foreach ($entries as $entry) {
                $entry->delete();
            }
        }
    }

    /**
     * Restore Survey from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function restore($id)
    {
        if (! Gate::allows('survey_delete')) {
            return abort(401);
        }
        $survey = Survey::onlyTrashed()->findOrFail($id);
        $survey->restore();

        return redirect()->route('admin.surveys.index');
    }

    /**
     * Permanently delete Survey from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function perma_del($id)
    {
        if (! Gate::allows('survey_delete')) {
            return abort(401);
        }
        $survey = Survey::onlyTrashed()->findOrFail($id);
        $survey->forceDelete();

        return redirect()->route('admin.surveys.index');
    }

    /**
     * clone Survey
     *
     * @param  App\Survey  $survey
     * @return \Illuminate\Http\Response
     */
    public function clone(Survey $survey)
    {
        if (! Gate::allows('survey_create')) {
            return abort(401);
        }

        $newSurvey = $survey->replicate();
        $newSurvey->completed = 0;
        $newSurvey->push(); //Push before to get id of $clone

        foreach (Item::where('survey_id', $survey->id)->get() as $item) {
            $newItem = $item->replicate();
            $newItem->survey_id = $newSurvey->id;
            $newItem->save();
        }

        foreach ($survey->category as $category) {
            $newSurvey->category()->attach($category);
        }

        foreach ($survey->group as $group) {
            $newSurvey->group()->attach($group);
        }

        return redirect()->route('admin.surveys.show', $newSurvey);
    }

    /**
     * Export survey data in machine-readable format for LLM analysis.
     * Supports JSON and CSV formats.
     *
     * @param  int  $id
     * @param  string  $format
     * @return \Illuminate\Http\Response
     */
    protected function exportRawData($id, $format = 'json')
    {
        // Load survey with all necessary relationships
        $survey = Survey::with([
            'institution',
            'category',
            'group',
            'items.question.answerlist.answers',
            'questionnaires.responses.question',
            'questionnaires.responses.answer'
        ])->findOrFail($id);

        // Build machine-readable data structure
        $data = [
            'survey' => [
                'id' => $survey->id,
                'title' => $survey->title,
                'alias' => $survey->alias,
                'introduction' => $survey->introduction,
                'notes' => $survey->notes,
                'access' => $survey->access,
                'completed' => (bool) $survey->completed,
                'inform' => (bool) $survey->inform,
                'institution' => $survey->institution ? $survey->institution->title : null,
                'categories' => $survey->category->pluck('title')->toArray(),
                'groups' => $survey->group->pluck('title')->toArray(),
                'created_at' => $survey->created_at?->toIso8601String(),
                'updated_at' => $survey->updated_at?->toIso8601String(),
            ],
            'questions' => [],
            'responses_summary' => [
                'total_questionnaires' => $survey->questionnaires->count(),
                'total_responses' => 0,
            ],
            'questionnaires' => [],
        ];

        // Build questions index with available answers
        foreach ($survey->items as $item) {
            if ($item->question) {
                $answers = [];
                if ($item->question->answerlist) {
                    foreach ($item->question->answerlist->answers as $answer) {
                        $answers[] = [
                            'id' => $answer->id,
                            'title' => $answer->title,
                            'open' => (bool) $answer->open,
                        ];
                    }
                }

                $data['questions'][] = [
                    'item_id' => $item->id,
                    'item_order' => $item->order,
                    'question_id' => $item->question->id,
                    'question_title' => $item->question->title,
                    'answerlist_id' => $item->question->answerlist_id,
                    'available_answers' => $answers,
                ];
            }
        }

        // Build questionnaires and responses
        foreach ($survey->questionnaires as $questionnaire) {
            $questionnaireData = [
                'id' => $questionnaire->id,
                'name' => $questionnaire->name,
                'created_at' => $questionnaire->created_at?->toIso8601String(),
                'responses' => [],
            ];

            foreach ($questionnaire->responses as $response) {
                $data['responses_summary']['total_responses']++;

                $questionnaireData['responses'][] = [
                    'response_id' => $response->id,
                    'question_id' => $response->question_id,
                    'question_title' => $response->question?->title,
                    'answer_id' => $response->answer_id,
                    'answer_title' => $response->answer?->title,
                    'content' => $response->content,
                    'created_at' => $response->created_at?->toIso8601String(),
                ];
            }

            $data['questionnaires'][] = $questionnaireData;
        }

        // Return data in requested format
        if ($format === 'csv') {
            return $this->exportAsCSV($data, $survey);
        }

        if ($format === 'csv-results') {
            return $this->exportAsCSVResults($id);
        }

        if ($format === 'json-results') {
            return $this->exportAsJSONResults($id);
        }

        // Default to JSON
        return $this->exportAsJSON($data, $survey);
    }

    /**
     * Export survey data as JSON format.
     * Optimized for LLM analysis with structured, hierarchical data.
     *
     * @param  array  $data
     * @param  \App\Survey  $survey
     * @return \Illuminate\Http\Response
     */
    protected function exportAsJSON(array $data, Survey $survey)
    {
        return response()->json($data, 200, [
            'Content-Type' => 'application/json',
            'Content-Disposition' => 'attachment; filename="survey_' . $survey->id . '_export.json"',
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }

    /**
     * Export survey data as CSV format.
     * Creates a flattened view suitable for spreadsheet analysis.
     *
     * @param  array  $data
     * @param  \App\Survey  $survey
     * @return \Illuminate\Http\Response
     */
    protected function exportAsCSV(array $data, Survey $survey)
    {
        $csvData = [];

        // CSV Header
        $csvData[] = [
            'questionnaire_id',
            'questionnaire_name',
            'questionnaire_created_at',
            'response_id',
            'question_id',
            'question_title',
            'answer_id',
            'answer_title',
            'response_content',
            'response_created_at',
        ];

        // CSV Rows - flatten questionnaires and responses
        foreach ($data['questionnaires'] as $questionnaire) {
            foreach ($questionnaire['responses'] as $response) {
                $csvData[] = [
                    $questionnaire['id'],
                    $questionnaire['name'],
                    $questionnaire['created_at'],
                    $response['response_id'],
                    $response['question_id'],
                    $response['question_title'],
                    $response['answer_id'],
                    $response['answer_title'],
                    $response['content'],
                    $response['created_at'],
                ];
            }
        }

        // Generate CSV content
        $output = fopen('php://temp', 'r+');
        foreach ($csvData as $row) {
            fputcsv($output, $row);
        }
        rewind($output);
        $csvContent = stream_get_contents($output);
        fclose($output);

        return response($csvContent, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="survey_' . $survey->id . '_export.csv"',
        ]);
    }

    /**
     * Export survey results in aggregated JSON format.
     * Includes only the survey template (questions/answers) and grouped results.
     * No individual questionnaire data - perfect for statistical analysis.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    protected function exportAsJSONResults($id)
    {
        // Load survey with necessary relationships for template
        $survey = Survey::with([
            'institution',
            'category',
            'group',
            'items.question.answerlist.answers',
            'questionnaires' // Need this to count responses
        ])->findOrFail($id);

        // Build results structure
        $data = [
            'survey' => [
                'id' => $survey->id,
                'title' => $survey->title,
                'alias' => $survey->alias,
                'introduction' => $survey->introduction,
                'notes' => $survey->notes,
                'access' => $survey->access,
                'completed' => (bool) $survey->completed,
                'institution' => $survey->institution ? $survey->institution->title : null,
                'categories' => $survey->category->pluck('title')->toArray(),
                'groups' => $survey->group->pluck('title')->toArray(),
                'total_responses' => $survey->questionnaires->count(),
                'created_at' => $survey->created_at?->toIso8601String(),
                'updated_at' => $survey->updated_at?->toIso8601String(),
            ],
            'questions' => [],
        ];

        // Build questions with aggregated results
        foreach ($survey->items as $item) {
            if (!$item->question || $item->label) {
                continue; // Skip labels and items without questions
            }

            $questionData = [
                'item_id' => $item->id,
                'item_order' => $item->order,
                'question_id' => $item->question->id,
                'question_title' => $item->question->title,
                'answerlist_id' => $item->question->answerlist_id,
                'answer_type' => $item->question->answerlist->type ?? null,
                'results' => [],
            ];

            // Get all responses for this question in this survey
            $responses = \App\Response::where('question_id', $item->question->id)
                ->whereIn('questionnaire_id', $survey->questionnaires->pluck('id'))
                ->get();

            $totalResponses = $responses->count();

            // For radio/checkbox questions: group by answer
            if ($item->question->answerlist && in_array($item->question->answerlist->type, ['radio', 'checkbox'])) {
                $answerCounts = $responses->groupBy('answer_id')->map(function ($group) {
                    return $group->count();
                });

                foreach ($item->question->answerlist->answers as $answer) {
                    $count = $answerCounts->get($answer->id, 0);
                    $percentage = $totalResponses > 0 ? round(($count / $totalResponses) * 100, 2) : 0;

                    $questionData['results'][] = [
                        'answer_id' => $answer->id,
                        'answer_title' => $answer->title,
                        'answer_is_open' => (bool) $answer->open,
                        'count' => $count,
                        'percentage' => $percentage,
                    ];
                }
            }
            // For text/number questions: provide statistics
            else {
                $contents = $responses->whereNotNull('content')
                    ->where('content', '!=', '')
                    ->pluck('content')
                    ->toArray();

                $questionData['results'] = [
                    'total_responses' => count($contents),
                    'response_rate' => $totalResponses > 0 ? round((count($contents) / $totalResponses) * 100, 2) : 0,
                ];

                // Add statistical analysis for numeric types
                if ($this->statisticsService->supportsStatistics($item->question->answerlist->type ?? '')) {
                    $stats = $this->statisticsService->calculateStatistics($contents);
                    if ($stats) {
                        $questionData['results']['statistics'] = [
                            'min' => $stats['min'],
                            'max' => $stats['max'],
                            'mean' => $stats['mean'],
                            'median' => $stats['median'],
                            'count' => $stats['count'],
                        ];
                    }
                }

                // For text responses, optionally include sample responses (first 10)
                if (!$this->statisticsService->supportsStatistics($item->question->answerlist->type ?? '')) {
                    $questionData['results']['sample_responses'] = array_slice($contents, 0, 10);
                }
            }

            $questionData['total_responses'] = $totalResponses;
            $data['questions'][] = $questionData;
        }

        return response()->json($data, 200, [
            'Content-Type' => 'application/json',
            'Content-Disposition' => 'attachment; filename="survey_' . $survey->id . '_results.json"',
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }

    /**
     * Export survey results in aggregated CSV format.
     * One row per answer option (for radio/checkbox) or per question (for text/numeric).
     * Perfect for spreadsheet analysis and pivot tables.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    protected function exportAsCSVResults($id)
    {
        // Load survey with necessary relationships
        $survey = Survey::with([
            'institution',
            'category',
            'group',
            'items.question.answerlist.answers',
            'questionnaires'
        ])->findOrFail($id);

        $csvData = [];

        // CSV Header
        $csvData[] = [
            'question_id',
            'question_order',
            'question_title',
            'question_type',
            'answer_id',
            'answer_title',
            'count',
            'percentage',
            'total_responses',
            'min',
            'max',
            'mean',
            'median',
            'statistics_count',
        ];

        // Build rows for each question
        foreach ($survey->items as $item) {
            if (!$item->question || $item->label) {
                continue; // Skip labels and items without questions
            }

            // Get all responses for this question
            $responses = \App\Response::where('question_id', $item->question->id)
                ->whereIn('questionnaire_id', $survey->questionnaires->pluck('id'))
                ->get();

            $totalResponses = $responses->count();
            $answerType = $item->question->answerlist ? $item->question->answerlist->type : 'text';

            // For radio/checkbox questions: one row per answer
            if ($item->question->answerlist && in_array($answerType, ['radio', 'checkbox'])) {
                $answerCounts = $responses->groupBy('answer_id')->map(function ($group) {
                    return $group->count();
                });

                foreach ($item->question->answerlist->answers as $answer) {
                    $count = $answerCounts->get($answer->id, 0);
                    $percentage = $totalResponses > 0 ? round(($count / $totalResponses) * 100, 2) : 0;

                    $csvData[] = [
                        $item->question->id,
                        $item->order,
                        $item->question->title,
                        $answerType,
                        $answer->id,
                        $answer->title,
                        $count,
                        $percentage,
                        $totalResponses,
                        '', // min (not applicable)
                        '', // max (not applicable)
                        '', // mean (not applicable)
                        '', // median (not applicable)
                        '', // statistics_count (not applicable)
                    ];
                }
            }
            // For numeric questions: one row with statistics
            elseif ($this->statisticsService->supportsStatistics($answerType)) {
                $contents = $responses->whereNotNull('content')
                    ->where('content', '!=', '')
                    ->pluck('content')
                    ->toArray();

                $stats = $this->statisticsService->calculateStatistics($contents);

                $csvData[] = [
                    $item->question->id,
                    $item->order,
                    $item->question->title,
                    $answerType,
                    '', // answer_id (not applicable)
                    '', // answer_title (not applicable)
                    '', // count (using statistics_count instead)
                    '', // percentage (not applicable)
                    $totalResponses,
                    $stats['min'] ?? '',
                    $stats['max'] ?? '',
                    $stats['mean'] ?? '',
                    $stats['median'] ?? '',
                    $stats['count'] ?? '',
                ];
            }
            // For text questions: one row with response count
            else {
                $contents = $responses->whereNotNull('content')
                    ->where('content', '!=', '')
                    ->pluck('content')
                    ->toArray();

                $responseCount = count($contents);
                $responseRate = $totalResponses > 0 ? round(($responseCount / $totalResponses) * 100, 2) : 0;

                $csvData[] = [
                    $item->question->id,
                    $item->order,
                    $item->question->title,
                    $answerType,
                    '', // answer_id (not applicable)
                    'Text responses', // answer_title
                    $responseCount,
                    $responseRate,
                    $totalResponses,
                    '', // min (not applicable)
                    '', // max (not applicable)
                    '', // mean (not applicable)
                    '', // median (not applicable)
                    '', // statistics_count (not applicable)
                ];
            }
        }

        // Generate CSV content
        $output = fopen('php://temp', 'r+');
        foreach ($csvData as $row) {
            fputcsv($output, $row);
        }
        rewind($output);
        $csvContent = stream_get_contents($output);
        fclose($output);

        return response($csvContent, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="survey_' . $survey->id . '_results.csv"',
        ]);
    }
}
