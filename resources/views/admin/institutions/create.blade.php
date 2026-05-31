@extends('layouts.app')

@section('title', trans('quickadmin.institutions.title') . ' | ' . trans('quickadmin.qa_create'))

@section('content')
    <h3 class="page-title">@lang('quickadmin.institutions.title')</h3>
    <form action="{{ route('admin.institutions.store') }}" method="POST">@csrf

    <div class="panel panel-default">
        <div class="panel-heading">
            @lang('quickadmin.qa_create')
        </div>
        
        <div class="panel-body">
            <div class="row">
                <div class="col-xs-12 form-group">
                    <label for="title" class="control-label">{{ trans('quickadmin.institutions.fields.title').'*' }}</label>
                    <input type="text" name="title" id="title" value="{{ old('title') }}" class="form-control" placeholder="" required>
                    <p class="help-block"></p>
                    @if($errors->has('title'))
                        <p class="help-block">
                            {{ $errors->first('title') }}
                        </p>
                    @endif
                </div>
            </div>
            
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">
            Surveys
        </div>
        <div class="panel-body">
            <table class="table table-bordered table-striped">
                <thead>
                <tr>
                    <th>@lang('quickadmin.surveys.fields.title')</th>
                        <th>@lang('quickadmin.surveys.fields.alias')</th>
                        
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody id="surveys">
                    @foreach(old('surveys', []) as $index => $data)
                        @include('admin.institutions.surveys_row', [
                            'index' => $index
                        ])
                    @endforeach
                </tbody>
            </table>
            <a href="#" class="btn btn-success pull-right add-new">@lang('quickadmin.qa_add_new')</a>
        </div>
    </div>

    <button type="submit" class="btn btn-danger">{{ trans('quickadmin.qa_save') }}</button>
    </form>
@stop

@section('javascript')
    @parent

    <script type="text/html" id="surveys-template">
        @include('admin.institutions.surveys_row',
                [
                    'index' => '_INDEX_',
                ])
               </script > 

            <script>
        $('.add-new').click(function () {
            var tableBody = $(this).parent().find('tbody');
            var template = $('#' + tableBody.attr('id') + '-template').html();
            var lastIndex = parseInt(tableBody.find('tr').last().data('index'));
            if (isNaN(lastIndex)) {
                lastIndex = 0;
            }
            tableBody.append(template.replace(/_INDEX_/g, lastIndex + 1));
            return false;
        });
        $(document).on('click', '.remove', function () {
            var row = $(this).parentsUntil('tr').parent();
            row.remove();
            return false;
        });
        </script>
@stop