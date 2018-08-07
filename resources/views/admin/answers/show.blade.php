@extends('layouts.app')

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
                            <th>@lang('quickadmin.answers.fields.answerlists')</th>
                            <td field-key='answerlists'>
                                @foreach ($answer->answerlists as $singleAnswerlists)
                                    <span class="label label-info label-many">{{ $singleAnswerlists->title }}</span>
                                @endforeach
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

            <p>&nbsp;</p>

            <a href="{{ route('admin.answers.index') }}" class="btn btn-default">@lang('quickadmin.qa_back_to_list')</a>
        </div>
    </div>
@stop
