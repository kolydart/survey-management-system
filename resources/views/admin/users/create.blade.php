@extends('layouts.app')

@section('title', trans('quickadmin.users.title') . ' | ' . trans('quickadmin.qa_create'))

@section('content')
    <h3 class="page-title">@lang('quickadmin.users.title')</h3>
    <form action="{{ route('admin.users.store') }}" method="POST">@csrf

    <div class="panel panel-default">
        <div class="panel-heading">
            @lang('quickadmin.qa_create')
        </div>
        
        <div class="panel-body">
            <div class="row">
                <div class="col-xs-12 form-group">
                    <label for="name" class="control-label">{{ trans('quickadmin.users.fields.name').'*' }}</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" class="form-control" placeholder="" required>
                    <p class="help-block"></p>
                    @if($errors->has('name'))
                        <p class="help-block">
                            {{ $errors->first('name') }}
                        </p>
                    @endif
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 form-group">
                    <label for="email" class="control-label">{{ trans('quickadmin.users.fields.email').'*' }}</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" class="form-control" placeholder="" required>
                    <p class="help-block"></p>
                    @if($errors->has('email'))
                        <p class="help-block">
                            {{ $errors->first('email') }}
                        </p>
                    @endif
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 form-group">
                    <label for="password" class="control-label">{{ trans('quickadmin.users.fields.password').'*' }}</label>
                    <input type="password" name="password" id="password" class="form-control" placeholder="" required>
                    <p class="help-block"></p>
                    @if($errors->has('password'))
                        <p class="help-block">
                            {{ $errors->first('password') }}
                        </p>
                    @endif
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 form-group">
                    <label for="role_id" class="control-label">{{ trans('quickadmin.users.fields.role').'*' }}</label>
                    <select name="role_id" id="role_id" class="form-control select2" required>
                        @foreach($roles as $key => $label)
                            <option value="{{ $key }}" {{ old('role_id') == $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    <p class="help-block"></p>
                    @if($errors->has('role_id'))
                        <p class="help-block">
                            {{ $errors->first('role_id') }}
                        </p>
                    @endif
                </div>
            </div>
            
        </div>
    </div>

    <button type="submit" class="btn btn-danger">{{ trans('quickadmin.qa_save') }}</button>
    </form>
@stop

