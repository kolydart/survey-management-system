@inject('request', 'Illuminate\Http\Request')
@extends('layouts.app')

@section('content')
    <h3 class="page-title">@lang('quickadmin.activitylog.title')</h3>
    @can('activitylog_create')
    <p>
        <a href="{{ route('admin.activitylogs.create') }}" class="btn btn-success">@lang('quickadmin.qa_add_new')</a>
        
    </p>
    @endcan

    

    <div class="panel panel-default">
        <div class="panel-heading">
            @lang('quickadmin.qa_list')
        </div>

        <div class="panel-body table-responsive">
            <table class="table table-bordered table-striped ajaxTable @can('activitylog_delete') dt-select @endcan">
                <thead>
                    <tr>
                        @can('activitylog_delete')
                            <th style="text-align:center;"><input type="checkbox" id="select-all" /></th>
                        @endcan

                        <th>@lang('quickadmin.activitylog.fields.causer-id')</th>
                        <th>@lang('quickadmin.activitylog.fields.description')</th>
                        <th>@lang('quickadmin.activitylog.fields.subject-type')</th>
                        <th>@lang('quickadmin.activitylog.fields.subject-id')</th>
                                                <th>&nbsp;</th>

                    </tr>
                </thead>
            </table>
        </div>
    </div>
@stop

@section('javascript') 
    <script>
        @can('activitylog_delete')
            window.route_mass_crud_entries_destroy = '{{ route('admin.activitylogs.mass_destroy') }}';
        @endcan
        $(document).ready(function () {
            window.dtDefaultOptions.ajax = '{!! route('admin.activitylogs.index') !!}';
            window.dtDefaultOptions.columns = [@can('activitylog_delete')
                    {data: 'massDelete', name: 'id', searchable: false, sortable: false},
                @endcan{data: 'causer_id', name: 'causer_id'},
                {data: 'description', name: 'description'},
                {data: 'subject_type', name: 'subject_type'},
                {data: 'subject_id', name: 'subject_id'},
                
                {data: 'actions', name: 'actions', searchable: false, sortable: false}
            ];
            processAjaxTables();
        });
    </script>
@endsection