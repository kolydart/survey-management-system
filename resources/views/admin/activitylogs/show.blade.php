@extends('layouts.app')

@section('content')
    <h3 class="page-title">@lang('quickadmin.activitylog.title')</h3>

    <div class="panel panel-default">
        <div class="panel-heading">
            @lang('quickadmin.qa_view')
        </div>

        <div class="panel-body table-responsive">
            <div class="row">
                <div class="col-md-6">
                    <table class="table table-bordered table-striped">
                        <tr>
                            <th>@lang('gw.date')</th>
                            <td field-key='date'>{{$activitylog->created_at}}</td>
                        </tr>
                        <tr>
                            <th>@lang('quickadmin.activitylog.fields.log-name')</th>
                            <td field-key='log_name'>{{ $activitylog->log_name }}</td>
                        </tr>
                        <tr>
                            <th>@lang('quickadmin.activitylog.fields.causer-type')</th>
                            <td field-key='causer_type'>{{ $activitylog->causer_type }}</td>
                        </tr>
                        <tr>
                            <th>@lang('quickadmin.activitylog.fields.causer-id')</th>
                            <td field-key='causer_id'>
                                @if ($activitylog->causer_type == 'App\User' && App\User::find($activitylog->causer_id))
                                    <a href="{{route('admin.users.show',$activitylog->causer_id)}}">{{ App\User::find($activitylog->causer_id)->name }}</a>
                                @else
                                    {{ $activitylog->causer_id }}
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>@lang('quickadmin.activitylog.fields.description')</th>
                            <td field-key='description'>{{ $activitylog->description }}</td>
                        </tr>
                        <tr>
                            <th>@lang('quickadmin.activitylog.fields.subject-type')</th>
                            <td field-key='subject_type'>{{ $activitylog->subject_type }}</td>
                        </tr>
                        <tr>
                            <th>@lang('quickadmin.activitylog.fields.subject-id')</th>
                            <td field-key='subject_id'>{{ $activitylog->subject_id }}</td>
                        </tr>
                        <tr>
                            <th>@lang('quickadmin.activitylog.fields.properties')</th>
                            <td field-key='properties'><pre>{!! print_r(json_decode($activitylog->properties,true)) !!}</pre></td>
                        </tr>
                    </table>
                </div>
            </div>

            <p>&nbsp;</p>

            <a href="{{ route('admin.activitylogs.index') }}" class="btn btn-default">@lang('quickadmin.qa_back_to_list')</a>
        </div>
    </div>
@stop
