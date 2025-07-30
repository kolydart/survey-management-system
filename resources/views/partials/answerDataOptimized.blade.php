{{-- 
Optimized answerData using SurveyStatisticsService
Used in admin.surveys.show route for raw data display
$item and $statisticsService must be available
--}}
@php
    // Use SurveyStatisticsService to get response data
    $responseData = isset($statisticsService) 
        ? $statisticsService->getResponseDataForQuestion($item->survey_id, $item->question_id)
        : null;
    
    // Fallback to original logic if service not available
    if (!$responseData) {
        $totalResponses = App\Response::whereIn('questionnaire_id', $item->survey->questionnaires->pluck('id'))
            ->where('question_id', $item->question_id)
            ->count();
        
        $responseData = [
            'total_responses' => $totalResponses,
            'answer_counts' => []
        ];
        
        foreach ($item->question->answerlist->answers as $answer) {
            $answerCount = App\Response::whereIn('questionnaire_id', $item->survey->questionnaires->pluck('id'))
                ->where('question_id', $item->question_id)
                ->where('answer_id', $answer->id)
                ->count();
            
            $responseData['answer_counts'][$answer->id] = [
                'count' => $answerCount,
                'percentage' => $totalResponses > 0 ? round($answerCount / $totalResponses * 100, 2) : 0
            ];
        }
    }
@endphp

@foreach ($item->question->answerlist->answers as $answer)
    <p>{{ $answer->title }}:
        @if ($responseData['total_responses'] > 0)
            {{ $responseData['answer_counts'][$answer->id]['percentage'] ?? 0 }}%
        @else
            0%
        @endif
        ({{ $responseData['answer_counts'][$answer->id]['count'] ?? 0 }})
    </p>
@endforeach