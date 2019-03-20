@foreach ($item->question->answerlist->answers as $answer)
    <p>{{$answer->title}}:
        @if (App\Response::whereIn('questionnaire_id',$item->survey->questionnaires->pluck('id'))->where('question_id',$item->question_id)->count() > 0)
            {{round(
                App\Response::whereIn('questionnaire_id',$item->survey->questionnaires->pluck('id'))->where('question_id',$item->question_id)->where('answer_id',$answer->id)->count()
                /
                App\Response::whereIn('questionnaire_id',$item->survey->questionnaires->pluck('id'))->where('question_id',$item->question_id)->count()
                *100
                ,2
                )}}@else 0,@endif%
            ({{App\Response::whereIn('questionnaire_id',$item->survey->questionnaires->pluck('id'))->where('question_id',$item->question_id)->where('answer_id',$answer->id)->count()}})
    </p>
@endforeach 
