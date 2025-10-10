@extends('layouts.app')

@section('title', trans('quickadmin.answerlists.title') . ' | ' . trans('quickadmin.qa_view') . ' | ' . $answerlist->id)

@section('content')
    <h3 class="page-title">@lang('quickadmin.answerlists.title')</h3>

    <div class="panel panel-default">
        <div class="panel-heading">
            @lang('quickadmin.qa_view')
        </div>

        <div class="panel-body table-responsive">
            <div class="row">
                <div class="col-md-6">
                    <table class="table table-bordered table-striped">
                        <tr>
                            <th>@lang('quickadmin.answerlists.fields.title')</th>
                            <td field-key='title'>{{ $answerlist->title }}</td>
                        </tr>
                        <tr>
                            <th>@lang('quickadmin.answerlists.fields.type')</th>
                            <td field-key='type'>{{ $answerlist->type }}</td>
                        <tr>
                            <th>@lang('quickadmin.answerlists.fields.answers')</th>
                            <td field-key='answers'>
                                @foreach ($answerlist->answers as $singleAnswers)
                                    <a class="btn btn-info btn-sm" href="{{route('admin.answers.show',$singleAnswers->id)}}">{{ $singleAnswers->title }}</a>
                                @endforeach
                            </td>
                        </tr>
                        </tr>                        <tr>
                            <th>@lang('quickadmin.answerlists.fields.remove_unused')</th>
                            <td field-key='remove_unused'>
                                <input type="checkbox" name="remove_unused" {{ $answerlist->remove_unused ? 'checked' : '' }} value="1">
                            </td>
                        </tr>
                        <x-dates-in-show :model="$answerlist" />
                    </table>
                </div>
            </div><!-- Nav tabs -->
<ul class="nav nav-tabs" role="tablist">
    
<li role="presentation" class="active"><a href="#questions" aria-controls="questions" role="tab" data-toggle="tab">Questions</a></li>
</ul>

<!-- Tab panes -->
<div class="tab-content">
    
<div role="tabpanel" class="tab-pane active" id="questions">
<table class="table table-bordered table-striped {{ count($questions) > 0 ? 'datatable' : '' }}">
    <thead>
        <tr>
            <th>@lang('quickadmin.questions.fields.title')</th>
                        @if( request('show_deleted') == 1 )
                        <th>&nbsp;</th>
                        @else
                        <th>&nbsp;</th>
                        @endif
        </tr>
    </thead>

    <tbody>
        @if (count($questions) > 0)
            @foreach ($questions as $question)
                <tr data-entry-id="{{ $question->id }}">
                    <td field-key='title'>{{ $question->title }}</td>
                                @if( request('show_deleted') == 1 )
                                <td>
                                    @can('question_delete')
                                                                        {!! Form::open(array(
                                        'style' => 'display: inline-block;',
                                        'method' => 'POST',
                                        'onsubmit' => "return confirm('".trans("quickadmin.qa_are_you_sure")."');",
                                        'route' => ['admin.questions.restore', $question->id])) !!}
                                    {!! Form::submit(trans('quickadmin.qa_restore'), array('class' => 'btn btn-xs btn-success')) !!}
                                    {!! Form::close() !!}
                                @endcan
                                    @can('question_delete')
                                                                        {!! Form::open(array(
                                        'style' => 'display: inline-block;',
                                        'method' => 'DELETE',
                                        'onsubmit' => "return confirm('".trans("quickadmin.qa_are_you_sure")."');",
                                        'route' => ['admin.questions.perma_del', $question->id])) !!}
                                    {!! Form::submit(trans('quickadmin.qa_permadel'), array('class' => 'btn btn-xs btn-danger')) !!}
                                    {!! Form::close() !!}
                                @endcan
                                </td>
                                @else
                                <td>
                                    @can('question_view')
                                    <a href="{{ route('admin.questions.show',[$question->id]) }}" class="btn btn-xs btn-primary">@lang('quickadmin.qa_view')</a>
                                    @endcan
                                    @can('question_edit')
                                    <a href="{{ route('admin.questions.edit',[$question->id]) }}" class="btn btn-xs btn-info">@lang('quickadmin.qa_edit')</a>
                                    @endcan
                                    @can('question_delete')
{!! Form::open(array(
                                        'style' => 'display: inline-block;',
                                        'method' => 'DELETE',
                                        'onsubmit' => "return confirm('".trans("quickadmin.qa_are_you_sure")."');",
                                        'route' => ['admin.questions.destroy', $question->id])) !!}
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
</div>

            <p>&nbsp;</p>

            <a href="{{ route('admin.answerlists.index') }}" class="btn btn-default">@lang('quickadmin.qa_back_to_list')</a>
        </div>
    </div>
@stop
