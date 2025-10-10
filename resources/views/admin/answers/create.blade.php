@extends('layouts.app')

@section('title', trans('quickadmin.answers.title') . ' | ' . trans('quickadmin.qa_create'))

@section('content')
    <h3 class="page-title">@lang('quickadmin.answers.title')</h3>
    {!! Form::open(['method' => 'POST', 'route' => ['admin.answers.store']]) !!}

    <div class="panel panel-default">
        <div class="panel-heading">
            @lang('quickadmin.qa_create')
        </div>
        
        <div class="panel-body">
            <div class="row">
                <div class="col-xs-12 form-group">
                    {!! Form::label('title', trans('quickadmin.answers.fields.title').'*', ['class' => 'control-label']) !!}
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
                    {!! Form::label('open', trans('quickadmin.answers.fields.open').'', ['class' => 'control-label']) !!}
                    {!! Form::hidden('open', 0) !!}
                    {!! Form::checkbox('open', 1, old('open', false), []) !!}
                    <p class="help-block">open ended question?</p>
                    @if($errors->has('open'))
                        <p class="help-block">
                            {{ $errors->first('open') }}
                        </p>
                    @endif
                </div>
            </div>
            
        </div>
    </div>

    {!! Form::submit(trans('quickadmin.qa_save'), ['class' => 'btn btn-danger']) !!}
    {!! Form::close() !!}
@stop

