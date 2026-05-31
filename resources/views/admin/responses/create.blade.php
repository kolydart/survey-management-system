@extends('layouts.app')

@section('title', trans('quickadmin.responses.title') . ' | ' . trans('quickadmin.qa_create'))

@section('content')
    <h3 class="page-title">@lang('quickadmin.responses.title')</h3>
    <form action="{{ route('admin.responses.store') }}" method="POST">@csrf

    <div class="panel panel-default">
        <div class="panel-heading">
            @lang('quickadmin.qa_create')
        </div>

        <div class="panel-body">
            <div class="row">
                <div class="col-xs-12 form-group">
                    <label for="questionnaire_id" class="control-label">{{ trans('quickadmin.responses.fields.questionnaire').'*' }}</label>
                    <select name="questionnaire_id" id="questionnaire_id" class="form-control select2" required>
                        @foreach($questionnaires as $key => $label)
                            <option value="{{ $key }}" {{ old('questionnaire_id') == $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
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
                    <label for="question_id" class="control-label">{{ trans('quickadmin.responses.fields.question').'*' }}</label>
                    <select name="question_id" id="question_id" class="form-control select2" required>
                        @foreach($questions as $key => $label)
                            <option value="{{ $key }}" {{ old('question_id') == $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
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
                    <label for="answer_id" class="control-label">{{ trans('quickadmin.responses.fields.answer').'*' }}</label>
                    <select name="answer_id" id="answer_id" class="form-control select2" required>
                        @foreach($answers as $key => $label)
                            <option value="{{ $key }}" {{ old('answer_id') == $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
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
                    <label for="content" class="control-label">{{ trans('quickadmin.responses.fields.content').'' }}</label>
                    <textarea name="content" id="content" class="form-control " placeholder="">{{ old('content') }}</textarea>
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

    <button type="submit" class="btn btn-danger">{{ trans('quickadmin.qa_save') }}</button>
    </form>
@stop

