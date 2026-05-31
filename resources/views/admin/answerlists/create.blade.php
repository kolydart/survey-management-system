@extends('layouts.app')

@section('title', trans('quickadmin.answerlists.title') . ' | ' . trans('quickadmin.qa_create'))

@section('content')
    <h3 class="page-title">@lang('quickadmin.answerlists.title')</h3>
    <form action="{{ route('admin.answerlists.store') }}" method="POST">@csrf

    <div class="panel panel-default">
        <div class="panel-heading">
            @lang('quickadmin.qa_create')
        </div>
        
        <div class="panel-body">
            <div class="row">
                <div class="col-xs-12 form-group">
                    <label for="title" class="control-label">{{ trans('quickadmin.answerlists.fields.title').'*' }}</label>
                    <input type="text" name="title" id="title" value="{{ old('title') }}" class="form-control" placeholder="" required>
                    <p class="help-block"></p>
                    @if($errors->has('title'))
                        <p class="help-block">
                            {{ $errors->first('title') }}
                        </p>
                    @endif
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 form-group">
                    <label for="type" class="control-label">{{ trans('quickadmin.answerlists.fields.type').'*' }}</label>
                    <p class="help-block"></p>
                    @if($errors->has('type'))
                        <p class="help-block">
                            {{ $errors->first('type') }}
                        </p>
                    @endif
                    <div>
                        <label>
                            <input type="radio" name="type" value="radio" {{ old('type') == 'radio' ? 'checked' : '' }} required>
                            radio
                        </label>
                    </div>
                    <div>
                        <label>
                            <input type="radio" name="type" value="checkbox" {{ old('type') == 'checkbox' ? 'checked' : '' }} required>
                            checkbox
                        </label>
                    </div>
                    <div>
                        <label>
                            <input type="radio" name="type" value="text" {{ old('type') == 'text' ? 'checked' : '' }} required>
                            text
                        </label>
                    </div>
                    <div>
                        <label>
                            <input type="radio" name="type" value="number" {{ old('type') == 'number' ? 'checked' : '' }} required>
                            number (integer)
                        </label>
                    </div>
                    <div>
                        <label>
                            <input type="radio" name="type" value="date" {{ old('type') == 'date' ? 'checked' : '' }} required>
                            date
                        </label>
                    </div>
                    <div>
                        <label>
                            <input type="radio" name="type" value="time" {{ old('type') == 'time' ? 'checked' : '' }} required>
                            time
                        </label>
                    </div>
                    <div>
                        <label>
                            <input type="radio" name="type" value="datetime-local" {{ old('type') == 'datetime-local' ? 'checked' : '' }} required>
                            datetime
                        </label>
                    </div>
                    <div>
                        <label>
                            <input type="radio" name="type" value="week" {{ old('type') == 'week' ? 'checked' : '' }} required>
                            week
                        </label>
                    </div>
                    <div>
                        <label>
                            <input type="radio" name="type" value="month" {{ old('type') == 'month' ? 'checked' : '' }} required>
                            month
                        </label>
                    </div>
                    <div>
                        <label>
                            <input type="radio" name="type" value="email" {{ old('type') == 'email' ? 'checked' : '' }} required>
                            email
                        </label>
                    </div>
                    <div>
                        <label>
                            <input type="radio" name="type" value="url" {{ old('type') == 'url' ? 'checked' : '' }} required>
                            url
                        </label>
                    </div>
                    <div>
                        <label>
                            <input type="radio" name="type" value="tel" {{ old('type') == 'tel' ? 'checked' : '' }} required>
                            telephone
                        </label>
                    </div>
                    <div>
                        <label>
                            <input type="radio" name="type" value="password" {{ old('type') == 'password' ? 'checked' : '' }} required>
                            password (no special chars)
                        </label>
                    </div>
                    <div>
                        <label>
                            <input type="radio" name="type" value="range" {{ old('type') == 'range' ? 'checked' : '' }} required>
                            range
                        </label>
                    </div>
                    <div>
                        <label>
                            <input type="radio" name="type" value="color" {{ old('type') == 'color' ? 'checked' : '' }} required>
                            color
                        </label>
                    </div>
                    
                </div>
            </div>
            <div class="row" id="gw_answers">
                <div class="col-xs-12 form-group">
                    <label for="answers" class="control-label">{{ trans('quickadmin.answerlists.fields.answers').'*' }}</label>
                    <button type="button" class="btn btn-primary btn-xs" id="selectbtn-answers">
                        {{ trans('quickadmin.qa_select_all') }}
                    </button>
                    <button type="button" class="btn btn-primary btn-xs" id="deselectbtn-answers">
                        {{ trans('quickadmin.qa_deselect_all') }}
                    </button>
                    @php $__selectedAnswers = old('answers'); @endphp
                    <select name="answers[]" id="selectall-answers" class="form-control select2" multiple>
                        @foreach($answers as $key => $label)
                            <option value="{{ $key }}" {{ (is_array($__selectedAnswers) && in_array($key, $__selectedAnswers)) ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    <p class="help-block"></p>
                    @if($errors->has('answers'))
                        <p class="help-block">
                            {{ $errors->first('answers') }}
                        </p>
                    @endif
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 form-group">
                    <label for="remove_unused">
                        {{ trans('quickadmin.answerlists.fields.remove_unused') }}
                        <input type="checkbox" name="remove_unused" value="1">
                    </label>
                    <p class="help-block"></p>
                    @if($errors->has('remove_unused'))
                        <p class="help-block">
                            {{ $errors->first('remove_unused') }}
                        </p>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">
            Questions
        </div>
        <div class="panel-body">
            <table class="table table-bordered table-striped">
                <thead>
                <tr>
                    <th>@lang('quickadmin.questions.fields.title')</th>
                        
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody id="questions">
                    @foreach(old('questions', []) as $index => $data)
                        @include('admin.answerlists.questions_row', [
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

    <script type="text/html" id="questions-template">
        @include('admin.answerlists.questions_row',
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
    <script>
        $("#selectbtn-answers").click(function(){
            $("#selectall-answers > option").prop("selected","selected");
            $("#selectall-answers").trigger("change");
        });
        $("#deselectbtn-answers").click(function(){
            $("#selectall-answers > option").prop("selected","");
            $("#selectall-answers").trigger("change");
        });
    </script>
    <script>
        var hidden_answer_id = {{$hidden_answer->id}};
    </script>
    <script src="/js/answerlist_answer.js"></script>
@stop