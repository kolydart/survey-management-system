@extends('layouts.app')

@section('content')
    <h3 class="page-title">@lang('quickadmin.surveys.title')</h3>

    <div class="panel panel-default">
        <div class="panel-heading">
            @lang('quickadmin.qa_view')
        </div>

        <div class="panel-body table-responsive">
            <div class="row">
                <div class="col-md-6">
                    <table class="table table-bordered table-striped">
                        <tr>
                            <th>@lang('quickadmin.surveys.fields.title')</th>
                            <td field-key='title'>{{ $survey->title }}</td>
                        </tr>
                        <tr>
                            <th>@lang('quickadmin.surveys.fields.category')</th>
                            <td field-key='category'>
                                @foreach ($survey->category as $singleCategory)
                                    <span class="label label-info label-many">{{ $singleCategory->title }}</span>
                                @endforeach
                            </td>
                        </tr>
                        <tr>
                            <th>@lang('quickadmin.surveys.fields.group')</th>
                            <td field-key='group'>
                                @foreach ($survey->group as $singleGroup)
                                    <span class="label label-info label-many">{{ $singleGroup->title }}</span>
                                @endforeach
                            </td>
                        </tr>
                        <tr>
                            <th>@lang('quickadmin.surveys.fields.introduction')</th>
                            <td field-key='introduction'>{!! $survey->introduction !!}</td>
                        </tr>
                        <tr>
                            <th>@lang('quickadmin.surveys.fields.notes')</th>
                            <td field-key='notes'>{!! $survey->notes !!}</td>
                        </tr>
                        <tr>
                            <th>@lang('quickadmin.surveys.fields.access')</th>
                            <td field-key='access'>{{ $survey->access }}</td>
                        </tr>
                        <tr>
                            <th>@lang('quickadmin.surveys.fields.completed')</th>
                            <td field-key='completed'>{{ Form::checkbox("completed", 1, $survey->completed == 1 ? true : false, ["disabled"]) }}</td>
                        </tr>
                    </table>
                </div>
            </div><!-- Nav tabs -->
<ul class="nav nav-tabs" role="tablist">
    
<li role="presentation" class="active"><a href="#questionnaires" aria-controls="questionnaires" role="tab" data-toggle="tab">Questionnaires</a></li>
<li role="presentation" class=""><a href="#items" aria-controls="items" role="tab" data-toggle="tab">Items</a></li>
</ul>

<!-- Tab panes -->
<div class="tab-content">
    
<div role="tabpanel" class="tab-pane active" id="questionnaires">
<table class="table table-bordered table-striped {{ count($questionnaires) > 0 ? 'datatable' : '' }}">
    <thead>
        <tr>
            <th>@lang('quickadmin.questionnaires.fields.survey')</th>
                        <th>@lang('quickadmin.questionnaires.fields.name')</th>
                        @if( request('show_deleted') == 1 )
                        <th>&nbsp;</th>
                        @else
                        <th>&nbsp;</th>
                        @endif
        </tr>
    </thead>

    <tbody>
        @if (count($questionnaires) > 0)
            @foreach ($questionnaires as $questionnaire)
                <tr data-entry-id="{{ $questionnaire->id }}">
                    <td field-key='survey'>{{ $questionnaire->survey->title or '' }}</td>
                                <td field-key='name'>{{ $questionnaire->name }}</td>
                                @if( request('show_deleted') == 1 )
                                <td>
                                    @can('questionnaire_delete')
                                                                        {!! Form::open(array(
                                        'style' => 'display: inline-block;',
                                        'method' => 'POST',
                                        'onsubmit' => "return confirm('".trans("quickadmin.qa_are_you_sure")."');",
                                        'route' => ['admin.questionnaires.restore', $questionnaire->id])) !!}
                                    {!! Form::submit(trans('quickadmin.qa_restore'), array('class' => 'btn btn-xs btn-success')) !!}
                                    {!! Form::close() !!}
                                @endcan
                                    @can('questionnaire_delete')
                                                                        {!! Form::open(array(
                                        'style' => 'display: inline-block;',
                                        'method' => 'DELETE',
                                        'onsubmit' => "return confirm('".trans("quickadmin.qa_are_you_sure")."');",
                                        'route' => ['admin.questionnaires.perma_del', $questionnaire->id])) !!}
                                    {!! Form::submit(trans('quickadmin.qa_permadel'), array('class' => 'btn btn-xs btn-danger')) !!}
                                    {!! Form::close() !!}
                                @endcan
                                </td>
                                @else
                                <td>
                                    @can('questionnaire_view')
                                    <a href="{{ route('admin.questionnaires.show',[$questionnaire->id]) }}" class="btn btn-xs btn-primary">@lang('quickadmin.qa_view')</a>
                                    @endcan
                                    @can('questionnaire_edit')
                                    <a href="{{ route('admin.questionnaires.edit',[$questionnaire->id]) }}" class="btn btn-xs btn-info">@lang('quickadmin.qa_edit')</a>
                                    @endcan
                                    @can('questionnaire_delete')
{!! Form::open(array(
                                        'style' => 'display: inline-block;',
                                        'method' => 'DELETE',
                                        'onsubmit' => "return confirm('".trans("quickadmin.qa_are_you_sure")."');",
                                        'route' => ['admin.questionnaires.destroy', $questionnaire->id])) !!}
                                    {!! Form::submit(trans('quickadmin.qa_delete'), array('class' => 'btn btn-xs btn-danger')) !!}
                                    {!! Form::close() !!}
                                    @endcan
                                </td>
                                @endif
                </tr>
            @endforeach
        @else
            <tr>
                <td colspan="7">@lang('quickadmin.qa_no_entries_in_table')</td>
            </tr>
        @endif
    </tbody>
