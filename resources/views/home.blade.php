@extends('layouts.app')

@section('content')
    <div class="row">


{{-- Recent surveys --}}
<div class="col-md-6">
   <div class="panel panel-default">
       <div class="panel-heading">Recent (10) surveys</div>
       <div class="panel-body table-responsive">
           <table class="table table-bordered table-striped ajaxTable">
               <thead>
               <tr>
                   <th> @lang('id')</th> 
                   <th> @lang('Title')</th> 
                   <th> @lang('Replies')</th>
               </tr>
               </thead>
               @foreach(App\Survey::latest()->take(10)->get() as $survey)
                   <tr>
                      
                       <td><a href="{{ route('admin.surveys.show',$survey->id) }}">{{$survey->id}}</a></td> 
                       <td> {{ $survey->title ?? '' }} </td> 
                       <td> {{ $survey->questionnaires->count() ?? '' }} </td> 
                   </tr>
               @endforeach
           </table>
       </div>
   </div>
</div>



{{-- Recently added questionnaires --}}
<div class="col-md-6">
   <div class="panel panel-default">
       <div class="panel-heading">Recently added questionnaires</div>
       <div class="panel-body table-responsive">
           <table class="table table-bordered table-striped ajaxTable">
               <thead>
               <tr>
                   <th> @lang('id')</th> 
                   <th> @lang('Date')</th> 
                   <th> @lang('Survey')</th> 
               </tr>
               </thead>
               @foreach($questionnaires as $questionnaire)
                   <tr>
                      
                       <td><a href="{{ route('admin.questionnaires.show',$questionnaire->id) }}">{{$questionnaire->id}}</a></td> 
                       <td> {{ $questionnaire->created_at->toFormattedDateString() }} </td> 
                       <td> {{ $questionnaire->survey->title }} </td> 
                   </tr>
               @endforeach
           </table>
       </div>
   </div>
</div>


{{-- Recently added responses --}}
 <div class="col-md-6">
    <div class="panel panel-default">
        <div class="panel-heading">Recently added text responses</div>

        <div class="panel-body table-responsive">
            <table class="table table-bordered table-striped ajaxTable">
                <thead>
                <tr>
                    
                    <th> @lang('quickadmin.responses.fields.content')</th> 
                    <th> @lang('Questionnaire')</th> 
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
                            </a> 
                        </td> 
                    </tr>
                @endforeach
            </table>
        </div>
    </div>
 </div>

    </div>
@endsection

