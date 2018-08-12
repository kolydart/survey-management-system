@extends('layouts.app')

@section('content')
    <div class="row">
         <div class="col-md-6">
            <div class="panel panel-default">
                <div class="panel-heading">Recently added responses</div>

                <div class="panel-body table-responsive">
                    <table class="table table-bordered table-striped ajaxTable">
                        <thead>
                        <tr>
                            
                            <th> @lang('quickadmin.responses.fields.content')</th> 
                            <th> @lang('Questionnaire')</th> 
                            <th>&nbsp;</th>
                        </tr>
                        </thead>
                        @foreach($responses as $response)
                            <tr>
                               
                                <td>{{ $response->content }} </td> 
                                <td>
                                    <a href="{{route('admin.questionnaires.show',$response->questionnaire->id)}}">
                                        @if ($response->questionnaire->name && Gate::allows('survey_edit')) 
                                            {{$response->questionnaire->name}} <br>
                                        @endif
                                        {{ $response->questionnaire->id }} - {{$response->questionnaire->survey->title}} 
                                    </a> </td> 
                                <td>

                                    @can('response_view')
                                    <a href="{{ route('admin.responses.show',[$response->id]) }}" class="btn btn-xs btn-primary">@lang('quickadmin.qa_view')</a>
                                    @endcan

                                    @can('response_edit')
                                    <a href="{{ route('admin.responses.edit',[$response->id]) }}" class="btn btn-xs btn-info">@lang('quickadmin.qa_edit')</a>
                                    @endcan

                                    @can('response_delete')
{!! Form::open(array(
                                        'style' => 'display: inline-block;',
                                        'method' => 'DELETE',
                                        'onsubmit' => "return confirm('".trans("quickadmin.qa_are_you_sure")."');",
                                        'route' => ['admin.responses.destroy', $response->id])) !!}
                                    {!! Form::submit(trans('quickadmin.qa_delete'), array('class' => 'btn btn-xs btn-danger')) !!}
                                    {!! Form::close() !!}
                                    @endcan
                                
</td>
                            </tr>
                        @endforeach
                    </table>
                </div>
            </div>
 </div>

 <div class="col-md-6">
            <div class="panel panel-default">
                <div class="panel-heading">Recently added questionnaires</div>

                <div class="panel-body table-responsive">
                    <table class="table table-bordered table-striped ajaxTable">
                        <thead>
                        <tr>
                            
                            <th> @lang('quickadmin.questionnaires.fields.name')</th> 
                            <th>&nbsp;</th>
                        </tr>
                        </thead>
                        @foreach($questionnaires as $questionnaire)
                            <tr>
                               
                                <td>{{ $questionnaire->name }} </td> 
                                <td>

                                    @can('questionnaire_view')
                                    <a href="{{ route('admin.questionnaires.show',[$questionnaire->id]) }}" class="btn btn-xs btn-primary">@lang('quickadmin.qa_view')</a>
                                    @endcan

                                    @can('questionnaire_edit')
                                    <a href="{{ route('admin.questionnaires.edit',[$questionnaire->id]) }}" class="btn btn-xs btn-info">@lang('quickadmin.qa_edit')</a>
                                    @endcan

                                    @can('questionnaire_delete')
{!! Form::open(array(
                                        'style' => 'display: inline-block;',
                                        'method' => 'DELETE',
                                        'onsubmit' => "return confirm('".trans("quickadmin.qa_are_you_sure")."');",
                                        'route' => ['admin.questionnaires.destroy', $questionnaire->id])) !!}
                                    {!! Form::submit(trans('quickadmin.qa_delete'), array('class' => 'btn btn-xs btn-danger')) !!}
                                    {!! Form::close() !!}
                                    @endcan
                                
</td>
                            </tr>
                        @endforeach
                    </table>
                </div>
            </div>
 </div>


    </div>
@endsection

