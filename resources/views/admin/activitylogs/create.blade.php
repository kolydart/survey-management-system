@extends('layouts.app')

@section('content')
    <h3 class="page-title">@lang('quickadmin.activitylog.title')</h3>
    {!! Form::open(['method' => 'POST', 'route' => ['admin.activitylogs.store']]) !!}

    <div class="panel panel-default">
        <div class="panel-heading">
            @lang('quickadmin.qa_create')
        </div>
        
        <div class="panel-body">
            <div class="row">
                <div class="col-xs-12 form-group">
                    {!! Form::label('causer_type', trans('quickadmin.activitylog.fields.causer-type').'', ['class' => 'control-label']) !!}
                    {!! Form::text('causer_type', old('causer_type'), ['class' => 'form-control', 'placeholder' => '']) !!}
                    <p class="help-block"></p>
                    @if($errors->has('causer_type'))
                        <p class="help-block">
                            {{ $errors->first('causer_type') }}
                        </p>
                    @endif
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 form-group">
                    {!! Form::label('causer_id', trans('quickadmin.activitylog.fields.causer-id').'', ['class' => 'control-label']) !!}
                    {!! Form::number('causer_id', old('causer_id'), ['class' => 'form-control', 'placeholder' => '']) !!}
                    <p class="help-block"></p>
                    @if($errors->has('causer_id'))
                        <p class="help-block">
                            {{ $errors->first('causer_id') }}
                        </p>
                    @endif
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 form-group">
                    {!! Form::label('description', trans('quickadmin.activitylog.fields.description').'', ['class' => 'control-label']) !!}
                    {!! Form::text('description', old('description'), ['class' => 'form-control', 'placeholder' => '']) !!}
                    <p class="help-block"></p>
                    @if($errors->has('description'))
                        <p class="help-block">
                            {{ $errors->first('description') }}
                        </p>
                    @endif
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 form-group">
                    {!! Form::label('subject_type', trans('quickadmin.activitylog.fields.subject-type').'', ['class' => 'control-label']) !!}
                    {!! Form::text('subject_type', old('subject_type'), ['class' => 'form-control', 'placeholder' => '']) !!}
                    <p class="help-block"></p>
                    @if($errors->has('subject_type'))
                        <p class="help-block">
                            {{ $errors->first('subject_type') }}
                        </p>
                    @endif
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 form-group">
                    {!! Form::label('subject_id', trans('quickadmin.activitylog.fields.subject-id').'', ['class' => 'control-label']) !!}
                    {!! Form::number('subject_id', old('subject_id'), ['class' => 'form-control', 'placeholder' => '']) !!}
                    <p class="help-block"></p>
                    @if($errors->has('subject_id'))
                        <p class="help-block">
                            {{ $errors->first('subject_id') }}
                        </p>
                    @endif
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 form-group">
                    {!! Form::label('properties', trans('quickadmin.activitylog.fields.properties').'', ['class' => 'control-label']) !!}
                    {!! Form::textarea('properties', old('properties'), ['class' => 'form-control ', 'placeholder' => '']) !!}
                    <p class="help-block"></p>
                    @if($errors->has('properties'))
                        <p class="help-block">
                            {{ $errors->first('properties') }}
                        </p>
                    @endif
                </div>
            </div>
            
        </div>
    </div>

    {!! Form::submit(trans('quickadmin.qa_save'), ['class' => 'btn btn-danger']) !!}
    {!! Form::close() !!}
@stop

