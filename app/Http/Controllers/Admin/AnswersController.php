<?php

namespace App\Http\Controllers\Admin;

use App\Answer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreAnswersRequest;
use App\Http\Requests\Admin\UpdateAnswersRequest;

class AnswersController extends Controller
{
    /**
     * Display a listing of Answer.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (! Gate::allows('answer_access')) {
            return abort(401);
        }


        if (request('show_deleted') == 1) {
            if (! Gate::allows('answer_delete')) {
                return abort(401);
            }
            $answers = Answer::onlyTrashed()->get();
        } else {
            $answers = Answer::all();
        }

        return view('admin.answers.index', compact('answers'));
    }

    /**
     * Show the form for creating new Answer.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (! Gate::allows('answer_create')) {
            return abort(401);
        }
        return view('admin.answers.create');
    }

    /**
     * Store a newly created Answer in storage.
     *
     * @param  \App\Http\Requests\StoreAnswersRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreAnswersRequest $request)
    {
        if (! Gate::allows('answer_create')) {
            return abort(401);
        }
        $answer = Answer::create($request->all());



        return redirect()->route('admin.answers.index');
    }


    /**
     * Show the form for editing Answer.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (! Gate::allows('answer_edit')) {
            return abort(401);
        }
        $answer = Answer::findOrFail($id);

        return view('admin.answers.edit', compact('answer'));
    }

    /**
     * Update Answer in storage.
     *
     * @param  \App\Http\Requests\UpdateAnswersRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateAnswersRequest $request, $id)
    {
        if (! Gate::allows('answer_edit')) {
            return abort(401);
        }
        $answer = Answer::findOrFail($id);
        $answer->update($request->all());



        return redirect()->route('admin.answers.show',$id);
    }


    /**
     * Display Answer.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (! Gate::allows('answer_view')) {
            return abort(401);
        }
        $responses = \App\Response::where('answer_id', $id)->get();$answerlists = \App\Answerlist::whereHas('answers',
                    function ($query) use ($id) {
                        $query->where('id', $id);
                    })->get();

        $answer = Answer::findOrFail($id);

        return view('admin.answers.show', compact('answer', 'responses', 'answerlists'));
    }


    /**
     * Remove Answer from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (! Gate::allows('answer_delete')) {
            return abort(401);
        }
        $answer = Answer::findOrFail($id);
        $answer->delete();

        return redirect()->route('admin.answers.index');
    }

    /**
     * Delete all selected Answer at once.
     *
     * @param Request $request
     */
    public function massDestroy(Request $request)
    {
        if (! Gate::allows('answer_delete')) {
            return abort(401);
        }
        if ($request->input('ids')) {
            $entries = Answer::whereIn('id', $request->input('ids'))->get();

            foreach ($entries as $entry) {
                $entry->delete();
            }
        }
    }


    /**
     * Restore Answer from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function restore($id)
    {
        if (! Gate::allows('answer_delete')) {
            return abort(401);
        }
        $answer = Answer::onlyTrashed()->findOrFail($id);
        $answer->restore();

        return redirect()->route('admin.answers.index');
    }

    /**
     * Permanently delete Answer from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function perma_del($id)
    {
        if (! Gate::allows('answer_delete')) {
            return abort(401);
        }
        $answer = Answer::onlyTrashed()->findOrFail($id);
        $answer->forceDelete();

        return redirect()->route('admin.answers.index');
    }
}
