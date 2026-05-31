@extends('layouts.app')

@section('title', trans('quickadmin.content-tags.title') . ' | ' . trans('quickadmin.qa_edit') . ' | ' . $content_tag->id)

@section('content')
    <h3 class="page-title">@lang('quickadmin.content-tags.title')</h3>

    <form action="{{ route('admin.content_tags.update', $content_tag->id) }}" method="POST">@csrf @method('PUT')

    <div class="panel panel-default">
        <div class="panel-heading">
            @lang('quickadmin.qa_edit')
        </div>

        <div class="panel-body">
            <div class="row">
                <div class="col-xs-12 form-group">
                    <label for="title" class="control-label">{{ trans('quickadmin.content-tags.fields.title').'' }}</label>
                    <input type="text" name="title" id="title" value="{{ old('title', $content_tag->title ?? '') }}" class="form-control" placeholder="">
                    <p class="help-block"></p>
                    @if($errors->has('title'))
                        <p class="help-block">
                            {{ $errors->first('title') }}
                        </p>
                    @endif
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 form-group">
                    <label for="slug" class="control-label">{{ trans('quickadmin.content-tags.fields.slug').'' }}</label>
                    <input type="text" name="slug" id="slug" value="{{ old('slug', $content_tag->slug ?? '') }}" class="form-control" placeholder="">
                    <p class="help-block"></p>
                    @if($errors->has('slug'))
                        <p class="help-block">
                            {{ $errors->first('slug') }}
                        </p>
                    @endif
                </div>
            </div>

        </div>
    </div>

    <button type="submit" class="btn btn-danger">{{ trans('quickadmin.qa_update') }}</button>
    </form>
@stop

