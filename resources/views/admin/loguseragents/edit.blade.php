@extends('layouts.app')

@section('content')
    <h3 class="page-title">@lang('quickadmin.loguseragent.title')</h3>
    
    {!! Form::model($loguseragent, ['method' => 'PUT', 'route' => ['admin.loguseragents.update', $loguseragent->id]]) !!}

    <div class="panel panel-default">
        <div class="panel-heading">
            @lang('quickadmin.qa_edit')
        </div>

        <div class="panel-body">
            <div class="row">
                <div class="col-xs-12 form-group">
                    {!! Form::label('os', trans('quickadmin.loguseragent.fields.os').'', ['class' => 'control-label']) !!}
                    {!! Form::text('os', old('os'), ['class' => 'form-control', 'placeholder' => '']) !!}
                    <p class="help-block"></p>
                    @if($errors->has('os'))
                        <p class="help-block">
                            {{ $errors->first('os') }}
                        </p>
                    @endif
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 form-group">
                    {!! Form::label('os_version', trans('quickadmin.loguseragent.fields.os-version').'', ['class' => 'control-label']) !!}
                    {!! Form::text('os_version', old('os_version'), ['class' => 'form-control', 'placeholder' => '']) !!}
                    <p class="help-block"></p>
                    @if($errors->has('os_version'))
                        <p class="help-block">
                            {{ $errors->first('os_version') }}
                        </p>
                    @endif
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 form-group">
                    {!! Form::label('browser', trans('quickadmin.loguseragent.fields.browser').'', ['class' => 'control-label']) !!}
                    {!! Form::text('browser', old('browser'), ['class' => 'form-control', 'placeholder' => '']) !!}
                    <p class="help-block"></p>
                    @if($errors->has('browser'))
                        <p class="help-block">
                            {{ $errors->first('browser') }}
                        </p>
                    @endif
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 form-group">
                    {!! Form::label('browser_version', trans('quickadmin.loguseragent.fields.browser-version').'', ['class' => 'control-label']) !!}
                    {!! Form::text('browser_version', old('browser_version'), ['class' => 'form-control', 'placeholder' => '']) !!}
                    <p class="help-block"></p>
                    @if($errors->has('browser_version'))
                        <p class="help-block">
                            {{ $errors->first('browser_version') }}
                        </p>
                    @endif
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 form-group">
                    {!! Form::label('device', trans('quickadmin.loguseragent.fields.device').'', ['class' => 'control-label']) !!}
                    {!! Form::text('device', old('device'), ['class' => 'form-control', 'placeholder' => '']) !!}
                    <p class="help-block"></p>
                    @if($errors->has('device'))
                        <p class="help-block">
                            {{ $errors->first('device') }}
                        </p>
                    @endif
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 form-group">
                    {!! Form::label('language', trans('quickadmin.loguseragent.fields.language').'', ['class' => 'control-label']) !!}
                    {!! Form::text('language', old('language'), ['class' => 'form-control', 'placeholder' => '']) !!}
                    <p class="help-block"></p>
                    @if($errors->has('language'))
                        <p class="help-block">
                            {{ $errors->first('language') }}
                        </p>
                    @endif
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 form-group">
                    {!! Form::label('item_id', trans('quickadmin.loguseragent.fields.item-id').'', ['class' => 'control-label']) !!}
                    {!! Form::number('item_id', old('item_id'), ['class' => 'form-control', 'placeholder' => '']) !!}
                    <p class="help-block"></p>
                    @if($errors->has('item_id'))
                        <p class="help-block">
                            {{ $errors->first('item_id') }}
                        </p>
                    @endif
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 form-group">
                    {!! Form::label('user_id', trans('quickadmin.loguseragent.fields.user').'', ['class' => 'control-label']) !!}
                    {!! Form::select('user_id', $users, old('user_id'), ['class' => 'form-control select2']) !!}
                    <p class="help-block"></p>
                    @if($errors->has('user_id'))
                        <p class="help-block">
                            {{ $errors->first('user_id') }}
                        </p>
                    @endif
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 form-group">
                    {!! Form::label('ipv6', trans('quickadmin.loguseragent.fields.ipv6').'', ['class' => 'control-label']) !!}
                    {!! Form::text('ipv6', old('ipv6'), ['class' => 'form-control', 'placeholder' => '']) !!}
                    <p class="help-block"></p>
                    @if($errors->has('ipv6'))
                        <p class="help-block">
                            {{ $errors->first('ipv6') }}
                        </p>
                    @endif
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 form-group">
                    {!! Form::label('uri', trans('quickadmin.loguseragent.fields.uri').'', ['class' => 'control-label']) !!}
                    {!! Form::text('uri', old('uri'), ['class' => 'form-control', 'placeholder' => '']) !!}
                    <p class="help-block"></p>
                    @if($errors->has('uri'))
                        <p class="help-block">
                            {{ $errors->first('uri') }}
                        </p>
                    @endif
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 form-group">
                    {!! Form::label('form_submitted', trans('quickadmin.loguseragent.fields.form-submitted').'', ['class' => 'control-label']) !!}
                    {!! Form::hidden('form_submitted', 0) !!}
                    {!! Form::checkbox('form_submitted', 1, old('form_submitted', old('form_submitted')), []) !!}
                    <p class="help-block"></p>
                    @if($errors->has('form_submitted'))
                        <p class="help-block">
                            {{ $errors->first('form_submitted') }}
                        </p>
                    @endif
                </div>
            </div>
            
        </div>
    </div>

    {!! Form::submit(trans('quickadmin.qa_update'), ['class' => 'btn btn-danger']) !!}
    {!! Form::close() !!}
@stop

