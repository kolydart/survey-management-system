@extends('frontend.master')

@section('content')
<div class="row">
	<div class="col-md-8 col-md-offset-2">
		@include('partials.questionnaireRender')
	</div>
</div>

@endsection


@section('javascript')
	<script>{{!! $survey->javascript !!}}</script>
@endsection