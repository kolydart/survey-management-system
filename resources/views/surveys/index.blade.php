@extends('layout.master')

@section('content')
	<ul>
		@foreach ($surveys as $survey)
			<li>{{$survey->subject}}, from {{$survey->date_begin}} to </li>
		@endforeach
	</ul>
		
@endsection