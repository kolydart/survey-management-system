<?php

namespace App\Http\Controllers\Api\V1;

use App\Institution;
use App\Http\Controllers\Controller;
use App\Http\Resources\Institution as InstitutionResource;
use App\Http\Requests\Admin\StoreInstitutionsRequest;
use App\Http\Requests\Admin\UpdateInstitutionsRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;



class InstitutionsController extends Controller
{
    public function index()
    {
        

        return new InstitutionResource(Institution::with([])->get());
    }

    public function show($id)
    {
        if (Gate::denies('institution_view')) {
            return abort(401);
        }

        $institution = Institution::with([])->findOrFail($id);

        return new InstitutionResource($institution);
    }

    public function store(StoreInstitutionsRequest $request)
    {
        if (Gate::denies('institution_create')) {
            return abort(401);
        }

        $institution = Institution::create($request->all());
        
        

        return (new InstitutionResource($institution))
            ->response()
            ->setStatusCode(201);
    }

    public function update(UpdateInstitutionsRequest $request, $id)
    {
        if (Gate::denies('institution_edit')) {
            return abort(401);
        }

        $institution = Institution::findOrFail($id);
        $institution->update($request->all());
        
        
        

        return (new InstitutionResource($institution))
            ->response()
            ->setStatusCode(202);
    }

    public function destroy($id)
    {
        if (Gate::denies('institution_delete')) {
            return abort(401);
        }

        $institution = Institution::findOrFail($id);
        $institution->delete();

        return response(null, 204);
    }
}
