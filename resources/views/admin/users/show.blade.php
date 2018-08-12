@extends('layouts.app')

@section('content')
    <h3 class="page-title">@lang('quickadmin.users.title')</h3>

    <div class="panel panel-default">
        <div class="panel-heading">
            @lang('quickadmin.qa_view')
        </div>

        <div class="panel-body table-responsive">
            <div class="row">
                <div class="col-md-6">
                    <table class="table table-bordered table-striped">
                        <tr>
                            <th>@lang('quickadmin.users.fields.name')</th>
                            <td field-key='name'>{{ $user->name }}</td>
                        </tr>
                        <tr>
                            <th>@lang('quickadmin.users.fields.email')</th>
                            <td field-key='email'>{{ $user->email }}</td>
                        </tr>
                        <tr>
                            <th>@lang('quickadmin.users.fields.role')</th>
                            <td field-key='role'>{{ $user->role->title or '' }}</td>
                        </tr>
                    </table>
                </div>
            </div><!-- Nav tabs -->
<ul class="nav nav-tabs" role="tablist">
    
<li role="presentation" class="active"><a href="#loguseragent" aria-controls="loguseragent" role="tab" data-toggle="tab">Loguseragent</a></li>
</ul>

<!-- Tab panes -->
<div class="tab-content">
    
<div role="tabpanel" class="tab-pane active" id="loguseragent">
<table class="table table-bordered table-striped {{ count($loguseragents) > 0 ? 'datatable' : '' }}">
    <thead>
        <tr>
            <th>@lang('quickadmin.loguseragent.fields.os')</th>
                        <th>@lang('quickadmin.loguseragent.fields.browser')</th>
                        <th>@lang('quickadmin.loguseragent.fields.device')</th>
                        <th>@lang('quickadmin.loguseragent.fields.item-id')</th>
                        <th>@lang('quickadmin.loguseragent.fields.ipv6')</th>
                        <th>@lang('quickadmin.loguseragent.fields.uri')</th>
                        <th>@lang('quickadmin.loguseragent.fields.user')</th>
                                                <th>&nbsp;</th>

        </tr>
    </thead>

    <tbody>
        @if (count($loguseragents) > 0)
            @foreach ($loguseragents as $loguseragent)
                <tr data-entry-id="{{ $loguseragent->id }}">
                    <td field-key='os'>{{ $loguseragent->os }}</td>
                                <td field-key='browser'>{{ $loguseragent->browser }}</td>
                                <td field-key='device'>{{ $loguseragent->device }}</td>
                                <td field-key='item_id'>{{ $loguseragent->item_id }}</td>
                                <td field-key='ipv6'>{{ $loguseragent->ipv6 }}</td>
                                <td field-key='uri'>{{ $loguseragent->uri }}</td>
                                <td field-key='user'>{{ $loguseragent->user->name or '' }}</td>
                                                                <td>
                                    @can('loguseragent_view')
                                    <a href="{{ route('admin.loguseragents.show',[$loguseragent->id]) }}" class="btn btn-xs btn-primary">@lang('quickadmin.qa_view')</a>
                                    @endcan
                                    @can('loguseragent_edit')
                                    <a href="{{ route('admin.loguseragents.edit',[$loguseragent->id]) }}" class="btn btn-xs btn-info">@lang('quickadmin.qa_edit')</a>
                                    @endcan
                                    @can('loguseragent_delete')
{!! Form::open(array(
                                        'style' => 'display: inline-block;',
                                        'method' => 'DELETE',
                                        'onsubmit' => "return confirm('".trans("quickadmin.qa_are_you_sure")."');",
                                        'route' => ['admin.loguseragents.destroy', $loguseragent->id])) !!}
                                    {!! Form::submit(trans('quickadmin.qa_delete'), array('class' => 'btn btn-xs btn-danger')) !!}
                                    {!! Form::close() !!}
                                    @endcan
                                </td>

                </tr>
            @endforeach
        @else
            <tr>
                <td colspan="16">@lang('quickadmin.qa_no_entries_in_table')</td>
            </tr>
        @endif
    </tbody>
</table>
</div>
</div>

            <p>&nbsp;</p>

            <a href="{{ route('admin.users.index') }}" class="btn btn-default">@lang('quickadmin.qa_back_to_list')</a>
        </div>
    </div>
@stop


