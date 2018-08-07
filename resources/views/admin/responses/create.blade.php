@extends('layouts.app')

@section('content')
    <h3 class="page-title">@lang('quickadmin.responses.title')</h3>
    {!! Form::open(['method' => 'POST', 'route' => ['admin.responses.store']]) !!}

    <div class="panel panel-default">
        <div class="panel-heading">
            @lang('quickadmin.qa_create')
        </div>
        
        <div class="panel-body">
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

    {!! Form::submit(trans('quickadmin.qa_save'), ['class' => 'btn btn-danger']) !!}
    {!! Form::close() !!}
@stop

