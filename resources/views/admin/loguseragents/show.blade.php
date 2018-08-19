@extends('layouts.app')

@section('content')
    <h3 class="page-title">@lang('quickadmin.loguseragent.title')</h3>

    <div class="panel panel-default">
        <div class="panel-heading">
            @lang('quickadmin.qa_view')
        </div>

        <div class="panel-body table-responsive">
            <div class="row">
                <div class="col-md-6">
                    <table class="table table-bordered table-striped">
                        <tr>
                            <th>@lang('quickadmin.loguseragent.fields.os')</th>
                            <td field-key='os'>{{ $loguseragent->os }}</td>
                        </tr>
                        <tr>
                            <th>@lang('quickadmin.loguseragent.fields.os-version')</th>
                            <td field-key='os_version'>{{ $loguseragent->os_version }}</td>
                        </tr>
                        <tr>
                            <th>@lang('quickadmin.loguseragent.fields.browser')</th>
                            <td field-key='browser'>{{ $loguseragent->browser }}</td>
                        </tr>
                        <tr>
                            <th>@lang('quickadmin.loguseragent.fields.browser-version')</th>
                            <td field-key='browser_version'>{{ $loguseragent->browser_version }}</td>
                        </tr>
                        <tr>
                            <th>@lang('quickadmin.loguseragent.fields.device')</th>
                            <td field-key='device'>{{ $loguseragent->device }}</td>
                        </tr>
                        <tr>
                            <th>@lang('quickadmin.loguseragent.fields.language')</th>
                            <td field-key='language'>{{ $loguseragent->language }}</td>
                        </tr>
                        <tr>
                            <th>@lang('quickadmin.loguseragent.fields.item-id')</th>
                            <td field-key='item_id'>{{ $loguseragent->item_id }}</td>
                        </tr>
                        <tr>
                            <th>@lang('quickadmin.loguseragent.fields.ipv6')</th>
                            <td field-key='ipv6'>{{ gateweb\common\Presenter::convert_hex2ip($loguseragent->ipv6) }}</td>
                        </tr>
                        <tr>
                            <th>@lang('quickadmin.loguseragent.fields.uri')</th>
                            <td field-key='uri'>{{ $loguseragent->uri }}</td>
                        </tr>
                        <tr>
                            <th>@lang('quickadmin.loguseragent.fields.form-submitted')</th>
                            <td field-key='form_submitted'>{{ Form::checkbox("form_submitted", 1, $loguseragent->form_submitted == 1 ? true : false, ["disabled"]) }}</td>
                        </tr>
                        <tr>
                            <th>@lang('quickadmin.loguseragent.fields.user')</th>
                            <td field-key='user'>{{ $loguseragent->user->name or '' }}</td>
                        </tr>
                        {!! gateweb\common\presenter\Laraview::dates_in_show($loguseragent) !!}
                    </table>
                </div>
            </div>

            <p>&nbsp;</p>

            <a href="{{ route('admin.loguseragents.index') }}" class="btn btn-default">@lang('quickadmin.qa_back_to_list')</a>
        </div>
    </div>
@stop


