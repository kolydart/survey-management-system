<?php

namespace App\Http\Controllers\Api\V1;

use App\ContentTag;
use App\Http\Controllers\Controller;
use App\Http\Resources\ContentTag as ContentTagResource;
use App\Http\Requests\Admin\StoreContentTagsRequest;
use App\Http\Requests\Admin\UpdateContentTagsRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;



class ContentTagsController extends Controller
{
    public function index()
    {
        

        return new ContentTagResource(ContentTag::with([])->get());
    }

    public function show($id)
    {
        if (Gate::denies('content_tag_view')) {
            return abort(401);
        }

        $content_tag = ContentTag::with([])->findOrFail($id);

        return new ContentTagResource($content_tag);
    }

    public function store(StoreContentTagsRequest $request)
    {
        if (Gate::denies('content_tag_create')) {
            return abort(401);
        }

        $content_tag = ContentTag::create($request->all());
        
        

        return (new ContentTagResource($content_tag))
            ->response()
            ->setStatusCode(201);
    }

    public function update(UpdateContentTagsRequest $request, $id)
    {
        if (Gate::denies('content_tag_edit')) {
            return abort(401);
        }

        $content_tag = ContentTag::findOrFail($id);
        $content_tag->update($request->all());
        
        
        

        return (new ContentTagResource($content_tag))
            ->response()
            ->setStatusCode(202);
    }

    public function destroy($id)
    {
        if (Gate::denies('content_tag_delete')) {
            return abort(401);
        }

        $content_tag = ContentTag::findOrFail($id);
        $content_tag->delete();

        return response(null, 204);
    }
}
