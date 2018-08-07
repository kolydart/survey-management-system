@extends('layouts.app')

@section('content')
    <h3 class="page-title">@lang('quickadmin.answerlists.title')</h3>
    
    {!! Form::model($answerlist, ['method' => 'PUT', 'route' => ['admin.answerlists.update', $answerlist->id]]) !!}

    <div class="panel panel-default">
        <div class="panel-heading">
            @lang('quickadmin.qa_edit')
        </div>

        <div class="panel-body">
            <div class="row">
                <div class="col-xs-12 form-group">
                    {!! Form::label('title', trans('quickadmin.answerlists.fields.title').'*', ['class' => 'control-label']) !!}
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
                    {!! Form::label('type', trans('quickadmin.answerlists.fields.type').'*', ['class' => 'control-label']) !!}
                    <p class="help-block"></p>
                    @if($errors->has('type'))
                        <p class="help-block">
                            {{ $errors->first('type') }}
                        </p>
                    @endif
                    <div>
                        <label>
                            {!! Form::radio('type', 'Radio', false, ['required' => '']) !!}
                            Radio
                        </label>
                    </div>
                    <div>
                        <label>
                            {!! Form::radio('type', 'Radio + Text', false, ['required' => '']) !!}
                            Radio + Text
                        </label>
                    </div>
                    <div>
                        <label>
                            {!! Form::radio('type', 'Checkbox', false, ['required' => '']) !!}
                            Checkbox
                        </label>
                    </div>
                    <div>
                        <label>
                            {!! Form::radio('type', 'Checkbox + Text', false, ['required' => '']) !!}
                            Checkbox + Text
                        </label>
                    </div>
                    <div>
                        <label>
                            {!! Form::radio('type', 'Text', false, ['required' => '']) !!}
                            Text
                        </label>
                    </div>
                    
                </div>
            </div>
            
        </div>
    </div>

    {!! Form::submit(trans('quickadmin.qa_update'), ['class' => 'btn btn-danger']) !!}
    {!! Form::close() !!}
@stop

