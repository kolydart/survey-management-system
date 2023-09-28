<canvas id="chart_{{$item->id ?? ''}}"></canvas>

<script>
    function splitter(str, l){
        var strs = [];
        while(str.length > l){
            var pos = str.substring(0, l).lastIndexOf(' ');
            pos = pos <= 0 ? l : pos;
            strs.push(str.substring(0, pos));
            var i = str.indexOf(' ', pos)+1;
            if(i < pos || i > pos+l)
                i = pos;
            str = str.substring(i);
        }
        strs.push(str);
        return strs;
    }
    var ctx = document.getElementById("chart_{{$item->id ?? ''}}");
    var myChart = new Chart(ctx, {
        type: 'horizontalBar',
        data: {
            labels: [
                @if (Route::currentRouteName() == 'admin.surveys.show')
                    @foreach ($item->question->answerlist->answers as $answer) 
                        "{{$answer->title}}", 
                    @endforeach 
                @elseif(Route::currentRouteName() == 'admin.questions.show')
                    @foreach ($question->answerlist->answers as $answer) 
                        "{{$answer->title}}", 
                    @endforeach 
                @endif
                ],
            datasets: [{
                label: '%',
                backgroundColor: '#99BCDA',
                data: [
                    @if (Route::currentRouteName() == 'admin.surveys.show')
                        @foreach ($item->question->answerlist->answers as $answer)
                            @if (App\Response::whereIn('questionnaire_id',$item->survey->questionnaires->pluck('id'))->where('question_id',$item->question_id)->count() > 0)
                                "{{round(
                                    App\Response::whereIn('questionnaire_id',$item->survey->questionnaires->pluck('id'))
                                        ->where('question_id',$item->question_id)
                                        ->where('answer_id',$answer->id)
                                        ->count()
                                    /
                                    App\Response::whereIn('questionnaire_id',$item->survey->questionnaires->pluck('id'))
                                        ->where('question_id',$item->question_id)
                                        ->count()
                                    *100
                                    ,2
                                    )}}",                       
                            @else
                                "0",
                            @endif
                        @endforeach

                    @elseif(Route::currentRouteName() == 'admin.questions.show')
                        @foreach ($question->answerlist->answers as $answer)
                            @if ($responses->count() > 0)
                                "{{round(
                                    $responses->where('answer_id',$answer->id)->count()
                                    /
                                    $responses->count()
                                    *100
                                    ,2
                                    )}}",                       
                            @else
                                "0",
                            @endif
                        @endforeach
                    @endif
                    ],
                borderWidth: 1,
                count: [
                    @if (Route::currentRouteName() == 'admin.surveys.show')
                        @foreach ($item->question->answerlist->answers as $answer) 
                            "{{App\Response::whereIn('questionnaire_id',$item->survey->questionnaires->pluck('id'))->where('question_id',$item->question_id)->where('answer_id',$answer->id)->count()}}", 
                        @endforeach
                    @elseif(Route::currentRouteName() == 'admin.questions.show')
                        @foreach ($question->answerlist->answers as $answer) 
                            "{{$responses->where('answer_id',$answer->id)->count()}}", 
                        @endforeach
                    @endif
                    ]
            }]
        },
        options: {
            legend: { display: false },
            scales: {
                xAxes: [{
                    ticks: {
                        callback: function(value) {
                            return value + '%';
                        },                     
                        beginAtZero: true
                    },
                }],
                yAxes: [{
                    ticks: {
                        beginAtZero:true,
                        callback: function(value) {
                            return splitter(value,30);
                        },
                        /* truncate yAxes label */
                        /* callback: function(value) {
                            if (value.length > 10) {
                                return value.substr(0, 10) + '...'; 
                            } else {
                                return value;
                            }
                        }*/
                    },
                    /*
                    afterFit: function(scaleInstance) {
                        scaleInstance.width = 100; // set the yAxes label width
                    },
                    */
            }],
        },
        tooltips: {
            mode: 'index',
            callbacks: {
                label: function(tooltipItem, data){
                    // add count to tooltip
                    return tooltipItem.xLabel + '% [' + data.datasets[tooltipItem.datasetIndex].count[tooltipItem.index] + ']';
                },
                title: function(tooltipItem, data) {
                    // get full title in tooltip
                    return splitter(data.labels[tooltipItem[0].index],40);
                },
          }
        }

        }
    });
</script>
