{{-- 
Optimized answerChart using ChartDataService
Used in admin.surveys.show route for chart display
$item and $chartService must be available
--}}
<canvas id="chart_{{$item->id ?? ''}}" class="gw_chart"></canvas>

<script>
    @php
        // Use ChartDataService to generate chart data
        $chartData = isset($chartService) 
            ? $chartService->generateChartData($item->survey_id, $item->question_id)
            : null;
        
        // Fallback to original logic if service not available
        if (!$chartData) {
            $answers = $item->get_answers();
            $totalResponses = $item->get_responses()->count();
            $chartData = [
                'labels' => $answers->pluck('title')->toArray(),
                'percentages' => [],
                'counts' => [],
                'aspectRatio' => 15 / ($answers->count() + 5)
            ];
            
            foreach ($answers as $answer) {
                $answerCount = $item->get_responses($answer)->count();
                $percentage = $totalResponses > 0 ? round($answerCount / $totalResponses * 100, 2) : 0;
                $chartData['percentages'][] = $percentage;
                $chartData['counts'][] = $answerCount;
            }
        }
    @endphp

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

    // Chart configuration using service data
    var ctx = document.getElementById("chart_{{$item->id ?? ''}}");
    var myChart = new Chart(ctx, {
        type: 'horizontalBar',
        data: {
            labels: @json($chartData['labels']),
            datasets: [{
                label: '%',
                backgroundColor: '#99BCDA',
                data: @json($chartData['percentages']),
                borderWidth: 1,
                count: @json($chartData['counts'])
            }]
        },
        options: {
            // Dynamic aspect ratio based on number of labels
            responsive: true,
            maintainAspectRatio: true,
            aspectRatio: {{ $chartData['aspectRatio'] ?? '3' }},

            // Display percentage/count inside bar
            animation: {
                onComplete: function() {
                    var ctx = this.chart.ctx;   
                    var dataset = this.data.datasets[0];
                    var meta = this.getDatasetMeta(0);

                    for (var i = 0; i < dataset.data.length; i++) {
                        var model = meta.data[i]._model;
                        var percentage = dataset.data[i];
                        var count = dataset.count[i];

                        if (count > 0) {
                            ctx.fillText(count + '  (' + percentage + '%)', model.x - 25, model.y + 5);
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
                        beginAtZero: true,
                        callback: function(value) {
                            return splitter(value, 30);
                        },
                    },
                }],
            },
            tooltips: {
                mode: 'index',
                callbacks: {
                    label: function(tooltipItem, data){
                        return tooltipItem.xLabel + '% [' + data.datasets[tooltipItem.datasetIndex].count[tooltipItem.index] + ']';
                    },
                    title: function(tooltipItem, data) {
                        return splitter(data.labels[tooltipItem[0].index], 40);
                    },
                }
            }
        }
    });
</script>