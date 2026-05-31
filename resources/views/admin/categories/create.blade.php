@extends('layouts.app')

@section('title', trans('quickadmin.categories.title') . ' | ' . trans('quickadmin.qa_create'))

@section('content')
    <h3 class="page-title">@lang('quickadmin.categories.title')</h3>
    <form action="{{ route('admin.categories.store') }}" method="POST">@csrf

    <div class="panel panel-default">
        <div class="panel-heading">
            @lang('quickadmin.qa_create')
        </div>
        
        <div class="panel-body">
            <div class="row">
                <div class="col-xs-12 form-group">
                    <label for="title" class="control-label">{{ trans('quickadmin.categories.fields.title').'*' }}</label>
                    <input type="text" name="title" id="title" value="{{ old('title') }}" class="form-control" placeholder="" required>
                    <p class="help-block"></p>
                    @if($errors->has('title'))
                        <p class="help-block">
                            {{ $errors->first('title') }}
                        </p>
                    @endif
                </div>
            </div>
            
        </div>
    </div>

    <button type="submit" class="btn btn-danger">{{ trans('quickadmin.qa_save') }}</button>
    </form>
@stop

