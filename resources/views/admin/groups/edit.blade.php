@extends('layouts.app')

@section('title', trans('quickadmin.groups.title') . ' | ' . trans('quickadmin.qa_edit') . ' | ' . $group->id)

@section('content')
    <h3 class="page-title">@lang('quickadmin.groups.title')</h3>
    
    <form action="{{ route('admin.groups.update', $group->id) }}" method="POST">@csrf @method('PUT')

    <div class="panel panel-default">
        <div class="panel-heading">
            @lang('quickadmin.qa_edit')
        </div>

        <div class="panel-body">
            <div class="row">
                <div class="col-xs-12 form-group">
                    <label for="title" class="control-label">{{ trans('quickadmin.groups.fields.title').'*' }}</label>
                    <input type="text" name="title" id="title" value="{{ old('title', $group->title ?? '') }}" class="form-control" placeholder="" required>
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

    <button type="submit" class="btn btn-danger">{{ trans('quickadmin.qa_update') }}</button>
    </form>
@stop

