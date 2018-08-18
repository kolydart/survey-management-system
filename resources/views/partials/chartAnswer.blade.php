<canvas id="chart_{{$item->id}}"></canvas>

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
    var ctx = document.getElementById("chart_{{$item->id}}");
    var myChart = new Chart(ctx, {
        type: 'horizontalBar',
        data: {
            labels: [@foreach ($item->question->answerlist->answers as $answer) "{{$answer->title}}", @endforeach ],
            datasets: [{
                label: '%',
                data: [
                    @foreach ($item->question->answerlist->answers as $answer)
                        "{{ round($item->question->responses->where('answer_id',$answer->id)->count()/$item->question->responses->count()*100,2)}}", 
                    @endforeach
                    ],
                borderWidth: 1,
                count: [@foreach ($item->question->answerlist->answers as $answer) "{{$item->question->responses->where('answer_id',$answer->id)->count()}}", @endforeach ]
            }]
        },
        options: {
            legend: { display: false },
            scales: {
                xAxes: [], 
                yAxes: [{
                    ticks: {
                        beginAtZero:true,
                        // truncate yAxes label
                        callback: function(value) {
                            if (value.length > 10) {
                                return value.substr(0, 10) + '...'; 
                            } else {
                                return value;
                            }
                        }
                    },
                    afterFit: function(scaleInstance) {
                        scaleInstance.width = 100; // set the yAxes label width
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
