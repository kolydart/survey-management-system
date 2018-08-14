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
                            <td field-key='survey'><a href="{{route('admin.surveys.show',$item->survey->id)}}">{{ $item->survey->title or '' }}</a></td>
                        </tr>
                        <tr>
                            <th>@lang('quickadmin.items.fields.question')</th>
                            <td field-key='question'><a href="{{route('admin.questions.show',$item->question->id)}}">{{ $item->question->title or '' }}</a></td>
                        </tr>
                        <tr>
                            <th>@lang('answerlist')</th>
                            <td field-key='answerlist'><a href="{{route('admin.answerlists.show',$item->question->answerlist->id)}}">{{ $item->question->answerlist->title or '' }}</a></td>
                        </tr>
                        {!! gateweb\common\presenter\Laraview::dates_in_show($item) !!}                        
                    </table>
                </div>
            </div>

            <p>&nbsp;</p>

            <a href="{{ route('admin.items.index') }}" class="btn btn-default">@lang('quickadmin.qa_back_to_list')</a>
        </div>
    </div>
@stop
