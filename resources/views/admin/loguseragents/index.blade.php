@inject('request', 'Illuminate\Http\Request')
@extends('layouts.app')

@section('content')
    <h3 class="page-title">@lang('quickadmin.loguseragent.title')</h3>
    @can('loguseragent_create')
    <p>
        
        
    </p>
    @endcan

    

    <div class="panel panel-default">
        <div class="panel-heading">
            @lang('quickadmin.qa_list')
        </div>

        <div class="panel-body table-responsive">
            <table class="table table-bordered table-striped ajaxTable @can('loguseragent_delete') dt-select @endcan">
                <thead>
                    <tr>
                        @can('loguseragent_delete')
                            <th style="text-align:center;"><input type="checkbox" id="select-all" /></th>
                        @endcan

                        <th>@lang('Date')</th>
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
            </table>
        </div>
    </div>
@stop

@section('javascript') 
    <script>
        @can('loguseragent_delete')
            window.route_mass_crud_entries_destroy = '{{ route('admin.loguseragents.mass_destroy') }}';
        @endcan
        $(document).ready(function () {
            window.dtDefaultOptions.ajax = '{!! route('admin.loguseragents.index') !!}';
            window.dtDefaultOptions.columns = [@can('loguseragent_delete')
                    {data: 'massDelete', name: 'id', searchable: false, sortable: false},
                @endcan
                {data: 'created_at', name: 'created_at'},
                {data: 'os', name: 'os'},
                {data: 'browser', name: 'browser'},
                {data: 'device', name: 'device'},
                {data: 'item_id', name: 'item_id'},
                {data: 'ipv6', name: 'ipv6'},
                {data: 'uri', name: 'uri'},
                {data: 'user.name', name: 'user.name'},
                
                {data: 'actions', name: 'actions', searchable: false, sortable: false}
            ];
            processAjaxTables();
        });
    </script>
@endsection