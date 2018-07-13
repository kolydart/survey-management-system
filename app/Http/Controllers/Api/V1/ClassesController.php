<?php

namespace App\Http\Controllers\Api\V1;

use App\Class;
use App\Http\Controllers\Controller;
use App\Http\Resources\Class as ClassResource;
use App\Http\Requests\Admin\StoreClassesRequest;
use App\Http\Requests\Admin\UpdateClassesRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;



class ClassesController extends Controller
{
    public function index()
    {
        

        return new ClassResource(Class::with([])->get());
    }

    public function show($id)
    {
        if (Gate::denies('class_view')) {
            return abort(401);
        }

        $class = Class::with([])->findOrFail($id);

        return new ClassResource($class);
    }

    public function store(StoreClassesRequest $request)
    {
        if (Gate::denies('class_create')) {
            return abort(401);
        }

        $class = Class::create($request->all());
        
        

        return (new ClassResource($class))
            ->response()
            ->setStatusCode(201);
    }

    public function update(UpdateClassesRequest $request, $id)
    {
        if (Gate::denies('class_edit')) {
            return abort(401);
        }

        $class = Class::findOrFail($id);
        $class->update($request->all());
        
        
        

        return (new ClassResource($class))
            ->response()
            ->setStatusCode(202);
    }

    public function destroy($id)
    {
        if (Gate::denies('class_delete')) {
            return abort(401);
        }

        $class = Class::findOrFail($id);
        $class->delete();

        return response(null, 204);
    }
}
