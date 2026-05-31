@extends('layouts.app')

@section('title', trans('quickadmin.answers.title') . ' | ' . trans('quickadmin.qa_edit') . ' | ' . $answer->id)

@section('content')
    <h3 class="page-title">@lang('quickadmin.answers.title')</h3>
    
    <form action="{{ route('admin.answers.update', $answer->id) }}" method="POST">
        @csrf
        @method('PUT')

    <div class="panel panel-default">
        <div class="panel-heading">
            @lang('quickadmin.qa_edit')
        </div>

        <div class="panel-body">
            <div class="row">
                <div class="col-xs-12 form-group">
                    <label for="title" class="control-label">{{ trans('quickadmin.answers.fields.title').'*' }}</label>
                    <input type="text" name="title" id="title" value="{{ old('title', $answer->title ?? '') }}" class="form-control" placeholder="" required>
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
                    <label for="open" class="control-label">{{ trans('quickadmin.answers.fields.open').'' }}</label>
                    <input type="hidden" name="open" value="0">
                    <input type="checkbox" name="open" value="1" {{ old('open', $answer->open ?? '') ? 'checked' : '' }}>
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

    <button type="submit" class="btn btn-danger">{{ trans('quickadmin.qa_update') }}</button>
    </form>
@stop

