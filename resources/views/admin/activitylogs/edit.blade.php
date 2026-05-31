@extends('layouts.app')

@section('content')
    <h3 class="page-title">@lang('quickadmin.activitylog.title')</h3>
    
    <form action="{{ route('admin.activitylogs.update', $activitylog->id) }}" method="POST">
        @csrf
        @method('PUT')

    <div class="panel panel-default">
        <div class="panel-heading">
            @lang('quickadmin.qa_edit')
        </div>

        <div class="panel-body">
            <div class="row">
                <div class="col-xs-12 form-group">
                    <label for="log_name" class="control-label">{{ trans('quickadmin.activitylog.fields.log-name').'' }}</label>
                    <input type="text" name="log_name" id="log_name" value="{{ old('log_name', $activitylog->log_name ?? '') }}" class="form-control" placeholder="">
                    <p class="help-block"></p>
                    @if($errors->has('log_name'))
                        <p class="help-block">
                            {{ $errors->first('log_name') }}
                        </p>
                    @endif
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 form-group">
                    <label for="causer_type" class="control-label">{{ trans('quickadmin.activitylog.fields.causer-type').'' }}</label>
                    <input type="text" name="causer_type" id="causer_type" value="{{ old('causer_type', $activitylog->causer_type ?? '') }}" class="form-control" placeholder="">
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
                    <label for="causer_id" class="control-label">{{ trans('quickadmin.activitylog.fields.causer-id').'' }}</label>
                    <input type="number" name="causer_id" id="causer_id" value="{{ old('causer_id', $activitylog->causer_id ?? '') }}" class="form-control" placeholder="">
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
                    <label for="description" class="control-label">{{ trans('quickadmin.activitylog.fields.description').'' }}</label>
                    <input type="text" name="description" id="description" value="{{ old('description', $activitylog->description ?? '') }}" class="form-control" placeholder="">
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
                    <label for="subject_type" class="control-label">{{ trans('quickadmin.activitylog.fields.subject-type').'' }}</label>
                    <input type="text" name="subject_type" id="subject_type" value="{{ old('subject_type', $activitylog->subject_type ?? '') }}" class="form-control" placeholder="">
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
                    <label for="subject_id" class="control-label">{{ trans('quickadmin.activitylog.fields.subject-id').'' }}</label>
                    <input type="number" name="subject_id" id="subject_id" value="{{ old('subject_id', $activitylog->subject_id ?? '') }}" class="form-control" placeholder="">
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
                    <label for="properties" class="control-label">{{ trans('quickadmin.activitylog.fields.properties').'' }}</label>
                    <textarea name="properties" id="properties" class="form-control " placeholder="">{{ old('properties', $activitylog->properties ?? '') }}</textarea>
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

    <button type="submit" class="btn btn-danger">{{ trans('quickadmin.qa_update') }}</button>
    </form>
@stop

