@extends('frontend.master')

@section('menu')
	{{-- @include('frontend.nav') --}}
@endsection

@section('content')
<div class="container">
		{!! $content !!}
</div>

@endsection