<?php

namespace App\Http\Controllers\Api\V1;

use App\Survey;
use App\Http\Controllers\Controller;
use App\Http\Resources\Survey as SurveyResource;
use App\Http\Requests\Admin\StoreSurveysRequest;
use App\Http\Requests\Admin\UpdateSurveysRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;



class SurveysController extends Controller
{
    public function index()
    {
        

        return new SurveyResource(Survey::with(['institution', 'category', 'group'])->get());
    }

    public function show($id)
    {
        if (Gate::denies('survey_view')) {
            return abort(401);
        }

        $survey = Survey::with(['institution', 'category', 'group'])->findOrFail($id);

        return new SurveyResource($survey);
    }

    public function store(StoreSurveysRequest $request)
    {
        if (Gate::denies('survey_create')) {
            return abort(401);
        }

        $survey = Survey::create($request->all());
        $survey->category()->sync($request->input('category', []));
        

        return (new SurveyResource($survey))
            ->response()
            ->setStatusCode(201);
    }

    public function update(UpdateSurveysRequest $request, $id)
    {
        if (Gate::denies('survey_edit')) {
            return abort(401);
        }

        $survey = Survey::findOrFail($id);
        $survey->update($request->all());
        $survey->category()->sync($request->input('category', []));
        
        

        return (new SurveyResource($survey))
            ->response()
            ->setStatusCode(202);
    }

    public function destroy($id)
    {
        if (Gate::denies('survey_delete')) {
            return abort(401);
        }

        $survey = Survey::findOrFail($id);
        $survey->delete();

        return response(null, 204);
    }
}
