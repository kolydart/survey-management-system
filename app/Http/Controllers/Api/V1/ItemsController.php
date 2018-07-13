<?php

namespace App\Http\Controllers\Api\V1;

use App\Item;
use App\Http\Controllers\Controller;
use App\Http\Resources\Item as ItemResource;
use App\Http\Requests\Admin\StoreItemsRequest;
use App\Http\Requests\Admin\UpdateItemsRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;



class ItemsController extends Controller
{
    public function index()
    {
        

        return new ItemResource(Item::with(['survey', 'question'])->get());
    }

    public function show($id)
    {
        if (Gate::denies('item_view')) {
            return abort(401);
        }

        $item = Item::with(['survey', 'question'])->findOrFail($id);

        return new ItemResource($item);
    }

    public function store(StoreItemsRequest $request)
    {
        if (Gate::denies('item_create')) {
            return abort(401);
        }

        $item = Item::create($request->all());
        
        

        return (new ItemResource($item))
            ->response()
            ->setStatusCode(201);
    }

    public function update(UpdateItemsRequest $request, $id)
    {
        if (Gate::denies('item_edit')) {
            return abort(401);
        }

        $item = Item::findOrFail($id);
        $item->update($request->all());
        
        
        

        return (new ItemResource($item))
            ->response()
            ->setStatusCode(202);
    }

    public function destroy($id)
    {
        if (Gate::denies('item_delete')) {
            return abort(401);
        }

        $item = Item::findOrFail($id);
        $item->delete();

        return response(null, 204);
    }
}
