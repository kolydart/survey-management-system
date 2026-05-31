@extends('layouts.app')

@section('title', trans('quickadmin.loguseragent.title') . ' | ' . trans('quickadmin.qa_edit') . ' | ' . $loguseragent->id)

@section('content')
    <h3 class="page-title">@lang('quickadmin.loguseragent.title')</h3>

    <form action="{{ route('admin.loguseragents.update', $loguseragent->id) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="panel panel-default">
        <div class="panel-heading">
            @lang('quickadmin.qa_edit')
        </div>

        <div class="panel-body">
            <div class="row">
                <div class="col-xs-12 form-group">
                    <label for="os" class="control-label">{{ trans('quickadmin.loguseragent.fields.os').'' }}</label>
                    <input type="text" name="os" id="os" value="{{ old('os', $loguseragent->os ?? '') }}" class="form-control" placeholder="">
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
                    <label for="os_version" class="control-label">{{ trans('quickadmin.loguseragent.fields.os-version').'' }}</label>
                    <input type="text" name="os_version" id="os_version" value="{{ old('os_version', $loguseragent->os_version ?? '') }}" class="form-control" placeholder="">
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
                    <label for="browser" class="control-label">{{ trans('quickadmin.loguseragent.fields.browser').'' }}</label>
                    <input type="text" name="browser" id="browser" value="{{ old('browser', $loguseragent->browser ?? '') }}" class="form-control" placeholder="">
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
                    <label for="browser_version" class="control-label">{{ trans('quickadmin.loguseragent.fields.browser-version').'' }}</label>
                    <input type="text" name="browser_version" id="browser_version" value="{{ old('browser_version', $loguseragent->browser_version ?? '') }}" class="form-control" placeholder="">
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
                    <label for="device" class="control-label">{{ trans('quickadmin.loguseragent.fields.device').'' }}</label>
                    <input type="text" name="device" id="device" value="{{ old('device', $loguseragent->device ?? '') }}" class="form-control" placeholder="">
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
                    <label for="language" class="control-label">{{ trans('quickadmin.loguseragent.fields.language').'' }}</label>
                    <input type="text" name="language" id="language" value="{{ old('language', $loguseragent->language ?? '') }}" class="form-control" placeholder="">
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
                    <label for="item_id" class="control-label">{{ trans('quickadmin.loguseragent.fields.item-id').'' }}</label>
                    <input type="number" name="item_id" id="item_id" value="{{ old('item_id', $loguseragent->item_id ?? '') }}" class="form-control" placeholder="">
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
                    <label for="ipv6" class="control-label">{{ trans('quickadmin.loguseragent.fields.ipv6').'' }}</label>
                    <input type="text" name="ipv6" id="ipv6" value="{{ old('ipv6', $loguseragent->ipv6 ?? '') }}" class="form-control" placeholder="">
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
                    <label for="uri" class="control-label">{{ trans('quickadmin.loguseragent.fields.uri').'' }}</label>
                    <input type="text" name="uri" id="uri" value="{{ old('uri', $loguseragent->uri ?? '') }}" class="form-control" placeholder="">
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
                    <label for="form_submitted" class="control-label">{{ trans('quickadmin.loguseragent.fields.form-submitted').'' }}</label>
                    <input type="hidden" name="form_submitted" value="0">
                    <input type="checkbox" name="form_submitted" value="1" {{ old('form_submitted', $loguseragent->form_submitted ?? '') ? 'checked' : '' }}>
                    <p class="help-block"></p>
                    @if($errors->has('form_submitted'))
                        <p class="help-block">
                            {{ $errors->first('form_submitted') }}
                        </p>
                    @endif
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 form-group">
                    <label for="user_id" class="control-label">{{ trans('quickadmin.loguseragent.fields.user').'' }}</label>
                    <select name="user_id" id="user_id" class="form-control select2">
                        @foreach($users as $key => $label)
                            <option value="{{ $key }}" {{ old('user_id', $loguseragent->user_id ?? '') == $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    <p class="help-block"></p>
                    @if($errors->has('user_id'))
                        <p class="help-block">
                            {{ $errors->first('user_id') }}
                        </p>
                    @endif
                </div>
            </div>

        </div>
    </div>

    <button type="submit" class="btn btn-danger">{{ trans('quickadmin.qa_update') }}</button>
    </form>
@stop

