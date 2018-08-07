@extends('layouts.app')

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
                            <td field-key='survey'>{{ $questionnaire->survey->title or '' }}</td>
                        </tr>
                        <tr>
                            <th>@lang('quickadmin.surveys.fields.introduction')</th>
                            <td field-key='introduction'>{!! isset($questionnaire->survey) ? $questionnaire->survey->introduction : '' !!}</td>
                        </tr>
                        <tr>
                            <th>@lang('quickadmin.questionnaires.fields.name')</th>
                            <td field-key='name'>{{ $questionnaire->name }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <p>&nbsp;</p>

            <a href="{{ route('admin.questionnaires.index') }}" class="btn btn-default">@lang('quickadmin.qa_back_to_list')</a>
        </div>
    </div>
@stop