</table>
</div>
<div role="tabpanel" class="tab-pane " id="items">
<table class="table table-bordered table-striped {{ count($items) > 0 ? 'datatable' : '' }}">
    <thead>
        <tr>
            <th>@lang('quickadmin.items.fields.survey')</th>
                        <th>@lang('quickadmin.items.fields.question')</th>
                        <th>@lang('quickadmin.items.fields.order')</th>
                        @if( request('show_deleted') == 1 )
                        <th>&nbsp;</th>
                        @else
                        <th>&nbsp;</th>
                        @endif
        </tr>
    </thead>

    <tbody>
        @if (count($items) > 0)
            @foreach ($items as $item)
                <tr data-entry-id="{{ $item->id }}">
                    <td field-key='survey'>{{ $item->survey->title or '' }}</td>
                                <td field-key='question'>{{ $item->question->title or '' }}</td>
                                <td field-key='order'>{{ $item->order }}</td>
                                @if( request('show_deleted') == 1 )
                                <td>
                                    @can('item_delete')
                                                                        {!! Form::open(array(
                                        'style' => 'display: inline-block;',
                                        'method' => 'POST',
                                        'onsubmit' => "return confirm('".trans("quickadmin.qa_are_you_sure")."');",
                                        'route' => ['admin.items.restore', $item->id])) !!}
                                    {!! Form::submit(trans('quickadmin.qa_restore'), array('class' => 'btn btn-xs btn-success')) !!}
                                    {!! Form::close() !!}
                                @endcan
                                    @can('item_delete')
                                                                        {!! Form::open(array(
                                        'style' => 'display: inline-block;',
                                        'method' => 'DELETE',
                                        'onsubmit' => "return confirm('".trans("quickadmin.qa_are_you_sure")."');",
                                        'route' => ['admin.items.perma_del', $item->id])) !!}
                                    {!! Form::submit(trans('quickadmin.qa_permadel'), array('class' => 'btn btn-xs btn-danger')) !!}
                                    {!! Form::close() !!}
                                @endcan
                                </td>
                                @else
                                <td>
                                    @can('item_view')
                                    <a href="{{ route('admin.items.show',[$item->id]) }}" class="btn btn-xs btn-primary">@lang('quickadmin.qa_view')</a>
                                    @endcan
                                    @can('item_edit')
                                    <a href="{{ route('admin.items.edit',[$item->id]) }}" class="btn btn-xs btn-info">@lang('quickadmin.qa_edit')</a>
                                    @endcan
                                    @can('item_delete')
{!! Form::open(array(
                                        'style' => 'display: inline-block;',
                                        'method' => 'DELETE',
                                        'onsubmit' => "return confirm('".trans("quickadmin.qa_are_you_sure")."');",
                                        'route' => ['admin.items.destroy', $item->id])) !!}
                                    {!! Form::submit(trans('quickadmin.qa_delete'), array('class' => 'btn btn-xs btn-danger')) !!}
                                    {!! Form::close() !!}
                                    @endcan
                                </td>
                                @endif
                </tr>
            @endforeach
        @else
            <tr>
                <td colspan="8">@lang('quickadmin.qa_no_entries_in_table')</td>
            </tr>
        @endif
    </tbody>
</table>
</div>
</div>

            <p>&nbsp;</p>

            <a href="{{ route('admin.surveys.index') }}" class="btn btn-default">@lang('quickadmin.qa_back_to_list')</a>
        </div>
    </div>
@stop
