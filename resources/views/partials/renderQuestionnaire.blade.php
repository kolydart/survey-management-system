<form action="" class="form-horizontal" role="form">
<div class="form-group" id="qst{{$questionnaire->id}}">
    <legend>{{$questionnaire->survey->title}}</legend>
</div>

{{-- questions --}}
@foreach ($questionnaire->survey->items as $item)
{{-- form-group --}}
<div class="form-group" id="q{{$item->question->id}}">
    {{-- question title --}}
    <strong>{{$item->order.". ".$item->question->title}}</strong>

    {{-- answers --}}
    @foreach ($item->question->answerlist->answers as $answer)
        {{-- type --}}
        <div class="{{gateweb\common\Presenter::get_firstWordInString($item->question->answerlist->type)}} form-check" id="a{{$answer->id}}">

            {{-- label --}}
            <label class="form-check-label" style="font-weight: normal;">

                {{-- input --}}
                <input type="{{gateweb\common\Presenter::get_firstWordInString($item->question->answerlist->type)}}" 
                    class="form-check-input" 
                    value="" 

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
                <div>{{$questionnaire->responses->where('answer_id',$answer->id)->where('question_id',$item->question->id)->first()->content or ''}}</div>

            </label>
        </div>
    @endforeach
</div>
@endforeach
</form>
