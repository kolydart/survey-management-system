@extends('layouts.app')

@section('title', trans('quickadmin.content-pages.title') . ' | ' . trans('quickadmin.qa_create'))

@section('content')
    <h3 class="page-title">@lang('quickadmin.content-pages.title')</h3>
    <form action="{{ route('admin.content_pages.store') }}" method="POST" enctype="multipart/form-data">@csrf

    <div class="panel panel-default">
        <div class="panel-heading">
            @lang('quickadmin.qa_create')
        </div>
        
        <div class="panel-body">
            <div class="row">
                <div class="col-xs-12 form-group">
                    <label for="title" class="control-label">{{ trans('quickadmin.content-pages.fields.title').'*' }}</label>
                    <input type="text" name="title" id="title" value="{{ old('title') }}" class="form-control" placeholder="" required>
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
                    <label for="category_id" class="control-label">{{ trans('quickadmin.content-pages.fields.category-id').'' }}</label>
                    <button type="button" class="btn btn-primary btn-xs" id="selectbtn-category_id">
                        {{ trans('quickadmin.qa_select_all') }}
                    </button>
                    <button type="button" class="btn btn-primary btn-xs" id="deselectbtn-category_id">
                        {{ trans('quickadmin.qa_deselect_all') }}
                    </button>
                    <select name="category_id[]" id="selectall-category_id" class="form-control select2" multiple>
                        @foreach($category_ids as $key => $label)
                            <option value="{{ $key }}" {{ (is_array(old('category_id')) && in_array($key, old('category_id'))) ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    <p class="help-block"></p>
                    @if($errors->has('category_id'))
                        <p class="help-block">
                            {{ $errors->first('category_id') }}
                        </p>
                    @endif
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 form-group">
                    <label for="tag_id" class="control-label">{{ trans('quickadmin.content-pages.fields.tag-id').'' }}</label>
                    <button type="button" class="btn btn-primary btn-xs" id="selectbtn-tag_id">
                        {{ trans('quickadmin.qa_select_all') }}
                    </button>
                    <button type="button" class="btn btn-primary btn-xs" id="deselectbtn-tag_id">
                        {{ trans('quickadmin.qa_deselect_all') }}
                    </button>
                    <select name="tag_id[]" id="selectall-tag_id" class="form-control select2" multiple>
                        @foreach($tag_ids as $key => $label)
                            <option value="{{ $key }}" {{ (is_array(old('tag_id')) && in_array($key, old('tag_id'))) ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    <p class="help-block"></p>
                    @if($errors->has('tag_id'))
                        <p class="help-block">
                            {{ $errors->first('tag_id') }}
                        </p>
                    @endif
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 form-group">
                    <label for="page_text" class="control-label">{{ trans('quickadmin.content-pages.fields.page-text').'' }}</label>
                    <textarea name="page_text" id="page_text" class="form-control ckeditor" placeholder="">{{ old('page_text') }}</textarea>
                    <p class="help-block"></p>
                    @if($errors->has('page_text'))
                        <p class="help-block">
                            {{ $errors->first('page_text') }}
                        </p>
                    @endif
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 form-group">
                    <label for="excerpt" class="control-label">{{ trans('quickadmin.content-pages.fields.excerpt').'' }}</label>
                    <textarea name="excerpt" id="excerpt" class="form-control " placeholder="">{{ old('excerpt') }}</textarea>
                    <p class="help-block"></p>
                    @if($errors->has('excerpt'))
                        <p class="help-block">
                            {{ $errors->first('excerpt') }}
                        </p>
                    @endif
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 form-group">
                    <label for="featured_image" class="control-label">{{ trans('quickadmin.content-pages.fields.featured-image').'' }}</label>
                    <input type="file" name="featured_image" id="featured_image" class="form-control" style="margin-top: 4px;">
                    <input type="hidden" name="featured_image_max_size" value="10">
                    <input type="hidden" name="featured_image_max_width" value="1000">
                    <input type="hidden" name="featured_image_max_height" value="1000">
                    <p class="help-block"></p>
                    @if($errors->has('featured_image'))
                        <p class="help-block">
                            {{ $errors->first('featured_image') }}
                        </p>
                    @endif
                </div>
            </div>
            
        </div>
    </div>

    <button type="submit" class="btn btn-danger">{{ trans('quickadmin.qa_save') }}</button>
    </form>
@stop

@section('javascript')
    @parent

    <script>
        $("#selectbtn-category_id").click(function(){
            $("#selectall-category_id > option").prop("selected","selected");
            $("#selectall-category_id").trigger("change");
        });
        $("#deselectbtn-category_id").click(function(){
            $("#selectall-category_id > option").prop("selected","");
            $("#selectall-category_id").trigger("change");
        });
    </script>

    <script>
        $("#selectbtn-tag_id").click(function(){
            $("#selectall-tag_id > option").prop("selected","selected");
            $("#selectall-tag_id").trigger("change");
        });
        $("#deselectbtn-tag_id").click(function(){
            $("#selectall-tag_id > option").prop("selected","");
            $("#selectall-tag_id").trigger("change");
        });
    </script>
@stop