{{--
injected models: 
$survey, 
(if Questionnaire@show): $questionnaire 

used in three routes:
frontend.create
admin.surveys.show
admin.questionnaires.show
--}}


{{-- form tag; only in frontend.create route --}}
@if (\Route::currentRouteName() == 'frontend.create')
<form action="{{route('frontend.store')}}" 
    method="POST"
    class="form form-horizontal gw-form" 
    style="margin-top: 50px; margin-bottom: 30px;"
    id="questionnaire"
    >
{{ csrf_field() }}
<input type="hidden" name="survey_id" id="survey_id" class="form-control" value="{{$survey->id}}">
@endif

{{-- fields list (in all routes) --}}
<fieldset @if (\Route::currentRouteName() == 'frontend.create') class="gw-fieldset" @endif>

    {{-- questionnaire title --}}
    <legend id="qst_{{ $questionnaire->id or 'create' }}">{{$survey->title}}</legend>

    {{-- introduction --}}
    <div class="mb-4 gw-introduction">{!! $survey->introduction or '' !!}</div>
    
    {{-- items --}}
    @foreach ( $survey->items()->orderBy('order')->get() as $item)

        {{-- item --}}
        <section class="form-group gw-item q_{{$item->question->id ?? ''}} row" id="i_{{$item->id ?? ''}}" style="page-break-inside:avoid;">
            {{-- label (question) --}}
            <label 
                class="@if (\Route::currentRouteName() == 'admin.surveys.show' && !$item->label ) col-md-6 col-lg-8 col-lg-offset-2 @else col-xs-12 @endif {{-- control-label --}} gw-label @if ($item->label) bg-primary gw-item-label @endif ">

                {{-- debug --}}
                {{-- @if ($survey->id == 2023 and Auth::check() )
                    i_{{$item->id ?? ''}} (q_{{$item->question->id ?? ''}})
                @endif --}}

                {{-- question text --}}
                {!! $item->order !!} {!! $item->question->title ?? '' !!}
            </label>

            {{-- answer(s) --}}

                {{-- item is label; display no question --}}
                @if( $item->label )

                {{-- answerlist type == radio|checkbox --}}
                @elseif ($item->question->answerlist->type == 'radio' || $item->question->answerlist->type == 'checkbox')

                    <div class="gw-answers @if (\Route::currentRouteName() == 'admin.surveys.show') col-md-6 col-lg-6 col-lg-offset-3 @else col-xs-10 col-xs-offset-1 @endif ">

                    {{-- report-or-answer begin--}}
                        {{-- if report, just show chart --}}
                        @if (\Route::currentRouteName() == 'admin.surveys.show')
                            @if ($item->label != 1)
                                {{-- report --}}
                                @if (\Request::query('rawdata')) @include('partials.answerData') @else @include('partials.answerChart') @endif
                            @endif

                        @else
                            {{-- answer --}}
                            @foreach ($item->question->answerlist->answers as $answer)
                                <div class="{{$item->question->answerlist->type}} {{-- form-check --}} gw-answer" >
                                    {{-- label --}}
                                    <label 
                                        class="form-check-label font-weight-normal"
                                        for="{{$item->question->id}}_{{$answer->id}}_select"
                                        >

                                            {{-- input --}}
                                            <input 
                                                type="{{$item->question->answerlist->type}}" 
                                                class="form-check-input" 
                                                id="{{$item->question->id}}_{{$answer->id}}_select"
                                                
                                                {{-- name --}}
                                                @if($item->question->answerlist->type == 'checkbox')
                                                    name="{{$item->question->id}}_id_{{$answer->id}}"
                                                @else
                                                    name="{{$item->question->id}}_id"
                                                @endif

                                                value="{{$answer->id}}" 

                                                {{-- disable input on show/index --}}
                                                @if (\Route::currentRouteName() != 'frontend.create')
                                                    disabled = "disabled"
                                                @endif
                                                
                                                {{-- is checked --}}
                                                @if ( 
                                                        /** display filled questionnaire */
                                                        (
                                                            \Route::currentRouteName() != 'frontend.create' 
                                                            && isset($questionnaire)
                                                            && $questionnaire->is_question_answered($item->question_id,$answer->id)
                                                        ) ||
                                                        /** radio input returning from error */
                                                        (
                                                            \Route::currentRouteName() == 'frontend.create' 
                                                            && $item->question->answerlist->type == 'radio'
                                                            && old($item->question->id.'_id') == $answer->id
                                                        ) ||
                                                        /** checkbox input returning from error */
                                                        (
                                                            \Route::currentRouteName() == 'frontend.create' 
                                                            && $item->question->answerlist->type == 'checkbox'
                                                            && old($item->question->id.'_id_'.$answer->id) == $answer->id
                                                        ) 
                                                    )
                                                    checked="checked"
                                                @endif
                                            >
                                            
                                            {{-- label text --}}
                                            <span 
                                                id="{{$item->question->id}}_{{$answer->id}}_text"
                                                class="@if ( isset($questionnaire) && $questionnaire->is_question_answered($item->question_id,$answer->id) ) font-weight-bold @endif "
                                                >

                                                {{-- debug --}}
                                                {{-- @if ($survey->id == 2023 and Auth::check())
                                                    {{$item->question->id}}_{{$answer->id}}_select
                                                @endif --}}

                                                {{ $answer->title }}
                                            </span>
                                            
                                            @include('partials.js.boldOnSelect')
                                    </label>

                                        {{-- textarea response content --}}
                                        @if ( 
                                            /** display filled */
                                            \Route::currentRouteName() == 'admin.questionnaires.show'
                                            && !empty($questionnaire->responses->where('answer_id',$answer->id)->where('question_id',$item->question->id)->first()->content) 
                                            )
                                            <br>
                                            {{$questionnaire->responses->where('answer_id',$answer->id)->where('question_id',$item->question_id)->first()->content or ''}}
                                        @elseif (
                                            /** create new */
                                            \Route::currentRouteName() == 'frontend.create'
                                            && $answer->open == 1
                                            )
                                            <textarea
                                                name="{{$item->question->id}}_content_{{$answer->id}}" 
                                                id="{{$item->question->id}}_content_{{$answer->id}}" 
                                                class="form-control" 
                                                rows="5" 
                                                placeholder=""
                                                required="required"
                                                >{{old($item->question->id.'_content_'.$answer->id, '')}}</textarea>

                                            @include('partials.js.toggleTextarea')
                                        
                                        @endif
                                </div>
                            @endforeach

                        @endif
                    {{-- report-or-answer end--}}
                        
                    {{-- info tooltip for checkbox --}}
                        @if ($item->question->answerlist->type == 'checkbox' && \Route::currentRouteName() == 'frontend.create')
                            <i class="fa fa-info-circle text-muted"></i> <small class="text-muted">@lang('Επιλέξτε όσα ισχύουν')</small>
                        @endif

                    </div>

                    {{-- display any content value in admin.surveys.show --}}
                    @if (
                        \Route::currentRouteName() == 'admin.surveys.show' && 
                        App\Response::whereIn('questionnaire_id',$item->survey->questionnaires->pluck('id'))->where('question_id',$item->question_id)->where('content','!=',"")
                    )
                        <div class="col-md-6 col-lg-6 col-lg-offset-3">
                            <table class="table table-condensed table-hover table-bordered text-muted small">
                                {{-- <thead> <tr> <th>Προσαρμοσμένες τιμές</th></tr></thead> --}}
                                <tbody>
                                    @foreach (App\Response::whereIn('questionnaire_id',$item->survey->questionnaires->pluck('id'))->where('question_id',$item->question_id)->where('content','!=',"")->pluck('content')->toArray() as $row)
                                        <tr><td>•</td><td>{{$row}}</td> </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif 

                {{-- answerlist type == any single value (hidden_answer) --}}
                @else

                {{-- single value by route begin --}}

                    {{-- frontend.create route --}}
                    @if (\Route::currentRouteName()=='frontend.create')
                        {{-- hidden value id --}}
                        <input type="hidden" class="hidden" id="{{$item->question->id}}_{{$hidden_answer->id}}_value" name="{{$item->question->id}}_id" value="{{$hidden_answer->id}}" >

                        @if ($item->question->answerlist->type == 'text')
                            {{-- text only: textarea --}}
                            <textarea
                                name="{{$item->question->id}}_content_{{$hidden_answer->id}}" 
                                id="{{$item->question->id}}_content_{{$hidden_answer->id}}" 
                                class="col-md-6 col-lg-6 col-lg-offset-3"
                                rows="4"
                                required="required"
                            >{{old($item->question->id.'_content_'.$hidden_answer->id)}}</textarea>

                        @else
                            {{-- the rest: input --}}
                            <input 
                                type="{{$item->question->answerlist->type}}"
                                name="{{$item->question->id}}_content_{{$hidden_answer->id}}" 
                                id="{{$item->question->id}}_content_{{$hidden_answer->id}}" 
                                class="col-md-6 col-lg-6 col-lg-offset-3"
                                value="{{old($item->question->id.'_content_'.$hidden_answer->id)}}"
                                required="required"
                            >                        
                        @endif
                

                    {{-- admin.surveys.show, admin.questionnaires.show --}}
                    @else
                        {{-- display results (table, or csv) --}}
                        <div class="card col-md-6 col-lg-6 col-lg-offset-3">
                            @if (in_array($item->question->answerlist->type,["text"]) )
                                <table class="table table-condensed table-hover table-bordered">
                                    <tbody>
                                        @foreach (App\Response::whereIn('questionnaire_id',$item->survey->questionnaires->pluck('id'))
                                                ->where('question_id',$item->question_id)->pluck('content')->toArray() as $row)
                                            <tr><td>{{$row}}</td> </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @else
                                <div class="border" style="border-style: solid; border-color: #EDEDED; border-radius: 5px; border-width: thin; padding: 5px;">
                                    {{implode(
                                        ", ",
                                        App\Response::whereIn('questionnaire_id',$item->survey->questionnaires->pluck('id'))->where('question_id',$item->question_id)->pluck('content')->toArray()
                                    )}}
                                    
                                </div>
                            @endif
                        </div>
                        {{-- statistics --}}
                        @if (in_array($item->question->answerlist->type,["number", "range", "date", "time", "datetime-local", "week", "month"]) )
                            <div class="col-md-6 col-lg-6 col-lg-offset-3">
                                @php $array=App\Response::whereIn('questionnaire_id',$item->survey->questionnaires->pluck('id'))
                                                    ->where('question_id',$item->question_id)->pluck('content')->toArray(); @endphp
                                    min: {{min($array)}},
                                    max: {{max($array)}},
                                    mean: {{round(array_sum($array)/count($array),2)}},
                                    count: {{count($array)}},
                                    {{-- std: {{stats_standard_deviation($array)}}, --}}
                                    @if (in_array($item->question->answerlist->type,["number", "range"]))
                                        @php
                                            // calculate median
                                            sort($array);
                                            $count = count($array);
                                            $middle_value = floor(($count-1)/2);
                                            if ($count % 2) {
                                                $median = $array[$middle_value];
                                            } else {
                                                $low = $array[$middle_value];
                                                $high = $array[$middle_value+1];
                                                $median = (($low+$high)/2);
                                            }
                                        @endphp
                                        median: {{$median}}
                                    @endif
                                </div>
                            @endif
                    @endif

                {{-- single value by route end --}}

                @endif

            {{-- answers end --}}

        </section>
        {{-- item end --}}

    @endforeach
</fieldset>

{{-- close form tag --}}
@if (\Route::currentRouteName() == 'frontend.create')
    <button type="submit" 
        class="btn btn-success btn-lg"
        >
        <i class="fa fa-save"></i>  @lang('Send')
    </button>
</form>
@endif

{{-- chart js --}}
@if (\Route::currentRouteName() == 'admin.surveys.show')
    @section('head')
        <script src="/js/chart.2.5.0.min.js"></script>
    @endsection
@endif