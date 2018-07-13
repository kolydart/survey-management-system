<?php

namespace App\Http\Controllers\Api\V1;

use App\Answer;
use App\Http\Controllers\Controller;
use App\Http\Resources\Answer as AnswerResource;
use App\Http\Requests\Admin\StoreAnswersRequest;
use App\Http\Requests\Admin\UpdateAnswersRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;



class AnswersController extends Controller
{
    public function index()
    {
        

        return new AnswerResource(Answer::with(['answerlists'])->get());
    }

    public function show($id)
    {
        if (Gate::denies('answer_view')) {
            return abort(401);
        }

        $answer = Answer::with(['answerlists'])->findOrFail($id);

        return new AnswerResource($answer);
    }

    public function store(StoreAnswersRequest $request)
    {
        if (Gate::denies('answer_create')) {
            return abort(401);
        }

        $answer = Answer::create($request->all());
        $answer->answerlists()->sync($request->input('answerlists', []));
        

        return (new AnswerResource($answer))
            ->response()
            ->setStatusCode(201);
    }

    public function update(UpdateAnswersRequest $request, $id)
    {
        if (Gate::denies('answer_edit')) {
            return abort(401);
        }

        $answer = Answer::findOrFail($id);
        $answer->update($request->all());
        $answer->answerlists()->sync($request->input('answerlists', []));
        
        

        return (new AnswerResource($answer))
            ->response()
            ->setStatusCode(202);
    }

    public function destroy($id)
    {
        if (Gate::denies('answer_delete')) {
            return abort(401);
        }

        $answer = Answer::findOrFail($id);
        $answer->delete();

        return response(null, 204);
    }
}
