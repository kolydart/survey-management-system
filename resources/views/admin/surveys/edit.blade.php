@extends('layouts.app')

@section('content')
    <script src="https://archive.gateweb.gr/ckeditor/ckeditor.js"></script>

    <h3 class="page-title">@lang('quickadmin.surveys.title')</h3>
    
    {!! Form::model($survey, ['method' => 'PUT', 'route' => ['admin.surveys.update', $survey->id]]) !!}

    <div class="panel panel-default">
        <div class="panel-heading">
            @lang('quickadmin.qa_edit')
        </div>

        <div class="panel-body">
            <div class="row">
            <div class="col-md-6 {{--row--}}">
                <div class="col-xs-12 form-group">
                    {!! Form::label('title', trans('quickadmin.surveys.fields.title').'*', ['class' => 'control-label']) !!}
                    {!! Form::text('title', old('title'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) !!}
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
                    {!! Form::label('alias', trans('quickadmin.surveys.fields.alias').'*', ['class' => 'control-label']) !!}
                    {!! Form::text('alias', old('alias'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) !!}
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
                    {!! Form::label('institution_id', trans('quickadmin.surveys.fields.institution').'', ['class' => 'control-label']) !!}
                    {!! Form::select('institution_id', $institutions, old('institution_id'), ['class' => 'form-control select2']) !!}
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
                    {!! Form::label('category', trans('quickadmin.surveys.fields.category').'', ['class' => 'control-label']) !!}
                    <button type="button" class="btn btn-primary btn-xs" id="selectbtn-category">
                        {{ trans('quickadmin.qa_select_all') }}
                    </button>
                    <button type="button" class="btn btn-primary btn-xs" id="deselectbtn-category">
                        {{ trans('quickadmin.qa_deselect_all') }}
                    </button>
                    {!! Form::select('category[]', $categories, old('category') ? old('category') : $survey->category->pluck('id')->toArray(), ['class' => 'form-control select2', 'multiple' => 'multiple', 'id' => 'selectall-category' ]) !!}
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
                    {!! Form::label('group', trans('quickadmin.surveys.fields.group').'', ['class' => 'control-label']) !!}
                    <button type="button" class="btn btn-primary btn-xs" id="selectbtn-group">
                        {{ trans('quickadmin.qa_select_all') }}
                    </button>
                    <button type="button" class="btn btn-primary btn-xs" id="deselectbtn-group">
                        {{ trans('quickadmin.qa_deselect_all') }}
                    </button>
                    {!! Form::select('group[]', $groups, old('group') ? old('group') : $survey->group->pluck('id')->toArray(), ['class' => 'form-control select2', 'multiple' => 'multiple', 'id' => 'selectall-group' ]) !!}
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
                    {!! Form::label('introduction', trans('quickadmin.surveys.fields.introduction').'', ['class' => 'control-label']) !!}
                    {!! Form::textarea('introduction', old('introduction'), ['class' => 'form-control ', 'placeholder' => '']) !!}
                    <script>CKEDITOR.replace( 'introduction' );</script>                    
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
                    {!! Form::label('notes', trans('quickadmin.surveys.fields.notes').'', ['class' => 'control-label']) !!}
                    {!! Form::textarea('notes', old('notes'), ['class' => 'form-control ', 'placeholder' => '']) !!}
                    <script>CKEDITOR.replace( 'notes' );</script>                    
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
                    {!! Form::label('access', trans('quickadmin.surveys.fields.access').'*', ['class' => 'control-label']) !!}
                    <p class="help-block"></p>
                    @if($errors->has('access'))
                        <p class="help-block">
                            {{ $errors->first('access') }}
                        </p>
                    @endif
                    <div>
                        <label>
                            {!! Form::radio('access', 'public', false, ['required' => '']) !!}
                            public
                        </label>
                    </div>
                    <div>
                        <label>
                            {!! Form::radio('access', 'invited', false, ['required' => '']) !!}
                            invited
                        </label>
                    </div>
                    <div>
                        <label>
                            {!! Form::radio('access', 'registered', false, ['required' => '']) !!}
                            registered
                        </label>
                    </div>
                    
                </div>
            </div>
            <div class="col-md-6 {{--row--}}">
                <div class="col-xs-12 form-group">
                    {!! Form::label('completed', trans('quickadmin.surveys.fields.completed').'', ['class' => 'control-label']) !!}
                    {!! Form::hidden('completed', 0) !!}
                    {!! Form::checkbox('completed', 1, old('completed', old('completed')), []) !!}
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

    {!! Form::submit(trans('quickadmin.qa_update'), ['class' => 'btn btn-danger']) !!}
    {!! Form::close() !!}
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