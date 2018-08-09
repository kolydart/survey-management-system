<?php

namespace App\Http\Controllers\Admin;

use App\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreResponsesRequest;
use App\Http\Requests\Admin\UpdateResponsesRequest;

class ResponsesController extends Controller
{
    /**
     * Display a listing of Response.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (! Gate::allows('response_access')) {
            return abort(401);
        }


        if (request('show_deleted') == 1) {
            if (! Gate::allows('response_delete')) {
                return abort(401);
            }
            $responses = Response::onlyTrashed()->get();
        } else {
            $responses = Response::all();
        }

        return view('admin.responses.index', compact('responses'));
    }

    /**
     * Show the form for creating new Response.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (! Gate::allows('response_create')) {
            return abort(401);
        }
        
        $questionnaires = \App\Questionnaire::get()->pluck('name', 'id')->prepend(trans('quickadmin.qa_please_select'), '');
        $questions = \App\Question::get()->pluck('title', 'id')->prepend(trans('quickadmin.qa_please_select'), '');
        $answers = \App\Answer::get()->pluck('title', 'id')->prepend(trans('quickadmin.qa_please_select'), '');

        return view('admin.responses.create', compact('questionnaires', 'questions', 'answers'));
    }

    /**
     * Store a newly created Response in storage.
     *
     * @param  \App\Http\Requests\StoreResponsesRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreResponsesRequest $request)
    {
        if (! Gate::allows('response_create')) {
            return abort(401);
        }
        $response = Response::create($request->all());



        return redirect()->route('admin.responses.index');
    }


    /**
     * Show the form for editing Response.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (! Gate::allows('response_edit')) {
            return abort(401);
        }
        
        $questionnaires = \App\Questionnaire::get()->pluck('name', 'id')->prepend(trans('quickadmin.qa_please_select'), '');
        $questions = \App\Question::get()->pluck('title', 'id')->prepend(trans('quickadmin.qa_please_select'), '');
        $answers = \App\Answer::get()->pluck('title', 'id')->prepend(trans('quickadmin.qa_please_select'), '');

        $response = Response::findOrFail($id);

        return view('admin.responses.edit', compact('response', 'questionnaires', 'questions', 'answers'));
    }

    /**
     * Update Response in storage.
     *
     * @param  \App\Http\Requests\UpdateResponsesRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateResponsesRequest $request, $id)
    {
        if (! Gate::allows('response_edit')) {
            return abort(401);
        }
        $response = Response::findOrFail($id);
        $response->update($request->all());



        return redirect()->route('admin.responses.index');
    }


    /**
     * Display Response.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (! Gate::allows('response_view')) {
            return abort(401);
        }
        $response = Response::findOrFail($id);

        return view('admin.responses.show', compact('response'));
    }


    /**
     * Remove Response from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (! Gate::allows('response_delete')) {
            return abort(401);
        }
        $response = Response::findOrFail($id);
        $response->delete();

        return redirect()->route('admin.responses.index');
    }

    /**
     * Delete all selected Response at once.
     *
     * @param Request $request
     */
    public function massDestroy(Request $request)
    {
        if (! Gate::allows('response_delete')) {
            return abort(401);
        }
        if ($request->input('ids')) {
            $entries = Response::whereIn('id', $request->input('ids'))->get();

            foreach ($entries as $entry) {
                $entry->delete();
            }
        }
    }


    /**
     * Restore Response from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function restore($id)
    {
        if (! Gate::allows('response_delete')) {
            return abort(401);
        }
        $response = Response::onlyTrashed()->findOrFail($id);
        $response->restore();

        return redirect()->route('admin.responses.index');
    }

    /**
     * Permanently delete Response from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function perma_del($id)
    {
        if (! Gate::allows('response_delete')) {
            return abort(401);
        }
        $response = Response::onlyTrashed()->findOrFail($id);
        $response->forceDelete();

        return redirect()->route('admin.responses.index');
    }
}
