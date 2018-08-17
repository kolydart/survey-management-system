{{-- injected: 
    $survey, 
    (if Questionnaire@show): $questionnaire 
    --}}

@if (Route::getCurrentRoute()->getActionMethod() == 'create')
<form action="{{route('public.store')}}" 
    method="POST"
    class="form form-horizontal" 
    role="form"
    style="margin-top: 10px; margin-bottom: 30px;"
    id="questionnaire"
    >
{{ csrf_field() }}
<input type="hidden" name="survey_id" id="survey_id" class="form-control" value="{{$survey->id}}">
@endif

<fieldset>

    {{-- questionnaire title --}}
    <legend id="qst_{{ $questionnaire->id or 'create' }}">{{$survey->title}}</legend>
    {{-- introduction --}}
    <div>{!! $survey->introduction or '' !!}</div>
    {{-- questions --}}
    @foreach ($survey->items as $item)
        {{-- question --}}
        <div 
            class="form-group"
            id="q_{{$item->question->id}}"
            >

            {{-- label --}}
            <label class="col-md-6 {{-- control-label --}}" 
                for="{{str_plural($item->question->answerlist->type)}}" 
                >
                    {{$item->order.". ".$item->question->title}}
            </label>

            {{-- answers --}}
            <div class="col-md-6">
            @foreach ($item->question->answerlist->answers as $answer)
                {{-- answer --}}
                <div class="{{$item->question->answerlist->type}} {{-- form-check --}}" >
                    {{-- label --}}
                    <label 
                        class="form-check-label"
                        style="font-weight: normal;"
                        for="{{$item->question->id}}_{{$answer->id}}_select"
                        >

                        {{-- hide-if-text begin--}}
                        @if($item->question->answerlist->type)
    
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
                                @if ( $questionnaire->is_question_answered($item->question_id,$answer->id) ) 
                                    style="font-weight:bold;"
                                @endif
                                >
                                {{ $answer->title }}
                            </span>

                        {{-- hide-if-text end --}}
                        @endif
                        {{-- textarea response content --}}
                        @if ( 
                            /** display filled */
                            \Route::getCurrentRoute()->getActionMethod() == 'show' 
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

                            {{-- show / hide textarea on load/reload --}}
                            <script>
                                jQuery(document).ready(function($) {
                                    /** show/hide if input == $answer->id */
                                    function check(){
                                        if ($('input#{{$item->question->id}}_{{$answer->id}}_select:checked').val() == {{$answer->id}}) {
                                            $('#{{$item->question->id}}_content_{{$answer->id}}')
                                                .attr('required', true)
                                                .attr('disabled', false)
                                                .show(300);
                                        }
                                        else {
                                            $('#{{$item->question->id}}_content_{{$answer->id}}')
                                                .val('')
                                                .attr('required', false)
                                                .attr('disabled', true)
                                                .hide(300);
                                        }                
                                    };
                                    /** run on first load */
                                    $('#q_{{$item->question->id}} input[name^="{{$item->question->id}}_id"]').ready(function(){
                                        check();
                                    })

                                    /** run on every change */
                                    $('#q_{{$item->question->id}} input[name^="{{$item->question->id}}_id"]').change(function(){
                                        check();
                                    });
                                });
                            </script> 
                        @endif
                    </label>
                </div>
            @endforeach
            </div>
        </div>
    @endforeach
</fieldset>
    
@if (Route::getCurrentRoute()->getActionMethod() == 'create')
    <button type="submit" 
        class="btn btn-primary"
        >
        <i class="fa fa-save"></i>  @lang('Send')
    </button>
</form>

@endif

