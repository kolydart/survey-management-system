{{-- injected: 
    $survey, 
    (if Questionnaire@show): $questionnaire 
    --}}

{{-- chart js --}}
@if (\Route::currentRouteName() == 'admin.surveys.show')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.min.js"></script>
@endif


@if (Route::getCurrentRoute()->getActionMethod() == 'create')
<form action="{{route('frontend.store')}}" 
    method="POST"
    class="form form-horizontal" 
    role="form"
    style="margin-top: 50px; margin-bottom: 30px;"
    id="questionnaire"
    >
{{ csrf_field() }}
<input type="hidden" name="survey_id" id="survey_id" class="form-control" value="{{$survey->id}}">
@endif

<fieldset @if (\Route::getCurrentRoute()->getActionMethod() == 'create') style="font-size: 120%;" @endif>

    {{-- questionnaire title --}}
    <legend id="qst_{{ $questionnaire->id or 'create' }}">{{$survey->title}}</legend>
    {{-- introduction --}}
    <div class="mb-4" style="margin-bottom:40px;">{!! $survey->introduction or '' !!}</div>
    {{-- questions --}}
    @foreach (
        \Route::currentRouteName()=='frontend.create' ? 
            $survey->items()->orderBy('order')->get():
            $survey->items()->where('label','<>','1')->orderBy('order')->get() 
            as $item
        )
        {{-- question --}}
        <div 
            class="form-group"
            id="q_{{$item->question->id ?? ''}}"
            >

            {{-- label --}}
            <label class="col-md-6 {{-- control-label --}}" 
                for="{{str_plural($item->question->answerlist->type ?? '')}}" 
                >
                    {{-- text --}}
                    {!! $item->order !!} {!! $item->question->title ?? '' !!}
                    {{-- info tooltip for checkbox --}}
                    @if ($item->question->answerlist->type == 'checkbox' && Route::getCurrentRoute()->getActionMethod() == 'create')
                        <i data-toggle="tooltip" data-placement="top" title="@lang('Μία ή περισσότερες απαντήσεις')" class="fa fa-info-circle text-info"></i>
                    @endif
            </label>

            {{-- answers --}}
            <div class="col-md-6">
            {{-- report-or-answer begin--}}
            @if (\Route::currentRouteName() == 'admin.surveys.show')
                {{-- report --}}
                @if (\Request::query('rawdata'))
                    @include('partials.answerData')
                @else
                    @include('partials.answerChart')
                @endif
            @else
                {{-- answer --}}
                @foreach ($item->question->answerlist->answers as $answer)
                    <div class="{{$item->question->answerlist->type}} {{-- form-check --}}" >
                        {{-- label --}}
                        <label 
                            class="form-check-label font-weight-normal"
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
                                {{-- show / hide textarea on load/reload --}}
                                <script>
                                    jQuery(document).ready(function($) {
                                        function check(){
                                            /** font-weight-normal|bold */
                                            if ($('input#{{$item->question->id}}_{{$answer->id}}_select').prop('checked')) {
                                                $('span#{{$item->question->id}}_{{$answer->id}}_text')
                                                    .removeClass('font-weight-normal')
                                                    .addClass('font-weight-bold');
                                            }else{
                                                $('span#{{$item->question->id}}_{{$answer->id}}_text')
                                                    .removeClass('font-weight-bold')
                                                    .addClass('font-weight-normal');
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


                            {{-- hide-if-text end --}}
                            @endif
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
                                            }else {
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
            {{-- report-or-answer end--}}
            @endif
            </div>
        </div>
    @endforeach
</fieldset>
    
@if (Route::getCurrentRoute()->getActionMethod() == 'create')
    <button type="submit" 
        class="btn btn-primary btn-lg"
        >
        <i class="fa fa-save"></i>  @lang('Send')
    </button>
</form>

@endif

