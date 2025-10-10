@extends('layouts.app')

@section('title', trans('quickadmin.responses.title') . ' | ' . trans('quickadmin.qa_view') . ' | ' . $response->id)

@section('content')
    <h3 class="page-title">@lang('quickadmin.responses.title')</h3>

    <div class="panel panel-default">
        <div class="panel-heading">
            @lang('quickadmin.qa_view')
        </div>

        <div class="panel-body table-responsive">
            <div class="row">
                <div class="col-md-6">
                    <table class="table table-bordered table-striped">
                        <tr>
                            <th>@lang('quickadmin.responses.fields.questionnaire')</th>
                            <td field-key='questionnaire'>
                                @if ($response->questionnaire)
                                    @can('questionnaire_view')
                                        <a href="{{route('admin.questionnaires.show',$response->questionnaire->id)}}">
                                            {{ $response->questionnaire->id }}: {{ $response->questionnaire->survey->title}}
                                            @if ($response->questionnaire->name && Gate::allows('survey_edit'))
                                                ({{$response->questionnaire->name}})
                                            @endif
                                        </a>
                                    @else
                                        {{ $response->questionnaire->id }}: {{ $response->questionnaire->survey->title}}
                                    @endcan
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>@lang('quickadmin.responses.fields.question')</th>
                            <td field-key='question'>
                                @can('question_view')
                                    <a href="{{route('admin.questions.show',$response->question->id)}}">{{ $response->question->title ?? '' }}</a>
                                @else
                                    {{ $response->question->title ?? '' }}
                                @endcan
                            </td>
                        </tr>
                        <tr>
                            <th>@lang('quickadmin.responses.fields.answer')</th>
                            <td field-key='answer'>
                                @can('answer_view')
                                    <a href="{{route('admin.answers.show',$response->answer->id)}}">{{ $response->answer->title ?? '' }}</a>
                                @else
                                    {{ $response->answer->title ?? '' }}
                                @endcan
                            </td>
                        </tr>
                        <tr>
                            <th>@lang('quickadmin.responses.fields.content')</th>
                            <td field-key='content'>{!! $response->content !!}</td>
                        </tr>
                        <x-dates-in-show :model="$response" />
                    </table>
                </div>
            </div>

            <p>&nbsp;</p>

            <a href="{{ route('admin.responses.index') }}" class="btn btn-default">@lang('quickadmin.qa_back_to_list')</a>
        </div>
    </div>
@stop
