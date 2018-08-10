@inject('request', 'Illuminate\Http\Request')
@extends('layouts.app')

@section('content')
    <h3 class="page-title">@lang('quickadmin.activitylog.title')</h3>
    @can('activitylog_create')
    <p>
        
        
    </p>
    @endcan

    

    <div class="panel panel-default">
        <div class="panel-heading">
            @lang('quickadmin.qa_list')
        </div>

        <div class="panel-body table-responsive">
            <table class="table table-bordered table-striped {{ count($activitylogs) > 0 ? 'datatable' : '' }} @can('activitylog_delete') dt-select @endcan">
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
                
                <tbody>
                    @if (count($activitylogs) > 0)
                        @foreach ($activitylogs as $activitylog)
                            <tr data-entry-id="{{ $activitylog->id }}">
                                @can('activitylog_delete')
                                    <td></td>
                                @endcan

                                <td field-key='causer_id'>{{ $activitylog->causer_id }}</td>
                                <td field-key='description'>{{ $activitylog->description }}</td>
                                <td field-key='subject_type'>{{ $activitylog->subject_type }}</td>
                                <td field-key='subject_id'>{{ $activitylog->subject_id }}</td>
                                                                <td>
                                    @can('activitylog_view')
                                    <a href="{{ route('admin.activitylogs.show',[$activitylog->id]) }}" class="btn btn-xs btn-primary">@lang('quickadmin.qa_view')</a>
                                    @endcan
                                    @can('activitylog_edit')
                                    <a href="{{ route('admin.activitylogs.edit',[$activitylog->id]) }}" class="btn btn-xs btn-info">@lang('quickadmin.qa_edit')</a>
                                    @endcan
                                    @can('activitylog_delete')
{!! Form::open(array(
                                        'style' => 'display: inline-block;',
                                        'method' => 'DELETE',
                                        'onsubmit' => "return confirm('".trans("quickadmin.qa_are_you_sure")."');",
                                        'route' => ['admin.activitylogs.destroy', $activitylog->id])) !!}
                                    {!! Form::submit(trans('quickadmin.qa_delete'), array('class' => 'btn btn-xs btn-danger')) !!}
                                    {!! Form::close() !!}
                                    @endcan
                                </td>

                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="12">@lang('quickadmin.qa_no_entries_in_table')</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
@stop

@section('javascript') 
    <script>
        @can('activitylog_delete')
            window.route_mass_crud_entries_destroy = '{{ route('admin.activitylogs.mass_destroy') }}';
        @endcan

    </script>
@endsection