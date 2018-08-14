@extends('layouts.app')

@section('content')
    <h3 class="page-title">@lang('quickadmin.answerlists.title')</h3>
    
    {!! Form::model($answerlist, ['method' => 'PUT', 'route' => ['admin.answerlists.update', $answerlist->id]]) !!}

    <div class="panel panel-default">
        <div class="panel-heading">
            @lang('quickadmin.qa_edit')
        </div>

        <div class="panel-body">
            <div class="row">
                <div class="col-xs-12 form-group">
                    {!! Form::label('title', trans('quickadmin.answerlists.fields.title').'*', ['class' => 'control-label']) !!}
                    {!! Form::text('title', old('title'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) !!}
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
                    {!! Form::label('type', trans('quickadmin.answerlists.fields.type').'*', ['class' => 'control-label']) !!}
                    <p class="help-block"></p>
                    @if($errors->has('type'))
                        <p class="help-block">
                            {{ $errors->first('type') }}
                        </p>
                    @endif
                    <div>
                        <label>
                            {!! Form::radio('type', 'radio', false, ['required' => '']) !!}
                            radio
                        </label>
                    </div>
                    <div>
                        <label>
                            {!! Form::radio('type', 'radio + text', false, ['required' => '']) !!}
                            radio + text
                        </label>
                    </div>
                    <div>
                        <label>
                            {!! Form::radio('type', 'checkbox', false, ['required' => '']) !!}
                            checkbox
                        </label>
                    </div>
                    <div>
                        <label>
                            {!! Form::radio('type', 'checkbox + text', false, ['required' => '']) !!}
                            checkbox + text
                        </label>
                    </div>
                    <div>
                        <label>
                            {!! Form::radio('type', 'text', false, ['required' => '']) !!}
                            text
                        </label>
                    </div>
                    
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 form-group">
                    {!! Form::label('answers', trans('quickadmin.answerlists.fields.answers').'*', ['class' => 'control-label']) !!}
                    <button type="button" class="btn btn-primary btn-xs" id="selectbtn-answers">
                        {{ trans('quickadmin.qa_select_all') }}
                    </button>
                    <button type="button" class="btn btn-primary btn-xs" id="deselectbtn-answers">
                        {{ trans('quickadmin.qa_deselect_all') }}
                    </button>
                    {!! Form::select('answers[]', $answers, old('answers') ? old('answers') : $answerlist->answers->pluck('id')->toArray(), ['class' => 'form-control select2', 'multiple' => 'multiple', 'id' => 'selectall-answers' , 'required' => '']) !!}
                    <p class="help-block"></p>
                    @if($errors->has('answers'))
                        <p class="help-block">
                            {{ $errors->first('answers') }}
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
                    @forelse(old('questions', []) as $index => $data)
                        @include('admin.answerlists.questions_row', [
                            'index' => $index
                        ])
                    @empty
                        @foreach($answerlist->questions as $item)
                            @include('admin.answerlists.questions_row', [
                                'index' => 'id-' . $item->id,
                                'field' => $item
                            ])
                        @endforeach
                    @endforelse
                </tbody>
            </table>
            <a href="#" class="btn btn-success pull-right add-new">@lang('quickadmin.qa_add_new')</a>
        </div>
    </div>

    {!! Form::submit(trans('quickadmin.qa_update'), ['class' => 'btn btn-danger']) !!}
    {!! Form::close() !!}
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
@stop