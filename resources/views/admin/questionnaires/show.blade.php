@extends('layouts.app')

@section('title', trans('quickadmin.questionnaires.title') . ' | ' . trans('quickadmin.qa_view') . ' | ' . $questionnaire->id)

@section('content')
    <h3 class="page-title">@lang('quickadmin.questionnaires.title')</h3>

    <div class="panel panel-default">
        <div class="panel-heading">
            @lang('quickadmin.qa_view')
        </div>

        <div class="panel-body table-responsive">
            <div class="row">
                <div class="col-md-6">
                    <table class="table table-bordered table-striped">
                        <tr>
                            <th>@lang('quickadmin.questionnaires.fields.survey')</th>
                            <td field-key='survey'><a href="{{route('admin.surveys.show',$questionnaire->survey->id)}}">{{ $questionnaire->survey->title ?? '' }}</a></td>
                        </tr>
                        <tr>
                            <th>@lang('quickadmin.questionnaires.fields.name')</th>
                            <td field-key='name'>{{ $questionnaire->name }}</td>
                        </tr>
                        <tr>
                            <th>@lang('Filled percent')</th>
                            <td field-key='name'>{{ $questionnaire->filled_percent * 100 }}%</td>
                        </tr>
                        <x-dates-in-show :model="$questionnaire" />
                    </table>
                </div>
            </div>


<!-- Nav tabs -->
<ul class="nav nav-tabs" role="tablist">
    
<li role="presentation" class="active"><a href="#render" aria-controls="render" role="tab" data-toggle="tab">Render</a></li>
<li role="presentation" class=""><a href="#responses" aria-controls="responses" role="tab" data-toggle="tab">Responses</a></li>
</ul>

<!-- Tab panes -->
<div class="tab-content">

<div role="tabpanel" class="tab-pane active" id="render">
    <br>@include('partials.questionnaireRender')
</div>

    
<div role="tabpanel" class="tab-pane" id="responses">
<table class="table table-bordered table-striped {{ count($responses) > 0 ? 'datatable' : '' }}">
    <thead>
        <tr>
                        <th style="width: 10px;">@lang('q_id')</th>
                        <th>@lang('quickadmin.responses.fields.question')</th>
                        <th>@lang('a_id')</th>
                        <th>@lang('quickadmin.responses.fields.answer')</th>
                        <th>@lang('quickadmin.responses.fields.content')</th>
                        @if( request('show_deleted') == 1 )
                        <th>&nbsp;</th>
                        @else
                        <th>&nbsp;</th>
                        @endif
        </tr>
    </thead>

    <tbody>
        @if (count($responses) > 0)
            @foreach ($responses as $response)
                <tr data-entry-id="{{ $response->id }}">
                                <td field-key='question_id'>{{ $response->question->id }}</td>
                                <td field-key='question'>{{ $response->question->title ?? '' }}</td>
                                <td field-key='answer_id'>{{ $response->answer->id }}</td>
                                <td field-key='answer'>{{ $response->answer->title ?? '' }}</td>
                                <td field-key='content'>{!! $response->content !!}</td>
                                @if( request('show_deleted') == 1 )
                                <td>
                                    @can('response_delete')
                                                                        {!! Form::open(array(
                                        'style' => 'display: inline-block;',
                                        'method' => 'POST',
                                        'onsubmit' => "return confirm('".trans("quickadmin.qa_are_you_sure")."');",
                                        'route' => ['admin.responses.restore', $response->id])) !!}
                                    {!! Form::submit(trans('quickadmin.qa_restore'), array('class' => 'btn btn-xs btn-success')) !!}
                                    {!! Form::close() !!}
                                @endcan
                                    @can('response_delete')
                                                                        {!! Form::open(array(
                                        'style' => 'display: inline-block;',
                                        'method' => 'DELETE',
                                        'onsubmit' => "return confirm('".trans("quickadmin.qa_are_you_sure")."');",
                                        'route' => ['admin.responses.perma_del', $response->id])) !!}
                                    {!! Form::submit(trans('quickadmin.qa_permadel'), array('class' => 'btn btn-xs btn-danger')) !!}
                                    {!! Form::close() !!}
                                @endcan
                                </td>
                                @else
                                <td>
                                    @can('response_view')
                                    <a href="{{ route('admin.responses.show',[$response->id]) }}" class="btn btn-xs btn-primary">@lang('quickadmin.qa_view')</a>
                                    @endcan
                                    @can('response_edit')
                                    <a href="{{ route('admin.responses.edit',[$response->id]) }}" class="btn btn-xs btn-info">@lang('quickadmin.qa_edit')</a>
                                    @endcan
                                    @can('response_delete')
                                    {!! Form::open(array(
                                        'style' => 'display: inline-block;',
                                        'method' => 'DELETE',
                                        'onsubmit' => "return confirm('".trans("quickadmin.qa_are_you_sure")."');",
                                        'route' => ['admin.responses.destroy', $response->id])) !!}
                                    {!! Form::submit(trans('quickadmin.qa_delete'), array('class' => 'btn btn-xs btn-danger')) !!}
                                    {!! Form::close() !!}
                                    @endcan
                                </td>
                                @endif
                </tr>
            @endforeach
        @else
            <tr>
                <td colspan="9">@lang('quickadmin.qa_no_entries_in_table')</td>
            </tr>
        @endif
    </tbody>
</table>
</div>
</div>

            <p>&nbsp;</p>

            <a href="{{ route('admin.questionnaires.index') }}" class="btn btn-default">@lang('quickadmin.qa_back_to_list')</a>
        </div>

        @can('questionnaire_delete')
        {!! Form::open(array(
            'style' => 'display: inline-block; width:100%;',
            'method' => 'DELETE',
            'onsubmit' => "return confirm('".trans("quickadmin.qa_are_you_sure")."');",
            'route' => ['admin.questionnaires.destroy', $questionnaire->id])) !!}
        {!! Form::submit(trans('quickadmin.qa_delete'), array('class' => 'btn btn-danger', 'style'=>'float:right;margin-right:20px;')) !!}
        {!! Form::close() !!}
        @endcan         
    </div>
@stop


