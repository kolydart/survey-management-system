@extends('layouts.app')

@section('title', trans('quickadmin.questionnaires.title') . ' | ' . trans('quickadmin.qa_edit') . ' | ' . $questionnaire->id)

@section('content')
    <h3 class="page-title">@lang('quickadmin.questionnaires.title')</h3>
    
    {!! Form::model($questionnaire, ['method' => 'PUT', 'route' => ['admin.questionnaires.update', $questionnaire->id]]) !!}

    <div class="panel panel-default">
        <div class="panel-heading">
            @lang('quickadmin.qa_edit')
        </div>

        <div class="panel-body">
            <div class="row">
                <div class="col-xs-12 form-group">
                    {!! Form::label('survey_id', trans('quickadmin.questionnaires.fields.survey').'*', ['class' => 'control-label']) !!}
                    {!! Form::select('survey_id', $surveys, old('survey_id'), ['class' => 'form-control select2', 'required' => '']) !!}
                    <p class="help-block"></p>
                    @if($errors->has('survey_id'))
                        <p class="help-block">
                            {{ $errors->first('survey_id') }}
                        </p>
                    @endif
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 form-group">
                    {!! Form::label('name', trans('quickadmin.questionnaires.fields.name').'', ['class' => 'control-label']) !!}
                    {!! Form::text('name', old('name'), ['class' => 'form-control', 'placeholder' => '']) !!}
                    <p class="help-block"></p>
                    @if($errors->has('name'))
                        <p class="help-block">
                            {{ $errors->first('name') }}
                        </p>
                    @endif
                </div>
            </div>
            
        </div>
    </div>

    {!! Form::submit(trans('quickadmin.qa_update'), ['class' => 'btn btn-danger']) !!}
    {!! Form::close() !!}
@stop

