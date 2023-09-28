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
            labels: 
                @if (Route::currentRouteName() == 'admin.surveys.show')

                    @json($item->get_answers()->pluck('title')->toArray())

                @elseif(Route::currentRouteName() == 'admin.questions.show')

                    @json($question->answerlist->answers->pluck('title')->toArray())

                @endif
                ,
            datasets: [{
                label: '%',
                backgroundColor: '#99BCDA',
                data: [
                    @if (Route::currentRouteName() == 'admin.surveys.show')

                        @foreach ($item->get_answers() as $answer)

                            @if ( $item->get_responses()->count() > 0)
                                "{{ round( $item->get_responses($answer)->count() / $item->get_responses()->count() * 100, 2 )}}",
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
                        @foreach ($item->get_answers() as $answer) 
                            @if($answer)
                                "{{$item->get_responses($answer)->count()}}",
                            @endif
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

            // display percentage / count inside bar
            animation: {

                onComplete: function() {
                
                    var ctx = this.chart.ctx;   
                
                    var dataset = this.data.datasets[0]; // get first dataset

                    var meta = this.getDatasetMeta(0); // get the meta data of the first dataset

                    for (var i = 0; i < dataset.data.length; i++) {

                        var model = meta.data[i]._model; // get the bar model
                        var percentage = dataset.data[i];
                        var count = dataset.count[i];

                        if (count > 0) {

                            ctx.fillText(count + '  (' + percentage + '%)', model.x - 25, model.y + 5); // draw text

                        }
                    }
                }
            },
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
                    },
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
