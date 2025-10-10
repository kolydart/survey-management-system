@extends('layouts.app')

@section('title', trans('quickadmin.responses.title') . ' | ' . trans('quickadmin.qa_edit') . ' | ' . $response->id)

@section('content')
    <h3 class="page-title">@lang('quickadmin.responses.title')</h3>
    
    {!! Form::model($response, ['method' => 'PUT', 'route' => ['admin.responses.update', $response->id]]) !!}

    <div class="panel panel-default">
        <div class="panel-heading">
            @lang('quickadmin.qa_edit')
        </div>

        <div class="panel-body">
            <div class="row">
                <div class="col-xs-12 form-group">
                    {!! Form::label('questionnaire_id', trans('quickadmin.responses.fields.questionnaire').'*', ['class' => 'control-label']) !!}
                    {!! Form::select('questionnaire_id', $questionnaires, old('questionnaire_id'), ['class' => 'form-control select2', 'required' => '']) !!}
                    <p class="help-block"></p>
                    @if($errors->has('questionnaire_id'))
                        <p class="help-block">
                            {{ $errors->first('questionnaire_id') }}
                        </p>
                    @endif
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 form-group">
                    {!! Form::label('question_id', trans('quickadmin.responses.fields.question').'*', ['class' => 'control-label']) !!}
                    {!! Form::select('question_id', $questions, old('question_id'), ['class' => 'form-control select2', 'required' => '']) !!}
                    <p class="help-block"></p>
                    @if($errors->has('question_id'))
                        <p class="help-block">
                            {{ $errors->first('question_id') }}
                        </p>
                    @endif
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 form-group">
                    {!! Form::label('answer_id', trans('quickadmin.responses.fields.answer').'*', ['class' => 'control-label']) !!}
                    {!! Form::select('answer_id', $answers, old('answer_id'), ['class' => 'form-control select2', 'required' => '']) !!}
                    <p class="help-block"></p>
                    @if($errors->has('answer_id'))
                        <p class="help-block">
                            {{ $errors->first('answer_id') }}
                        </p>
                    @endif
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 form-group">
                    {!! Form::label('content', trans('quickadmin.responses.fields.content').'', ['class' => 'control-label']) !!}
                    {!! Form::textarea('content', old('content'), ['class' => 'form-control ', 'placeholder' => '']) !!}
                    <p class="help-block"></p>
                    @if($errors->has('content'))
                        <p class="help-block">
                            {{ $errors->first('content') }}
                        </p>
                    @endif
                </div>
            </div>
            
        </div>
    </div>

    {!! Form::submit(trans('quickadmin.qa_update'), ['class' => 'btn btn-danger']) !!}
    {!! Form::close() !!}
@stop

