<?php

namespace App\Http\Controllers\Admin;

use App\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreResponsesRequest;
use App\Http\Requests\Admin\UpdateResponsesRequest;
use Yajra\DataTables\DataTables;

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


        
        if (request()->ajax()) {
            $query = Response::query();
            $query->with("questionnaire");
            $query->with("question");
            $query->with("answer");
            $template = 'actionsTemplate';
            if(request('show_deleted') == 1) {
                
        if (! Gate::allows('response_delete')) {
            return abort(401);
        }
                $query->onlyTrashed();
                $template = 'restoreTemplate';
            }
            $query->select([
                'responses.id',
                'responses.questionnaire_id',
                'responses.question_id',
                'responses.answer_id',
                'responses.content',
                'responses.created_at',
            ]);
            $table = Datatables::of($query);

            $table->setRowAttr([
                'data-entry-id' => '{{$id}}',
            ]);
            $table->addColumn('massDelete', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');
            $table->editColumn('actions', function ($row) use ($template) {
                $gateKey  = 'response_';
                $routeKey = 'admin.responses';

                return view($template, compact('row', 'gateKey', 'routeKey'));
            });
            $table->editColumn('questionnaire.id', function ($row) {
                return $row->questionnaire ? $row->questionnaire->id : '';
            });
            $table->editColumn('question.title', function ($row) {
                return $row->question ? $row->question->title : '';
            });
            $table->editColumn('answer.title', function ($row) {
                return $row->answer ? $row->answer->title : '';
            });
            $table->editColumn('content', function ($row) {
                return $row->content ? $row->content : '';
            });

            $table->rawColumns(['actions','massDelete']);

            return $table->make(true);
        }

        return view('admin.responses.index');
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
        
        $questionnaires = \App\Questionnaire::get()->pluck('id', 'id')->prepend(trans('quickadmin.qa_please_select'), '');
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
