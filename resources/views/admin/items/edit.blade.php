@extends('layouts.app')

@section('content')
    <h3 class="page-title">@lang('quickadmin.items.title')</h3>
    
    {!! Form::model($item, ['method' => 'PUT', 'route' => ['admin.items.update', $item->id]]) !!}

    <div class="panel panel-default">
        <div class="panel-heading">
            @lang('quickadmin.qa_edit')
        </div>

        <div class="panel-body">
            <div class="row" id="gw_fld_survey">
                <div class="col-xs-12 form-group">
                    {!! Form::label('survey_id', trans('quickadmin.items.fields.survey').'*', ['class' => 'control-label']) !!}
                    {!! Form::select('survey_id', $surveys, old('survey_id'), ['class' => 'form-control select2', 'required' => '']) !!}
                    <p class="help-block"></p>
                    @if($errors->has('survey_id'))
                        <p class="help-block">
                            {{ $errors->first('survey_id') }}
                        </p>
                    @endif
                </div>
            </div>
            <div class="row" id="gw_fld_question">
                <div class="col-xs-12 form-group">
                    {!! Form::label('question_id', trans('quickadmin.items.fields.question'), ['class' => 'control-label']) !!}
                    {!! Form::select('question_id', $questions, old('question_id'), ['class' => 'form-control select2']) !!}
                    <p class="help-block"></p>
                    @if($errors->has('question_id'))
                        <p class="help-block">
                            {{ $errors->first('question_id') }}
                        </p>
                    @endif
                </div>
            </div>
            <div class="row" id="gw_fld_label">
                <div class="col-xs-12 form-group">
                    {!! Form::label('label', trans('quickadmin.items.fields.label').'', ['class' => 'control-label']) !!}
                    {{-- {!! Form::hidden('label', 0) !!} --}}
                    {!! Form::checkbox('label', 1, old('label', old('label')), []) !!}
                    <p class="help-block">Category label. Do not show content.</p>
                    @if($errors->has('label'))
                        <p class="help-block">
                            {{ $errors->first('label') }}
                        </p>
                    @endif
                </div>
            </div>
            <div class="row" id="gw_fld_order">
                <div class="col-xs-12 form-group">
                    {!! Form::label('order', trans('quickadmin.items.fields.order').'', ['class' => 'control-label']) !!}
                    {!! Form::text('order', old('order'), ['class' => 'form-control', 'placeholder' => '']) !!}
                    <p class="help-block"></p>
                    @if($errors->has('order'))
                        <p class="help-block">
                            {{ $errors->first('order') }}
                        </p>
                    @endif
                </div>
            </div>
            
        </div>
    </div>

    {!! Form::submit(trans('quickadmin.qa_update'), ['class' => 'btn btn-danger']) !!}
    {!! Form::close() !!}
@stop


@section('javascript')
    <script src="/js/item_question.js"></script>
@stop
