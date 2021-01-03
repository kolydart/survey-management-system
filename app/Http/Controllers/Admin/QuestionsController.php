<?php

namespace App\Http\Controllers\Admin;

use App\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreQuestionsRequest;
use App\Http\Requests\Admin\UpdateQuestionsRequest;

class QuestionsController extends Controller
{
    /**
     * Display a listing of Question.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (! Gate::allows('question_access')) {
            return abort(401);
        }


        if (request('show_deleted') == 1) {
            if (! Gate::allows('question_delete')) {
                return abort(401);
            }
            $questions = Question::onlyTrashed()->get();
        } else {
            $questions = Question::latest()->get();
        }

        return view('admin.questions.index', compact('questions'));
    }

    /**
     * Show the form for creating new Question.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (! Gate::allows('question_create')) {
            return abort(401);
        }
        
        $answerlists = \App\Answerlist::get()->pluck('title', 'id')->prepend(trans('quickadmin.qa_please_select'), '');

        return view('admin.questions.create', compact('answerlists'));
    }

    /**
     * Store a newly created Question in storage.
     *
     * @param  \App\Http\Requests\StoreQuestionsRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreQuestionsRequest $request)
    {
        if (! Gate::allows('question_create')) {
            return abort(401);
        }
        $question = Question::create($request->all());



        return redirect()->route('admin.questions.index');
    }


    /**
     * Show the form for editing Question.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (! Gate::allows('question_edit')) {
            return abort(401);
        }
        
        $answerlists = \App\Answerlist::get()->pluck('title', 'id')->prepend(trans('quickadmin.qa_please_select'), '');

        $question = Question::findOrFail($id);

        return view('admin.questions.edit', compact('question', 'answerlists'));
    }

    /**
     * Update Question in storage.
     *
     * @param  \App\Http\Requests\UpdateQuestionsRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateQuestionsRequest $request, $id)
    {
        if (! Gate::allows('question_edit')) {
            return abort(401);
        }
        $question = Question::findOrFail($id);
        $question->update($request->all());



        return redirect()->route('admin.questions.show',$id);
    }


    /**
     * Display Question.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (! Gate::allows('question_view')) {
            return abort(401);
        }
        
        $answerlists = \App\Answerlist::get()->pluck('title', 'id')->prepend(trans('quickadmin.qa_please_select'), '');$responses = \App\Response::where('question_id', $id)->get();$items = \App\Item::where('question_id', $id)->get();

        $question = Question::findOrFail($id);

        return view('admin.questions.show', compact('question', 'responses', 'items'));
    }


    /**
     * Remove Question from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (! Gate::allows('question_delete')) {
            return abort(401);
        }
        $question = Question::findOrFail($id);
        $question->delete();

        return redirect()->route('admin.questions.index');
    }

    /**
     * Delete all selected Question at once.
     *
     * @param Request $request
     */
    public function massDestroy(Request $request)
    {
        if (! Gate::allows('question_delete')) {
            return abort(401);
        }
        if ($request->input('ids')) {
            $entries = Question::whereIn('id', $request->input('ids'))->get();

            foreach ($entries as $entry) {
                $entry->delete();
            }
        }
    }


    /**
     * Restore Question from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function restore($id)
    {
        if (! Gate::allows('question_delete')) {
            return abort(401);
        }
        $question = Question::onlyTrashed()->findOrFail($id);
        $question->restore();

        return redirect()->route('admin.questions.index');
    }

    /**
     * Permanently delete Question from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function perma_del($id)
    {
        if (! Gate::allows('question_delete')) {
            return abort(401);
        }
        $question = Question::onlyTrashed()->findOrFail($id);
        $question->forceDelete();

        return redirect()->route('admin.questions.index');
    }
}
