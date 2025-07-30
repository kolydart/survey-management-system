<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreSurveysRequest;
use App\Http\Requests\Admin\UpdateSurveysRequest;
use App\Item;
use App\Survey;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class SurveysController extends Controller
{
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
            $duplicates = $this->get_duplicates($id);
        }

        return view('admin.surveys.show', compact('survey', 'questionnaires', 'items', 'duplicates'));
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

    protected function get_duplicates($survey_id)
    {
        /** get duplicates */
        $loguseragent = new \App\Loguseragent();
        $duplicates = [];

        /** get $survey->questionnaires - optimized to only get IDs */
        $questionnaires_arr = \App\Questionnaire::where('survey_id', $survey_id)
            ->pluck('id')
            ->toArray();

        // Early return if no questionnaires
        if (empty($questionnaires_arr)) {
            return $duplicates;
        }

        /** Batch load all loguseragents for this survey to reduce queries */
        $allLogs = $loguseragent::whereIn('item_id', $questionnaires_arr)
            ->select('id', 'item_id', 'ipv6', 'os', 'os_version', 'browser', 'browser_version', 'created_at')
            ->get()
            ->groupBy(function($item) {
                return $item->ipv6 . '|' . $item->os . '|' . $item->os_version . '|' . $item->browser . '|' . $item->browser_version;
            });

        // Process duplicates from grouped data
        foreach ($allLogs as $logs) {
            if ($logs->count() > 1) {
                $first = $logs->first();
                $row = [
                    'type' => 'ipsw',
                    'value' => [
                        'ipv6' => $first->ipv6,
                        'os' => $first->os,
                        'os_version' => $first->os_version,
                        'browser' => $first->browser,
                        'browser_version' => $first->browser_version
                    ],
                    'count' => $logs->count(),
                    'loguseragents' => $logs
                ];
                $duplicates[] = $row;
            }
        }

        return $duplicates;
    }
}
