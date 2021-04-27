<?php

namespace App\Http\Controllers\Admin;

use App\Answerlist;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreAnswerlistsRequest;
use App\Http\Requests\Admin\UpdateAnswerlistsRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class AnswerlistsController extends Controller
{
    /**
     * Display a listing of Answerlist.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (! Gate::allows('answerlist_access')) {
            return abort(401);
        }

        if (request('show_deleted') == 1) {
            if (! Gate::allows('answerlist_delete')) {
                return abort(401);
            }
            $answerlists = Answerlist::onlyTrashed()->get();
        } else {
            $answerlists = Answerlist::all();
        }

        return view('admin.answerlists.index', compact('answerlists'));
    }

    /**
     * Show the form for creating new Answerlist.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (! Gate::allows('answerlist_create')) {
            return abort(401);
        }

        $answers = \App\Answer::get()->pluck('title', 'id');
        $hidden_answer = \App\Answer::hidden();

        return view('admin.answerlists.create', compact('answers', 'hidden_answer'));
    }

    /**
     * Store a newly created Answerlist in storage.
     *
     * @param  \App\Http\Requests\StoreAnswerlistsRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreAnswerlistsRequest $request)
    {
        if (! Gate::allows('answerlist_create')) {
            return abort(401);
        }
        $answerlist = Answerlist::create($request->all());
        $answerlist->answers()->sync(array_filter((array) $request->input('answers')));

        foreach ($request->input('questions', []) as $data) {
            $answerlist->questions()->create($data);
        }

        return redirect()->route('admin.answerlists.index');
    }

    /**
     * Show the form for editing Answerlist.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (! Gate::allows('answerlist_edit')) {
            return abort(401);
        }

        $answers = \App\Answer::get()->pluck('title', 'id');
        $answerlist = Answerlist::findOrFail($id);
        $hidden_answer = \App\Answer::hidden();

        return view('admin.answerlists.edit', compact('answerlist', 'answers', 'hidden_answer'));
    }

    /**
     * Update Answerlist in storage.
     *
     * @param  \App\Http\Requests\UpdateAnswerlistsRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateAnswerlistsRequest $request, $id)
    {
        if (! Gate::allows('answerlist_edit')) {
            return abort(401);
        }
        $answerlist = Answerlist::findOrFail($id);
        $answerlist->update($request->all());
        $answerlist->answers()->sync(array_filter((array) $request->input('answers')));

        $questions = $answerlist->questions;
        $currentQuestionData = [];
        foreach ($request->input('questions', []) as $index => $data) {
            if (is_int($index)) {
                $answerlist->questions()->create($data);
            } else {
                $id = explode('-', $index)[1];
                $currentQuestionData[$id] = $data;
            }
        }
        foreach ($questions as $item) {
            if (isset($currentQuestionData[$item->id])) {
                $item->update($currentQuestionData[$item->id]);
            } else {
                $item->delete();
            }
        }

        return redirect()->route('admin.answerlists.index');
    }

    /**
     * Display Answerlist.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (! Gate::allows('answerlist_view')) {
            return abort(401);
        }

        $answers = \App\Answer::get()->pluck('title', 'id');
        $questions = \App\Question::where('answerlist_id', $id)->get();

        $answerlist = Answerlist::findOrFail($id);

        return view('admin.answerlists.show', compact('answerlist', 'questions'));
    }

    /**
     * Remove Answerlist from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (! Gate::allows('answerlist_delete')) {
            return abort(401);
        }
        $answerlist = Answerlist::findOrFail($id);
        $answerlist->delete();

        return redirect()->route('admin.answerlists.index');
    }

    /**
     * Delete all selected Answerlist at once.
     *
     * @param Request $request
     */
    public function massDestroy(Request $request)
    {
        if (! Gate::allows('answerlist_delete')) {
            return abort(401);
        }
        if ($request->input('ids')) {
            $entries = Answerlist::whereIn('id', $request->input('ids'))->get();

            foreach ($entries as $entry) {
                $entry->delete();
            }
        }
    }

    /**
     * Restore Answerlist from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function restore($id)
    {
        if (! Gate::allows('answerlist_delete')) {
            return abort(401);
        }
        $answerlist = Answerlist::onlyTrashed()->findOrFail($id);
        $answerlist->restore();

        return redirect()->route('admin.answerlists.index');
    }

    /**
     * Permanently delete Answerlist from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function perma_del($id)
    {
        if (! Gate::allows('answerlist_delete')) {
            return abort(401);
        }
        $answerlist = Answerlist::onlyTrashed()->findOrFail($id);
        $answerlist->forceDelete();

        return redirect()->route('admin.answerlists.index');
    }
}
