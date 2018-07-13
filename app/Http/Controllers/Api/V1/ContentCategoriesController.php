<?php

namespace App\Http\Controllers\Api\V1;

use App\ContentCategory;
use App\Http\Controllers\Controller;
use App\Http\Resources\ContentCategory as ContentCategoryResource;
use App\Http\Requests\Admin\StoreContentCategoriesRequest;
use App\Http\Requests\Admin\UpdateContentCategoriesRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;



class ContentCategoriesController extends Controller
{
    public function index()
    {
        

        return new ContentCategoryResource(ContentCategory::with([])->get());
    }

    public function show($id)
    {
        if (Gate::denies('content_category_view')) {
            return abort(401);
        }

        $content_category = ContentCategory::with([])->findOrFail($id);

        return new ContentCategoryResource($content_category);
    }

    public function store(StoreContentCategoriesRequest $request)
    {
        if (Gate::denies('content_category_create')) {
            return abort(401);
        }

        $content_category = ContentCategory::create($request->all());
        
        

        return (new ContentCategoryResource($content_category))
            ->response()
            ->setStatusCode(201);
    }

    public function update(UpdateContentCategoriesRequest $request, $id)
    {
        if (Gate::denies('content_category_edit')) {
            return abort(401);
        }

        $content_category = ContentCategory::findOrFail($id);
        $content_category->update($request->all());
        
        
        

        return (new ContentCategoryResource($content_category))
            ->response()
            ->setStatusCode(202);
    }

    public function destroy($id)
    {
        if (Gate::denies('content_category_delete')) {
            return abort(401);
        }

        $content_category = ContentCategory::findOrFail($id);
        $content_category->delete();

        return response(null, 204);
    }
}
