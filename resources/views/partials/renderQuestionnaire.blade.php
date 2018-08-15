{{-- injected: 
    $survey, 
    (if rendering filled) 
    $questionnaire 
    --}}

@if (Route::getCurrentRoute()->getActionMethod() == 'create')
<form action="{{route('public.store')}}" 
    method="POST"
    class="form form-horizontal" 
    role="form"
    style="margin-top: 10px; margin-bottom: 30px;"
    >
{{ csrf_field() }}
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
                        
                        {{-- input --}}
                        <input 
                            type="{{$item->question->answerlist->type}}" 
                            class="form-check-input" 
                            id="{{$item->question->id}}_{{$answer->id}}_select"
                            name="{{$item->question->id}}[id]"
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
                                        && old($item->question->id['id']) == $answer->id
                                    ) ||
                                    /** radio input returning from error */
                                    (
                                        \Route::getCurrentRoute()->getActionMethod() == 'create' 
                                        && $item->question->answerlist->type == 'checkbox'
                                        && old($item->question->id['id'][$answer->id]) == $answer->id
                                    ) 
                                )

                                checked="checked"

                            @endif
                        >
                        
                        {{-- label text --}}
                        <span @if ( $questionnaire->is_question_answered($item->question_id,$answer->id) ) style="font-weight:bold;"@endif>
                            {{ $answer->title }}
                        </span>

                        {{-- response content --}}
                        @if ( 
                            \Route::getCurrentRoute()->getActionMethod() == 'show' && 
                            !empty($questionnaire->responses->where('answer_id',$answer->id)->where('question_id',$item->question->id)->first()->content) 
                            )
                            <br>{{$questionnaire->responses->where('answer_id',$answer->id)->where('question_id',$item->question->id)->first()->content or ''}}
                            {{-- @todo --}}
                        {{-- @elseif (\Route::getCurrentRoute()->getActionMethod() == 'create')
                            <textarea 
                                name="content" 
                                id="c{{$answer->id}}" 
                                class="form-control hide" 
                                rows="5" 
                                required="required" 
                                placeholder=""
                                name="{{$item->question->id}}[{{$answer->id}}][content]"
                                ></textarea> --}}
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
