<form action="" class="form form-horizontal" role="form">
<fieldset>

{{-- questionnaire title --}}
<legend id="qst{{$questionnaire->id}}">{{$questionnaire->survey->title}}</legend>
<div>{!!$questionnaire->survey->introduction or ''!!}</div>
{{-- questions --}}
@foreach ($questionnaire->survey->items as $item)
<div class="form-group">
    {{-- question --}}
    <label class="col-md-6 {{-- control-label --}}" for="{{str_plural(gateweb\common\Presenter::get_firstWordInString($item->question->answerlist->type))}}" id="q{{$item->question->id}}">
        {{$item->order.". ".$item->question->title}}
    </label>
    <div class="col-md-6">
    {{-- answers --}}
    @foreach ($item->question->answerlist->answers as $answer)
        {{-- type div--}}
        <div class="{{gateweb\common\Presenter::get_firstWordInString($item->question->answerlist->type)}} form-check" >

            {{-- answer --}}
            <label class="form-check-label" style="font-weight: normal;">
                
                {{-- input --}}
                <input 
                    type="{{gateweb\common\Presenter::get_firstWordInString($item->question->answerlist->type)}}" 
                    class="form-check-input" 
                    id="a{{$answer->id}}"
                    value="a{{$answer->id}}" 

                    {{-- disable input on show/index --}}
                    @if (\Route::getCurrentRoute()->getActionMethod() != 'edit')
                        disabled 
                    @endif
                    
                    {{-- is checked --}}
                    @if ( $questionnaire->responses->where('answer_id',$answer->id)->where('question_id',$item->question->id)->count() )
                        checked
                    @endif
                >
                
                {{-- label text --}}
                <span @if ( $questionnaire->responses->where('answer_id',$answer->id)->where('question_id',$item->question->id)->count() ) style="font-weight:bold;"@endif>
                    {{ $answer->title }}
                </span>

                {{-- response content --}}
                @if ( !empty($questionnaire->responses->where('answer_id',$answer->id)->where('question_id',$item->question->id)->first()->content) )
                    <br>{{$questionnaire->responses->where('answer_id',$answer->id)->where('question_id',$item->question->id)->first()->content or ''}}
                    {{-- @todo --}}
                @elseif (false)
                    <textarea 
                        name="content" 
                        id="c{{$answer->id}}" 
                        class="form-control" 
                        rows="5" 
                        required="required" 
                        placeholder=""
                        ></textarea>
                @endif

            </label>
        </div>
    @endforeach
    </div>
</div>
@endforeach
</fieldset>
</form>
