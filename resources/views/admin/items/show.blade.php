@extends('layouts.app')

@section('content')
    <h3 class="page-title">@lang('quickadmin.items.title')</h3>

    <div class="panel panel-default">
        <div class="panel-heading">
            @lang('quickadmin.qa_view')
        </div>

        <div class="panel-body table-responsive">
            <div class="row">
                <div class="col-md-6">
                    <table class="table table-bordered table-striped">
                        <tr>
                            <th>@lang('quickadmin.items.fields.order')</th>
                            <td field-key='order'>{{ $item->order }}</td>
                        </tr>
                        <tr>
                            <th>@lang('quickadmin.items.fields.survey')</th>
                            <td field-key='survey'><a href="{{route('admin.surveys.show',$item->survey->id)}}">{{ $item->survey->title ?? '' }}</a></td>
                        </tr>
                        <tr>
                            <th>@lang('quickadmin.items.fields.question')</th>
                            <td field-key='question'>@if (!$item->label)
                                <a href="{{route('admin.questions.show',$item->question->id)}}">{{ $item->question->title ?? '' }}</a>
                            @endif</td>
                        </tr>
                        <tr>
                            <th>@lang('answerlist')</th>
                            <td field-key='answerlist'>@if (!$item->label)
                                <a href="{{route('admin.answerlists.show',$item->question->answerlist->id)}}">{{ $item->question->answerlist->title ?? '' }}</a>
                            @endif
                            </td>
                        </tr>
                        <tr>
                            <th>@lang('quickadmin.items.fields.label')</th>
                            <td field-key='label'>{{ Form::checkbox("label", 1, $item->label == 1 ? true : false, ["disabled"]) }}</td>
                        </tr>
                        {!! gateweb\common\presenter\Laraview::dates_in_show($item) !!}                        
                    </table>
                </div>
            </div>

            <p>&nbsp;</p>

            <a href="{{ route('admin.items.index') }}" class="btn btn-default">@lang('quickadmin.qa_back_to_list')</a>
            <p>&nbsp;</p>

        @if (!$item->label)
        <div role="tabpanel">
            <!-- Nav tabs -->
            <ul class="nav nav-tabs" role="tablist">
                <li role="presentation" class="active">
                    <a href="#responses" aria-controls="responses" role="tab" data-toggle="tab">@lang('Responses')</a>
                </li>
            </ul>
        
            <!-- Tab panes -->
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane active" id="responses">
                    <table class="table table-bordered table-striped {{ count($item->question->responses) > 0 ? 'datatable' : '' }}">
                        <thead>
                            <tr>
                                <th>@lang('id')</th>
                                <th>@lang('Questionnaire')</th>
                                <th>@lang('Answer')</th>
                                <th>@lang('Content')</th>
                                <th>@lang('Name')</th>
                                <th>@lang('Answer_id')</th>
                            </tr>
                        </thead>

                        <tbody>
                            @if (App\Response::whereIn('questionnaire_id',$item->survey->questionnaires->pluck('id'))->where('question_id',$item->question_id)->count() > 0)
                                @foreach (App\Response::whereIn('questionnaire_id',$item->survey->questionnaires->pluck('id'))->where('question_id',$item->question_id)->get() as $response)
                                    <tr data-entry-id="{{ $response->id }}">
                                        <td field-key='id'><a href="{{route('admin.responses.show',$response->id)}}">{{ $response->id }}</a></td>
                                        <td field-key='questionnaire'><a href="{{route('admin.questionnaires.show',$response->questionnaire_id)}}">{{ $response->questionnaire_id }}</a></td>
                                        <td field-key='answer'><a href="{{route('admin.answers.show',$response->answer_id)}}">{{$response->answer->title ?? ''}}</a></td>
                                        <td field-key='content'><a href="{{route('admin.answers.show',$response->answer_id)}}">{{ $response->content ?? '' }}</a></td>
                                        <td field-key='name'><a href="{{route('admin.questionnaires.show',$response->questionnaire_id)}}">{{$response->questionnaire->name ?? ''}}</a></td>
                                        <td field-key='answer_id'><a href="{{route('admin.answers.show',$response->answer_id)}}">{{ $response->answer->id }}</a></td>
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
        </div>
        @endif

        </div>
    </div>
@stop


