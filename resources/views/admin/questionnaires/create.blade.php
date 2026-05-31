@extends('layouts.app')

@section('title', trans('quickadmin.questionnaires.title') . ' | ' . trans('quickadmin.qa_create'))

@section('content')
    <h3 class="page-title">@lang('quickadmin.questionnaires.title')</h3>
    <form action="{{ route('admin.questionnaires.store') }}" method="POST">@csrf

    <div class="panel panel-default">
        <div class="panel-heading">
            @lang('quickadmin.qa_create')
        </div>
        
        <div class="panel-body">
            <div class="row">
                <div class="col-xs-12 form-group">
                    <label for="survey_id" class="control-label">{{ trans('quickadmin.questionnaires.fields.survey').'*' }}</label>
                    <select name="survey_id" id="survey_id" class="form-control select2" required>
                        @foreach($surveys as $key => $label)
                            <option value="{{ $key }}" {{ old('survey_id') == $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
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
                    <label for="name" class="control-label">{{ trans('quickadmin.questionnaires.fields.name').'' }}</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" class="form-control" placeholder="">
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

    <button type="submit" class="btn btn-danger">{{ trans('quickadmin.qa_save') }}</button>
    </form>
@stop

