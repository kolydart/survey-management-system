@extends('layout.master')

@section('content')
	@foreach ($surveys as $survey)
		<div class="card col-xl-4 col-lg-6 col-sm-8 col-xs-12">
			<div class="card-header">
	            <h4 class="card-title"> {{$survey->subject}} </h4>
			</div>
	        <div class="card-body">
	            @if ($survey->notes)
		            <p class="card-text"> {{$survey->notes}} </p>
	            @endif
	        </div>
	        <div class="card-footer">
	            <a class="btn btn-secondary my-2" href="{{route('questionnaires.index')}}"> Show questionnaires </a>
	        </div>
		</div>
	@endforeach
@endsection
