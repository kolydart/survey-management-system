@extends('public.master')

@section('menu')
	@include('public.nav')
@endsection

@section('content')
<div class="container">
		{!! $content !!}
</div>

@endsection