<?php

namespace App\Http\Controllers\Api\V1;

use App\Response;
use App\Http\Controllers\Controller;
use App\Http\Resources\Response as ResponseResource;
use App\Http\Requests\Admin\StoreResponsesRequest;
use App\Http\Requests\Admin\UpdateResponsesRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;



class ResponsesController extends Controller
{
    public function index()
    {
        

        return new ResponseResource(Response::with(['question'])->get());
    }

    public function show($id)
    {
        if (Gate::denies('response_view')) {
            return abort(401);
        }

        $response = Response::with(['question'])->findOrFail($id);

        return new ResponseResource($response);
    }

    public function store(StoreResponsesRequest $request)
    {
        if (Gate::denies('response_create')) {
            return abort(401);
        }

        $response = Response::create($request->all());
        
        

        return (new ResponseResource($response))
            ->response()
            ->setStatusCode(201);
    }

    public function update(UpdateResponsesRequest $request, $id)
    {
        if (Gate::denies('response_edit')) {
            return abort(401);
        }

        $response = Response::findOrFail($id);
        $response->update($request->all());
        
        
        

        return (new ResponseResource($response))
            ->response()
            ->setStatusCode(202);
    }

    public function destroy($id)
    {
        if (Gate::denies('response_delete')) {
            return abort(401);
        }

        $response = Response::findOrFail($id);
        $response->delete();

        return response(null, 204);
    }
}
