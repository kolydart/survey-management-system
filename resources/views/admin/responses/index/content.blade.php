@inject('request', 'Illuminate\Http\Request')
@extends('layouts.app')

@section('content')
    <h3 class="page-title">@lang('quickadmin.responses.title')</h3>
    @can('response_create')
    <p>
        <a href="{{ route('admin.responses.create') }}" class="btn btn-success">@lang('quickadmin.qa_add_new')</a>
        
    </p>
    @endcan



    <div class="panel panel-default">
        <div class="panel-heading">
            @lang('quickadmin.qa_list')
        </div>

        <div class="panel-body table-responsive">
            <table class="table table-bordered table-striped {{ count($responses) > 0 ? 'datatable' : '' }}">
                <thead>
                    <tr>
                        <th>@lang('quickadmin.responses.fields.content')</th>
                        <th>@lang('Survey')</th>
                        <th>@lang('quickadmin.responses.fields.question')</th>
                        <th>@lang('quickadmin.responses.fields.answer')</th>
                        <th>@lang('created_at')</th>
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

                                <td field-key='content'>{!! $response->content !!}</td>
                                <td field-key='question'>{{ $response->questionnaire->survey->title ?? '' }}</td>
                                <td field-key='question'>{{ $response->question->title ?? '' }}</td>
                                <td field-key='answer'>{{ $response->answer->title ?? '' }}</td>
                                <td field-key='created_at'>{{ $response->created_at ?? '' }}</td>
                                <td>
                                    @can('response_view')
                                    <a href="{{ route('admin.responses.show',[$response->id]) }}" class="btn btn-xs btn-primary">@lang('quickadmin.qa_view')</a>
                                    @endcan
                                    @can('response_edit')
                                    <a href="{{ route('admin.responses.edit',[$response->id]) }}" class="btn btn-xs btn-info">@lang('quickadmin.qa_edit')</a>
                                    @endcan
                                    
                                </td>
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
@stop

