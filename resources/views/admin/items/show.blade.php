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
                            <th>@lang('quickadmin.items.fields.survey')</th>
                            <td field-key='survey'>{{ $item->survey->title or '' }}</td>
                        </tr>
                        <tr>
                            <th>@lang('quickadmin.items.fields.question')</th>
                            <td field-key='question'>{{ $item->question->title or '' }}</td>
                        </tr>
                        <tr>
                            <th>@lang('quickadmin.items.fields.label')</th>
                            <td field-key='label'>{{ Form::checkbox("label", 1, $item->label == 1 ? true : false, ["disabled"]) }}</td>
                        </tr>
                        <tr>
                            <th>@lang('quickadmin.items.fields.order')</th>
                            <td field-key='order'>{{ $item->order }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <p>&nbsp;</p>

            <a href="{{ route('admin.items.index') }}" class="btn btn-default">@lang('quickadmin.qa_back_to_list')</a>
        </div>
    </div>
@stop


