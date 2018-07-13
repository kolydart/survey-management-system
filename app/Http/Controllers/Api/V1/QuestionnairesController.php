<?php

namespace App\Http\Controllers\Api\V1;

use App\Questionnaire;
use App\Http\Controllers\Controller;
use App\Http\Resources\Questionnaire as QuestionnaireResource;
use App\Http\Requests\Admin\StoreQuestionnairesRequest;
use App\Http\Requests\Admin\UpdateQuestionnairesRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;



class QuestionnairesController extends Controller
{
    public function index()
    {
        

        return new QuestionnaireResource(Questionnaire::with(['survey'])->get());
    }

    public function show($id)
    {
        if (Gate::denies('questionnaire_view')) {
            return abort(401);
        }

        $questionnaire = Questionnaire::with(['survey'])->findOrFail($id);

        return new QuestionnaireResource($questionnaire);
    }

    public function store(StoreQuestionnairesRequest $request)
    {
        if (Gate::denies('questionnaire_create')) {
            return abort(401);
        }

        $questionnaire = Questionnaire::create($request->all());
        
        

        return (new QuestionnaireResource($questionnaire))
            ->response()
            ->setStatusCode(201);
    }

    public function update(UpdateQuestionnairesRequest $request, $id)
    {
        if (Gate::denies('questionnaire_edit')) {
            return abort(401);
        }

        $questionnaire = Questionnaire::findOrFail($id);
        $questionnaire->update($request->all());
        
        
        

        return (new QuestionnaireResource($questionnaire))
            ->response()
            ->setStatusCode(202);
    }

    public function destroy($id)
    {
        if (Gate::denies('questionnaire_delete')) {
            return abort(401);
        }

        $questionnaire = Questionnaire::findOrFail($id);
        $questionnaire->delete();

        return response(null, 204);
    }
}
