<?php

namespace App\Http\Controllers\Api\V1;

use App\Answerlist;
use App\Http\Controllers\Controller;
use App\Http\Resources\Answerlist as AnswerlistResource;
use App\Http\Requests\Admin\StoreAnswerlistsRequest;
use App\Http\Requests\Admin\UpdateAnswerlistsRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;



class AnswerlistsController extends Controller
{
    public function index()
    {
        

        return new AnswerlistResource(Answerlist::with([])->get());
    }

    public function show($id)
    {
        if (Gate::denies('answerlist_view')) {
            return abort(401);
        }

        $answerlist = Answerlist::with([])->findOrFail($id);

        return new AnswerlistResource($answerlist);
    }

    public function store(StoreAnswerlistsRequest $request)
    {
        if (Gate::denies('answerlist_create')) {
            return abort(401);
        }

        $answerlist = Answerlist::create($request->all());
        
        

        return (new AnswerlistResource($answerlist))
            ->response()
            ->setStatusCode(201);
    }

    public function update(UpdateAnswerlistsRequest $request, $id)
    {
        if (Gate::denies('answerlist_edit')) {
            return abort(401);
        }

        $answerlist = Answerlist::findOrFail($id);
        $answerlist->update($request->all());
        
        
        

        return (new AnswerlistResource($answerlist))
            ->response()
            ->setStatusCode(202);
    }

    public function destroy($id)
    {
        if (Gate::denies('answerlist_delete')) {
            return abort(401);
        }

        $answerlist = Answerlist::findOrFail($id);
        $answerlist->delete();

        return response(null, 204);
    }
}
