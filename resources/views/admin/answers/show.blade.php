@extends('layouts.app')

@section('title', trans('quickadmin.answers.title') . ' | ' . trans('quickadmin.qa_view') . ' | ' . $answer->id)

@section('content')
    <h3 class="page-title">@lang('quickadmin.answers.title')</h3>

    <div class="panel panel-default">
        <div class="panel-heading">
            @lang('quickadmin.qa_view')
        </div>

        <div class="panel-body table-responsive">
            <div class="row">
                <div class="col-md-6">
                    <table class="table table-bordered table-striped">
                        <tr>
                            <th>@lang('quickadmin.answers.fields.title')</th>
                            <td field-key='title'>{{ $answer->title }}</td>
                        </tr>
                        <tr>
                            <th>@lang('quickadmin.answers.fields.open')</th>
                            <td field-key='open'><input type="checkbox" name="open" value="1" {{ $answer->open == 1 ? 'checked' : '' }} disabled></td>
                        </tr>
                        <tr>
                            <th>Hidden</th>
                            <td field-key='hidden'><input type="checkbox" name="hidden" value="1" {{ $answer->hidden == 1 ? 'checked' : '' }} disabled></td>
                        </tr>
                        <x-dates-in-show :model="$answer" />
                    </table>
                </div>
            </div><!-- Nav tabs -->
<ul class="nav nav-tabs" role="tablist">
    
<li role="presentation" class="active"><a href="#responses" aria-controls="responses" role="tab" data-toggle="tab">Responses</a></li>
<li role="presentation" class=""><a href="#answerlists" aria-controls="answerlists" role="tab" data-toggle="tab">Answerlists</a></li>
</ul>

<!-- Tab panes -->
<div class="tab-content">
    
<div role="tabpanel" class="tab-pane active" id="responses">
<table class="table table-bordered table-striped {{ count($responses) > 0 ? 'datatable' : '' }}">
    <thead>
        <tr>
            <th>@lang('quickadmin.responses.fields.questionnaire')</th>
                        <th>@lang('quickadmin.responses.fields.question')</th>
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
                    <td field-key='questionnaire'>{{ $response->questionnaire->name ?? '' }}</td>
                                <td field-key='question'>{{ $response->question->title ?? '' }}</td>
                                <td field-key='answer'>{{ $response->answer->title ?? '' }}</td>
                                <td field-key='content'>{!! $response->content !!}</td>
                                @if( request('show_deleted') == 1 )
                                <td>
                                    @can('response_delete')
                                                                        <form action="{{ route('admin.responses.restore', $response->id) }}" method="POST" style="display: inline-block;" onsubmit="return confirm('{{ trans('quickadmin.qa_are_you_sure') }}');">@csrf
                                    <button type="submit" class="btn btn-xs btn-success">{{ trans('quickadmin.qa_restore') }}</button>
                                    </form>
                                @endcan
                                    @can('response_delete')
                                                                        <form action="{{ route('admin.responses.perma_del', $response->id) }}" method="POST" style="display: inline-block;" onsubmit="return confirm('{{ trans('quickadmin.qa_are_you_sure') }}');">@csrf @method('DELETE')
                                    <button type="submit" class="btn btn-xs btn-danger">{{ trans('quickadmin.qa_permadel') }}</button>
                                    </form>
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
<form action="{{ route('admin.responses.destroy', $response->id) }}" method="POST" style="display: inline-block;" onsubmit="return confirm('{{ trans('quickadmin.qa_are_you_sure') }}');">@csrf @method('DELETE')
                                    <button type="submit" class="btn btn-xs btn-danger">{{ trans('quickadmin.qa_delete') }}</button>
                                    </form>
                                    @endcan
                                </td>
                                @endif
                </tr>
            @endforeach
        @else
            <tr>
                <td colspan="5">@lang('quickadmin.qa_no_entries_in_table')</td>
            </tr>
        @endif
    </tbody>
</table>
</div>
<div role="tabpanel" class="tab-pane " id="answerlists">
<table class="table table-bordered table-striped {{ count($answerlists) > 0 ? 'datatable' : '' }}">
    <thead>
        <tr>
            <th>@lang('quickadmin.answerlists.fields.title')</th>
                        <th>@lang('quickadmin.answerlists.fields.type')</th>
                        <th>@lang('quickadmin.answerlists.fields.answers')</th>
                        @if( request('show_deleted') == 1 )
                        <th>&nbsp;</th>
                        @else
                        <th>&nbsp;</th>
                        @endif
        </tr>
    </thead>

    <tbody>
        @if (count($answerlists) > 0)
            @foreach ($answerlists as $answerlist)
                <tr data-entry-id="{{ $answerlist->id }}">
                    <td field-key='title'>{{ $answerlist->title }}</td>
                                <td field-key='type'>{{ $answerlist->type }}</td>
                                <td field-key='answers'>
                                    @foreach ($answerlist->answers as $singleAnswers)
                                        <span class="label label-info label-many">{{ $singleAnswers->title }}</span>
                                    @endforeach
                                </td>
                                @if( request('show_deleted') == 1 )
                                <td>
                                    @can('answerlist_delete')
                                                                        <form action="{{ route('admin.answerlists.restore', $answerlist->id) }}" method="POST" style="display: inline-block;" onsubmit="return confirm('{{ trans('quickadmin.qa_are_you_sure') }}');">@csrf
                                    <button type="submit" class="btn btn-xs btn-success">{{ trans('quickadmin.qa_restore') }}</button>
                                    </form>
                                @endcan
                                    @can('answerlist_delete')
                                                                        <form action="{{ route('admin.answerlists.perma_del', $answerlist->id) }}" method="POST" style="display: inline-block;" onsubmit="return confirm('{{ trans('quickadmin.qa_are_you_sure') }}');">@csrf @method('DELETE')
                                    <button type="submit" class="btn btn-xs btn-danger">{{ trans('quickadmin.qa_permadel') }}</button>
                                    </form>
                                @endcan
                                </td>
                                @else
                                <td>
                                    @can('answerlist_view')
                                    <a href="{{ route('admin.answerlists.show',[$answerlist->id]) }}" class="btn btn-xs btn-primary">@lang('quickadmin.qa_view')</a>
                                    @endcan
                                    @can('answerlist_edit')
                                    <a href="{{ route('admin.answerlists.edit',[$answerlist->id]) }}" class="btn btn-xs btn-info">@lang('quickadmin.qa_edit')</a>
                                    @endcan
                                    @can('answerlist_delete')
<form action="{{ route('admin.answerlists.destroy', $answerlist->id) }}" method="POST" style="display: inline-block;" onsubmit="return confirm('{{ trans('quickadmin.qa_are_you_sure') }}');">@csrf @method('DELETE')
                                    <button type="submit" class="btn btn-xs btn-danger">{{ trans('quickadmin.qa_delete') }}</button>
                                    </form>
                                    @endcan
                                </td>
                                @endif
                </tr>
            @endforeach
        @else
            <tr>
                <td colspan="4">@lang('quickadmin.qa_no_entries_in_table')</td>
            </tr>
        @endif
    </tbody>
</table>
</div>
</div>

            <p>&nbsp;</p>

            <a href="{{ route('admin.answers.index') }}" class="btn btn-default">@lang('quickadmin.qa_back_to_list')</a>
        </div>
    </div>
@stop
