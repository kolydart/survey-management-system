@extends('layouts.app')

@section('content')
    <h3 class="page-title">@lang('quickadmin.institutions.title')</h3>

    <div class="panel panel-default">
        <div class="panel-heading">
            @lang('quickadmin.qa_view')
        </div>

        <div class="panel-body table-responsive">
            <div class="row">
                <div class="col-md-6">
                    <table class="table table-bordered table-striped">
                        <tr>
                            <th>@lang('quickadmin.institutions.fields.title')</th>
                            <td field-key='title'>{{ $institution->title }}</td>
                        </tr>
                    </table>
                </div>
            </div><!-- Nav tabs -->
<ul class="nav nav-tabs" role="tablist">
    
<li role="presentation" class="active"><a href="#surveys" aria-controls="surveys" role="tab" data-toggle="tab">Surveys</a></li>
</ul>

<!-- Tab panes -->
<div class="tab-content">
    
<div role="tabpanel" class="tab-pane active" id="surveys">
<table class="table table-bordered table-striped {{ count($surveys) > 0 ? 'datatable' : '' }}">
    <thead>
        <tr>
            <th>@lang('quickadmin.surveys.fields.title')</th>
                        <th>@lang('quickadmin.surveys.fields.category')</th>
                        <th>@lang('quickadmin.surveys.fields.group')</th>
                        <th>@lang('quickadmin.surveys.fields.access')</th>
                        <th>@lang('quickadmin.surveys.fields.completed')</th>
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
                    <td field-key='title'>{{ $survey->title }}</td>
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
                                <td field-key='access'>{{ $survey->access }}</td>
                                <td field-key='completed'>{{ Form::checkbox("completed", 1, $survey->completed == 1 ? true : false, ["disabled"]) }}</td>
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
                <td colspan="13">@lang('quickadmin.qa_no_entries_in_table')</td>
            </tr>
        @endif
    </tbody>
</table>
</div>
</div>

            <p>&nbsp;</p>

            <a href="{{ route('admin.institutions.index') }}" class="btn btn-default">@lang('quickadmin.qa_back_to_list')</a>
        </div>
    </div>
@stop
