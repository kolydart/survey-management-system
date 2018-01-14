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
				<ul>
					@foreach ($question->answers as $answer)
						<li @if ($answer == $question->answers->where('order',$question->pivot->answer_id)->first()) class="text-success" @endif>
							{{$answer->text}}
						</li>
					@endforeach
				</ul>
				{{-- {{$question->answered}} --}}
			</li>
		@endforeach
	</ul>
@endsection