@extends('layouts.app')

@section('title', trans('quickadmin.questions.title') . ' | ' . trans('quickadmin.qa_create'))

@section('content')
    <h3 class="page-title">@lang('quickadmin.questions.title')</h3>
    <form action="{{ route('admin.questions.store') }}" method="POST">@csrf

    <div class="panel panel-default">
        <div class="panel-heading">
            @lang('quickadmin.qa_create')
        </div>
        
        <div class="panel-body">
            <div class="row">
                <div class="col-xs-12 form-group">
                    <label for="title" class="control-label">{{ trans('quickadmin.questions.fields.title').'*' }}</label>
                    <input type="text" name="title" id="title" value="{{ old('title') }}" class="form-control" placeholder="" required>
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
                    <label for="answerlist_id" class="control-label">{{ trans('quickadmin.questions.fields.answerlist').'*' }}</label>
                    <select name="answerlist_id" id="answerlist_id" class="form-control select2" required>
                        @foreach($answerlists as $key => $label)
                            <option value="{{ $key }}" {{ old('answerlist_id') == $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
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

    <button type="submit" class="btn btn-danger">{{ trans('quickadmin.qa_save') }}</button>
    </form>
@stop

