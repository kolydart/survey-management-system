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
            </div>

            <p>&nbsp;</p>

            <a href="{{ route('admin.surveys.index') }}" class="btn btn-default">@lang('quickadmin.qa_back_to_list')</a>
        </div>
    </div>
@stop
