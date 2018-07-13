<?php

namespace App\Http\Controllers\Api\V1;

use App\ContentPage;
use App\Http\Controllers\Controller;
use App\Http\Resources\ContentPage as ContentPageResource;
use App\Http\Requests\Admin\StoreContentPagesRequest;
use App\Http\Requests\Admin\UpdateContentPagesRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

use App\Http\Controllers\Traits\FileUploadTrait;


class ContentPagesController extends Controller
{
    public function index()
    {
        

        return new ContentPageResource(ContentPage::with(['category_id', 'tag_id'])->get());
    }

    public function show($id)
    {
        if (Gate::denies('content_page_view')) {
            return abort(401);
        }

        $content_page = ContentPage::with(['category_id', 'tag_id'])->findOrFail($id);

        return new ContentPageResource($content_page);
    }

    public function store(StoreContentPagesRequest $request)
    {
        if (Gate::denies('content_page_create')) {
            return abort(401);
        }

        $content_page = ContentPage::create($request->all());
        $content_page->category_id()->sync($request->input('category_id', []));
        $content_page->tag_id()->sync($request->input('tag_id', []));
        if ($request->hasFile('featured_image')) {
            $content_page->addMedia($request->file('featured_image'))->toMediaCollection('featured_image');
        }

        return (new ContentPageResource($content_page))
            ->response()
            ->setStatusCode(201);
    }

    public function update(UpdateContentPagesRequest $request, $id)
    {
        if (Gate::denies('content_page_edit')) {
            return abort(401);
        }

        $content_page = ContentPage::findOrFail($id);
        $content_page->update($request->all());
        $content_page->category_id()->sync($request->input('category_id', []));
        $content_page->tag_id()->sync($request->input('tag_id', []));
        if (! $request->input('featured_image') && $content_page->getFirstMedia('featured_image')) {
            $content_page->getFirstMedia('featured_image')->delete();
        }
        if ($request->hasFile('featured_image')) {
            $content_page->addMedia($request->file('featured_image'))->toMediaCollection('featured_image');
        }

        return (new ContentPageResource($content_page))
            ->response()
            ->setStatusCode(202);
    }

    public function destroy($id)
    {
        if (Gate::denies('content_page_delete')) {
            return abort(401);
        }

        $content_page = ContentPage::findOrFail($id);
        $content_page->delete();

        return response(null, 204);
    }
}
