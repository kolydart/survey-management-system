@extends('layout.master')
@section('content')
	<p class='col-sm-12'>{{$questionnaire->id}}, 
		{{$questionnaire->created_at->toFormattedDateString()}} 
		@if ($questionnaire->name)
			({{$questionnaire->name}})
		@endif
	</p>
	<ul class="list-group">
		@foreach ($questionnaire->questions as $question)
			<li class="list-group-item">
				{{$question->id}}: 
				{{$question->text}}<br>
				{{-- {{App\Answer::where('order',$question->pivot->answer_id)->where('id',$question->id)->first()->text}} --}}
				{{App\Answer::where('order',$question->pivot->answer_id)->where('id',1)->text}}
			</li>
		@endforeach
	</ul>
@endsection