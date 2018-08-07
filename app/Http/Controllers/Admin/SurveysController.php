<?php

namespace App\Http\Controllers\Admin;

use App\Survey;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreSurveysRequest;
use App\Http\Requests\Admin\UpdateSurveysRequest;

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
            $surveys = Survey::all();
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
        
        $institutions = \App\Institution::get()->pluck('title', 'id')->prepend(trans('quickadmin.qa_please_select'), '');
        $categories = \App\Category::get()->pluck('title', 'id');

        $groups = \App\Group::get()->pluck('title', 'id')->prepend(trans('quickadmin.qa_please_select'), '');

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
        $survey->category()->sync(array_filter((array)$request->input('category')));



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

        $groups = \App\Group::get()->pluck('title', 'id')->prepend(trans('quickadmin.qa_please_select'), '');

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
        $survey->category()->sync(array_filter((array)$request->input('category')));



        return redirect()->route('admin.surveys.index');
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
        
        $institutions = \App\Institution::get()->pluck('title', 'id')->prepend(trans('quickadmin.qa_please_select'), '');
        $categories = \App\Category::get()->pluck('title', 'id');

        $groups = \App\Group::get()->pluck('title', 'id')->prepend(trans('quickadmin.qa_please_select'), '');$questionnaires = \App\Questionnaire::where('survey_id', $id)->get();$items = \App\Item::where('survey_id', $id)->get();

        $survey = Survey::findOrFail($id);

        return view('admin.surveys.show', compact('survey', 'questionnaires', 'items'));
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
}
