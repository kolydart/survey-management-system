@extends('layouts.app')

@section('content')
    <h3 class="page-title">@lang('quickadmin.answers.title')</h3>
    {!! Form::open(['method' => 'POST', 'route' => ['admin.answers.store']]) !!}

    <div class="panel panel-default">
        <div class="panel-heading">
            @lang('quickadmin.qa_create')
        </div>
        
        <div class="panel-body">
            <div class="row">
                <div class="col-xs-12 form-group">
                    {!! Form::label('title', trans('quickadmin.answers.fields.title').'*', ['class' => 'control-label']) !!}
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
                    {!! Form::label('answerlists', trans('quickadmin.answers.fields.answerlists').'*', ['class' => 'control-label']) !!}
                    <button type="button" class="btn btn-primary btn-xs" id="selectbtn-answerlists">
                        {{ trans('quickadmin.qa_select_all') }}
                    </button>
                    <button type="button" class="btn btn-primary btn-xs" id="deselectbtn-answerlists">
                        {{ trans('quickadmin.qa_deselect_all') }}
                    </button>
                    {!! Form::select('answerlists[]', $answerlists, old('answerlists'), ['class' => 'form-control select2', 'multiple' => 'multiple', 'id' => 'selectall-answerlists' , 'required' => '']) !!}
                    <p class="help-block"></p>
                    @if($errors->has('answerlists'))
                        <p class="help-block">
                            {{ $errors->first('answerlists') }}
                        </p>
                    @endif
                </div>
            </div>
            
        </div>
    </div>

    {!! Form::submit(trans('quickadmin.qa_save'), ['class' => 'btn btn-danger']) !!}
    {!! Form::close() !!}
@stop

@section('javascript')
    @parent

    <script>
        $("#selectbtn-answerlists").click(function(){
            $("#selectall-answerlists > option").prop("selected","selected");
            $("#selectall-answerlists").trigger("change");
        });
        $("#deselectbtn-answerlists").click(function(){
            $("#selectall-answerlists > option").prop("selected","");
            $("#selectall-answerlists").trigger("change");
        });
    </script>
@stop