@extends('layout.master')

@section('content')
	<ul class="list-group">
	@foreach ($questionnaires as $questionnaire)
		<p class="list-group-item"><a href="{{route('questionnaires.show',$questionnaire)}}">
			{{$questionnaire->id}}, {{$questionnaire->created_at->toFormattedDateString()}} 
			@if ($questionnaire->name)
				({{$questionnaire->name}})
			@endif
		</a></p>
		<div class="list-group">
		</div>
	@endforeach
	</ul>
@endsection