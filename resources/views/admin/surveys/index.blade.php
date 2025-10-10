@inject('request', 'Illuminate\Http\Request')
@extends('layouts.app')

@section('title', trans('quickadmin.surveys.title') . ' | ' . trans('quickadmin.qa_list'))

@section('content')
    <h3 class="page-title">@lang('quickadmin.surveys.title')</h3>
    @can('survey_create')
    <p>
        <a href="{{ route('admin.surveys.create') }}" class="btn btn-success">@lang('quickadmin.qa_add_new')</a>
        
    </p>
    @endcan

    @can('survey_delete')
    <p>
        <ul class="list-inline">
            <li><a href="{{ route('admin.surveys.index') }}" style="{{ request('show_deleted') == 1 ? '' : 'font-weight: 700' }}">@lang('quickadmin.qa_all')</a></li> |
            <li><a href="{{ route('admin.surveys.index') }}?show_deleted=1" style="{{ request('show_deleted') == 1 ? 'font-weight: 700' : '' }}">@lang('quickadmin.qa_trash')</a></li>
        </ul>
    </p>
    @endcan


    <div class="panel panel-default">
        <div class="panel-heading">
            @lang('quickadmin.qa_list')
        </div>

        <div class="panel-body table-responsive">
            <table class="table table-bordered table-striped {{ count($surveys) > 0 ? 'datatable' : '' }} @can('survey_delete') @if ( request('show_deleted') != 1 ) dt-select @endif @endcan">
                <thead>
                    <tr>
                        @can('survey_delete')
                            @if ( request('show_deleted') != 1 )<th style="text-align:center;"><input type="checkbox" id="select-all" /></th>@endif
                        @endcan

                        <th>@lang('quickadmin.surveys.fields.title')</th>
                        <th>@lang('quickadmin.surveys.fields.alias')</th>
                        <th>@lang('quickadmin.surveys.fields.institution')</th>
                        <th>@lang('quickadmin.surveys.fields.category')</th>
                        <th>@lang('quickadmin.surveys.fields.group')</th>
                        <th>@lang('quickadmin.surveys.fields.inform')</th>
                        <th>@lang('quickadmin.surveys.fields.access')</th>
                        <th>@lang('Replies')</th>
                        <th>{{ Form::checkbox("completed", 1, true , ["disabled"]) }}</th>
                        <th>@lang('Created at')</th>
                        @if( request('show_deleted') == 1 )
                        <th>&nbsp;</th>
                        @else
                        <th>&nbsp;</th>
                        @endif
                    </tr>
                </thead>
                
                <tbody>
                    @if (count($surveys) > 0)
                        @foreach ($surveys as $survey)
                            <tr data-entry-id="{{ $survey->id }}">
                                @can('survey_delete')
                                    @if ( request('show_deleted') != 1 )<td></td>@endif
                                @endcan

                                <td field-key='title'><a href="{{route('admin.surveys.show',$survey->id)}}">{{ $survey->title }}</a></td>
                                <td field-key='alias'>
                                    <a href="{{ route('frontend.create', $survey->alias) }}" target="_blank">{{ route('frontend.create', $survey->alias, 0) }}</a>
                                </td>
                                <td field-key='institution'>{{ $survey->institution->title ?? '' }}</td>
                                <td field-key='category'>
                                    @foreach ($survey->category as $singleCategory)
                                        <span class="label label-info label-many">{{ $singleCategory->title }}</span>
                                    @endforeach
                                </td>
                                <td field-key='group'>
                                    @foreach ($survey->group as $singleGroup)
                                        <span class="label label-info label-many">{{ $singleGroup->title }}</span>
                                    @endforeach
                                </td>
                                <td field-key='inform'>{{ Form::checkbox("inform", 1, $survey->inform == 1 ? true : false, ["disabled"]) }}</td>
                                <td field-key='access'>{{ $survey->access }}</td>
                                <td field-key='replies'>{{ $survey->questionnaires->count() }}</td>
                                <td field-key='completed'>{{ Form::checkbox("completed", 1, $survey->completed == 1 ? true : false, ["disabled"]) }}</td>
                                <td field-key='created_at'>{{ $survey->created_at->toFormattedDateString() }}</td>
                                @if( request('show_deleted') == 1 )
                                <td>
                                    @can('survey_delete')
                                                                        {!! Form::open(array(
                                        'style' => 'display: inline-block;',
                                        'method' => 'POST',
                                        'onsubmit' => "return confirm('".trans("quickadmin.qa_are_you_sure")."');",
                                        'route' => ['admin.surveys.restore', $survey->id])) !!}
                                    {!! Form::submit(trans('quickadmin.qa_restore'), array('class' => 'btn btn-xs btn-success')) !!}
                                    {!! Form::close() !!}
                                @endcan
                                    @can('survey_delete')
                                                                        {!! Form::open(array(
                                        'style' => 'display: inline-block;',
                                        'method' => 'DELETE',
                                        'onsubmit' => "return confirm('".trans("quickadmin.qa_are_you_sure")."');",
                                        'route' => ['admin.surveys.perma_del', $survey->id])) !!}
                                    {!! Form::submit(trans('quickadmin.qa_permadel'), array('class' => 'btn btn-xs btn-danger')) !!}
                                    {!! Form::close() !!}
                                @endcan
                                </td>
                                @else
                                <td>
                                    @can('survey_view')
                                    <a href="{{ route('admin.surveys.show',[$survey->id]) }}" class="btn btn-xs btn-primary">@lang('quickadmin.qa_view')</a>
                                    @endcan
                                    @can('survey_create')
                                    <a href="{{ route('admin.surveys.clone',[$survey->id]) }}" class="btn btn-xs btn-warning"><i class="fa fa-copy"></i> @lang('Clone')</a>
                                    @endcan
                                    @can('survey_edit')
                                    <a href="{{ route('admin.surveys.edit',[$survey->id]) }}" class="btn btn-xs btn-info">@lang('quickadmin.qa_edit')</a>
                                    @endcan
                                    @can('survey_delete')
{!! Form::open(array(
                                        'style' => 'display: inline-block;',
                                        'method' => 'DELETE',
                                        'onsubmit' => "return confirm('".trans("quickadmin.qa_are_you_sure")."');",
                                        'route' => ['admin.surveys.destroy', $survey->id])) !!}
                                    {!! Form::submit(trans('quickadmin.qa_delete'), array('class' => 'btn btn-xs btn-danger')) !!}
                                    {!! Form::close() !!}
                                    @endcan
                                </td>
                                @endif
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
@stop

@section('javascript') 
    <script>
        @can('survey_delete')
            @if ( request('show_deleted') != 1 ) window.route_mass_crud_entries_destroy = '{{ route('admin.surveys.mass_destroy') }}'; @endif
        @endcan

    </script>
@endsection