{{-- injected: 
    $survey, 
    (if Questionnaire@show): $questionnaire 
    --}}

{{-- form tag only on create --}}
@if (Route::getCurrentRoute()->getActionMethod() == 'create')
<form action="{{route('frontend.store')}}" 
    method="POST"
    class="form form-horizontal gw-form" 
    role="form"
    style="margin-top: 50px; margin-bottom: 30px;"
    id="questionnaire"
    >
{{ csrf_field() }}
<input type="hidden" name="survey_id" id="survey_id" class="form-control" value="{{$survey->id}}">
@endif

{{-- fields everywhere --}}
<fieldset @if (\Route::getCurrentRoute()->getActionMethod() == 'create') class="gw-fieldset" @endif>

    {{-- questionnaire title --}}
    <legend id="qst_{{ $questionnaire->id or 'create' }}">{{$survey->title}}</legend>

    {{-- introduction --}}
    <div class="mb-4 gw-introduction">{!! $survey->introduction or '' !!}</div>
    
    {{-- questions as item --}}
    @foreach (
        \Route::currentRouteName()=='frontend.create' ? 
            $survey->items()->orderBy('order')->get():
            $survey->items()->where('label','<>','1')->orderBy('order')->get() 
            as $item
        )

        {{-- item --}}
        <div class="form-group gw-item" id="q_{{$item->question->id ?? ''}}">

            {{-- label --}}
            <label 
                class="
                    col-xs-12 
                    {{-- control-label --}}
                    gw-label

                    @if ($item->label)
                        bg-primary
                        gw-item-label
                    @endif
                " 
                for="{{str_plural($item->question->answerlist->type ?? '')}}" 
                >
                    {{-- text --}}
                    {!! $item->order !!} {!! $item->question->title ?? '' !!}
            </label>

            {{-- answers --}}

            {{-- hide answers if question is "null" (3392) --}}
            @if( $item->question->id != 3392 ) {{-- @todo, remove custom id --}}

            <div class="col-xs-10 col-xs-offset-1 gw-answers">

                {{-- report-or-answer begin--}}
                {{-- if report, just show chart --}}
                @if (\Route::currentRouteName() == 'admin.surveys.show')
                    {{-- report --}}
                    @if (\Request::query('rawdata')) @include('partials.answerData') @else @include('partials.answerChart') @endif

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
                                        @if (\Route::getCurrentRoute()->getActionMethod() != 'create')
                                            disabled = "disabled"
                                        @endif
                                        
                                        {{-- is checked --}}
                                        @if ( 
                                                /** display filled questionnaire */
                                                (
                                                    \Route::getCurrentRoute()->getActionMethod() != 'create' 
                                                    && isset($questionnaire)
                                                    && $questionnaire->is_question_answered($item->question_id,$answer->id)
                                                ) ||
                                                /** radio input returning from error */
                                                (
                                                    \Route::getCurrentRoute()->getActionMethod() == 'create' 
                                                    && $item->question->answerlist->type == 'radio'
                                                    && old($item->question->id.'_id') == $answer->id
                                                ) ||
                                                /** checkbox input returning from error */
                                                (
                                                    \Route::getCurrentRoute()->getActionMethod() == 'create' 
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
                                        {{ $answer->title }}
                                    </span>
                                    
                                    @include('partials.js.boldOnSelect')

                                {{-- textarea response content --}}
                                @if ( 
                                    /** display filled */
                                    \Route::getCurrentRoute()->getActionMethod() == 'show' 
                                    && isset($questionnaire)
                                    && !empty($questionnaire->responses->where('answer_id',$answer->id)->where('question_id',$item->question->id)->first()->content) 
                                    )
                                    <br>
                                    {{$questionnaire->responses->where('answer_id',$answer->id)->where('question_id',$item->question_id)->first()->content or ''}}
                                @elseif (
                                    /** create new */
                                    \Route::getCurrentRoute()->getActionMethod() == 'create'
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
                            </label>
                        </div>
                    @endforeach
                {{-- report-or-answer end--}}
                @endif
                
                {{-- info tooltip for checkbox --}}
                @if ($item->question->answerlist->type == 'checkbox' && Route::getCurrentRoute()->getActionMethod() == 'create')
                    <i class="fa fa-info-circle text-muted"></i> <small class="text-muted">@lang('Επιλέξτε όσα ισχύουν')</small>
                @endif

            </div>

            {{-- end hide if null --}}
            @endif
            
            {{-- answers end --}}
        </div>

    @endforeach
</fieldset>

{{-- close form tag (on create) --}}
@if (Route::getCurrentRoute()->getActionMethod() == 'create')
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
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.min.js"></script>
    @endsection
@endif