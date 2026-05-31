@extends('layouts.app')

@section('title', trans('quickadmin.surveys.title') . ' | ' . trans('quickadmin.qa_create'))

@section('content')
    <h3 class="page-title">@lang('quickadmin.surveys.title')</h3>
    <form action="{{ route('admin.surveys.store') }}" method="POST">@csrf

    <div class="panel panel-default">
        <div class="panel-heading">
            @lang('quickadmin.qa_create')
        </div>
        
        <div class="panel-body">
            <div class="row">
            <div class="col-md-6 {{--row--}}">
                <div class="col-xs-12 form-group">
                    <label for="title" class="control-label">{{ trans('quickadmin.surveys.fields.title').'*' }}</label>
                    <input type="text" name="title" id="title" value="{{ old('title') }}" class="form-control" placeholder="" required>
                    <p class="help-block"></p>
                    @if($errors->has('title'))
                        <p class="help-block">
                            {{ $errors->first('title') }}
                        </p>
                    @endif
                </div>
            </div>
            <div class="col-md-6 {{--row--}}">
                <div class="col-xs-12 form-group">
                    <label for="alias" class="control-label">{{ trans('quickadmin.surveys.fields.alias').'*' }}</label>
                    <input type="text" name="alias" id="alias" value="{{ old('alias') }}" class="form-control" placeholder="" required>
                    <p class="help-block"></p>
                    @if($errors->has('alias'))
                        <p class="help-block">
                            {{ $errors->first('alias') }}
                        </p>
                    @endif
                </div>
            </div>
            <div class="col-md-6 {{--row--}}">
                <div class="col-xs-12 form-group">
                    <label for="institution_id" class="control-label">{{ trans('quickadmin.surveys.fields.institution').'' }}</label>
                    <select name="institution_id" id="institution_id" class="form-control select2">
                        @foreach($institutions as $key => $label)
                            <option value="{{ $key }}" {{ old('institution_id') == $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    <p class="help-block"></p>
                    @if($errors->has('institution_id'))
                        <p class="help-block">
                            {{ $errors->first('institution_id') }}
                        </p>
                    @endif
                </div>
            </div>
            <div class="col-md-6 {{--row--}}">
                <div class="col-xs-12 form-group">
                    <label for="category" class="control-label">{{ trans('quickadmin.surveys.fields.category').'' }}</label>
                    <button type="button" class="btn btn-primary btn-xs" id="selectbtn-category">
                        {{ trans('quickadmin.qa_select_all') }}
                    </button>
                    <button type="button" class="btn btn-primary btn-xs" id="deselectbtn-category">
                        {{ trans('quickadmin.qa_deselect_all') }}
                    </button>
                    @php $__selected_category = old('category'); @endphp
                    <select name="category[]" id="selectall-category" class="form-control select2" multiple>
                        @foreach($categories as $key => $label)
                            <option value="{{ $key }}" {{ (is_array($__selected_category) && in_array($key, $__selected_category)) ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    <p class="help-block"></p>
                    @if($errors->has('category'))
                        <p class="help-block">
                            {{ $errors->first('category') }}
                        </p>
                    @endif
                </div>
            </div>
            <div class="col-md-6 {{--row--}}">
                <div class="col-xs-12 form-group">
                    <label for="group" class="control-label">{{ trans('quickadmin.surveys.fields.group').'' }}</label>
                    <button type="button" class="btn btn-primary btn-xs" id="selectbtn-group">
                        {{ trans('quickadmin.qa_select_all') }}
                    </button>
                    <button type="button" class="btn btn-primary btn-xs" id="deselectbtn-group">
                        {{ trans('quickadmin.qa_deselect_all') }}
                    </button>
                    @php $__selected_group = old('group'); @endphp
                    <select name="group[]" id="selectall-group" class="form-control select2" multiple>
                        @foreach($groups as $key => $label)
                            <option value="{{ $key }}" {{ (is_array($__selected_group) && in_array($key, $__selected_group)) ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    <p class="help-block"></p>
                    @if($errors->has('group'))
                        <p class="help-block">
                            {{ $errors->first('group') }}
                        </p>
                    @endif
                </div>
            </div>
            <div class="col-md-10 {{--row--}}">
                <div class="col-xs-12 form-group">
                    <label for="introduction" class="control-label">{{ trans('quickadmin.surveys.fields.introduction').'' }}</label>
                    <textarea name="introduction" id="introduction" class="form-control ckeditor" placeholder="">{{ old('introduction') }}</textarea>
                    <p class="help-block"></p>
                    @if($errors->has('introduction'))
                        <p class="help-block">
                            {{ $errors->first('introduction') }}
                        </p>
                    @endif
                </div>
            </div>
            <div class="col-md-6 {{--row--}}">
                <div class="col-xs-12 form-group">
                    <label for="notes" class="control-label">{{ trans('quickadmin.surveys.fields.notes').'' }}</label>
                    <textarea name="notes" id="notes" class="form-control ckeditor" placeholder="">{{ old('notes') }}</textarea>
                    <p class="help-block"></p>
                    @if($errors->has('notes'))
                        <p class="help-block">
                            {{ $errors->first('notes') }}
                        </p>
                    @endif
                </div>
            </div>
            <div class="col-md-6 {{--row--}}">
                <div class="col-xs-12 form-group">
                    <label for="javascript" class="control-label">{{ trans('quickadmin.surveys.fields.javascript').'' }}</label>
                    <textarea name="javascript" id="javascript" class="form-control " placeholder="">{{ old('javascript') }}</textarea>
                    <p class="help-block"></p>
                    @if($errors->has('javascript'))
                        <p class="help-block">
                            {{ $errors->first('javascript') }}
                        </p>
                    @endif
                </div>
            </div>
            <div class="col-md-6 {{--row--}}">
                <div class="col-xs-12 form-group">
                    <label for="inform" class="control-label">{{ trans('quickadmin.surveys.fields.inform').'' }}</label>
                    <input type="hidden" name="inform" value="0">
                    <input type="checkbox" name="inform" value="1" {{ old('inform', false) ? 'checked' : '' }}>
                    <p class="help-block">send email on new questionnaire</p>
                    @if($errors->has('inform'))
                        <p class="help-block">
                            {{ $errors->first('inform') }}
                        </p>
                    @endif
                </div>
            </div>
            <div class="col-md-6 {{--row--}}">
                <div class="col-xs-12 form-group">
                    <label for="access" class="control-label">{{ trans('quickadmin.surveys.fields.access').'*' }}</label>
                    <p class="help-block"></p>
                    @if($errors->has('access'))
                        <p class="help-block">
                            {{ $errors->first('access') }}
                        </p>
                    @endif
                    <div>
                        <label>
                            <input type="radio" name="access" value="public" {{ old('access', 'public') == 'public' ? 'checked' : '' }} required>
                            public
                        </label>
                    </div>
                    <div>
                        <label>
                            <input type="radio" name="access" value="invited" {{ old('access') == 'invited' ? 'checked' : '' }} required>
                            invited
                        </label>
                    </div>
                    <div>
                        <label>
                            <input type="radio" name="access" value="registered" {{ old('access') == 'registered' ? 'checked' : '' }} required>
                            registered
                        </label>
                    </div>
                    
                </div>
            </div>
            <div class="col-md-6 {{--row--}}">
                <div class="col-xs-12 form-group">
                    <label for="completed" class="control-label">{{ trans('quickadmin.surveys.fields.completed').'' }}</label>
                    <input type="hidden" name="completed" value="0">
                    <input type="checkbox" name="completed" value="1" {{ old('completed', false) ? 'checked' : '' }}>
                    <p class="help-block"></p>
                    @if($errors->has('completed'))
                        <p class="help-block">
                            {{ $errors->first('completed') }}
                        </p>
                    @endif
                </div>
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
        $("#selectbtn-category").click(function(){
            $("#selectall-category > option").prop("selected","selected");
            $("#selectall-category").trigger("change");
        });
        $("#deselectbtn-category").click(function(){
            $("#selectall-category > option").prop("selected","");
            $("#selectall-category").trigger("change");
        });
    </script>

    <script>
        $("#selectbtn-group").click(function(){
            $("#selectall-group > option").prop("selected","selected");
            $("#selectall-group").trigger("change");
        });
        $("#deselectbtn-group").click(function(){
            $("#selectall-group > option").prop("selected","");
            $("#selectall-group").trigger("change");
        });
    </script>
@stop