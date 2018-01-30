@extends('layout.master')

@section('content')


@foreach ($surveys as $survey)
<div class="col-lg-4 col-md-6 col-sm-12 my-2">
    <div class="card">
        {{--
        <div class="card-header">
        </div>
        --}}
        <div class="card-body">
            <h4 class="card-title">
                {{$survey->subject}}
            </h4>
            @if ($survey->notes)
            <p class="card-text">
                {{$survey->notes}}
            </p>
            @endif
        </div>
        <div class="card-footer">
            <a class="btn btn-secondary" href="{{route('questionnaires.index',['survey'=>$survey->id])}}">
                Show questionnaires
            </a>
        </div>
    </div>
</div>
@endforeach
@endsection
