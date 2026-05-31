@extends('layouts.app')

@section('title', trans('quickadmin.items.title') . ' | ' . trans('quickadmin.qa_edit') . ' | ' . $item->id)

@section('content')
    <h3 class="page-title">@lang('quickadmin.items.title')</h3>
    
    <form action="{{ route('admin.items.update', $item->id) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="panel panel-default">
        <div class="panel-heading">
            @lang('quickadmin.qa_edit')
        </div>

        <div class="panel-body">
            <div class="row" id="gw_fld_survey">
                <div class="col-xs-12 form-group">
                    <label for="survey_id" class="control-label">{{ trans('quickadmin.items.fields.survey').'*' }}</label>
                    <select name="survey_id" id="survey_id" class="form-control select2" required>
                        @foreach($surveys as $key => $label)
                            <option value="{{ $key }}" {{ old('survey_id', $item->survey_id ?? '') == $key ? 'selected' : '' }}>{{ $label }}</option>
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
            <div class="row" id="gw_fld_question">
                <div class="col-xs-12 form-group">
                    <label for="question_id" class="control-label">{{ trans('quickadmin.items.fields.question') }}</label>
                    <select name="question_id" id="question_id" class="form-control select2">
                        @foreach($questions as $key => $label)
                            <option value="{{ $key }}" {{ old('question_id', $item->question_id ?? '') == $key ? 'selected' : '' }}>{{ $label }}</option>
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
            <div class="row" id="gw_fld_label">
                <div class="col-xs-12 form-group">
                    <label for="label" class="control-label">{{ trans('quickadmin.items.fields.label').'' }}</label>
                    <input type="hidden" name="label" value="0">
                    <input type="checkbox" name="label" value="1" {{ old('label', $item->label ?? '') ? 'checked' : '' }}>
                    <p class="help-block">Category label. Do not show content.</p>
                    @if($errors->has('label'))
                        <p class="help-block">
                            {{ $errors->first('label') }}
                        </p>
                    @endif
                </div>
            </div>
            <div class="row" id="gw_fld_order">
                <div class="col-xs-12 form-group">
                    <label for="order" class="control-label">{{ trans('quickadmin.items.fields.order').'' }}</label>
                    <input type="text" name="order" id="order" value="{{ old('order', $item->order ?? '') }}" class="form-control" placeholder="">
                    <p class="help-block"></p>
                    @if($errors->has('order'))
                        <p class="help-block">
                            {{ $errors->first('order') }}
                        </p>
                    @endif
                </div>
            </div>
            
        </div>
    </div>

    <button type="submit" class="btn btn-danger">{{ trans('quickadmin.qa_update') }}</button>
    </form>
@stop


@section('javascript')
    <script src="/js/item_question.js"></script>
@stop
