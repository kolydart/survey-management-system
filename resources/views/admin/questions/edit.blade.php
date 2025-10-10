@extends('layouts.app')

@section('title', trans('quickadmin.questions.title') . ' | ' . trans('quickadmin.qa_edit') . ' | ' . $question->id)

@section('content')
    <h3 class="page-title">@lang('quickadmin.questions.title')</h3>
    
    {!! Form::model($question, ['method' => 'PUT', 'route' => ['admin.questions.update', $question->id]]) !!}

    <div class="panel panel-default">
        <div class="panel-heading">
            @lang('quickadmin.qa_edit')
        </div>

        <div class="panel-body">
            <div class="row">
                <div class="col-xs-12 form-group">
                    {!! Form::label('title', trans('quickadmin.questions.fields.title').'*', ['class' => 'control-label']) !!}
                    {!! Form::text('title', old('title'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) !!}
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
                    {!! Form::label('answerlist_id', trans('quickadmin.questions.fields.answerlist').'*', ['class' => 'control-label']) !!}
                    {!! Form::select('answerlist_id', $answerlists, old('answerlist_id'), ['class' => 'form-control select2', 'required' => '']) !!}
                    <p class="help-block"></p>
                    @if($errors->has('answerlist_id'))
                        <p class="help-block">
                            {{ $errors->first('answerlist_id') }}
                        </p>
                    @endif
                </div>
            </div>
            
        </div>
    </div>

    {!! Form::submit(trans('quickadmin.qa_update'), ['class' => 'btn btn-danger']) !!}
    {!! Form::close() !!}
@stop

