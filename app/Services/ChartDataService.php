<?php

namespace App\Services;

use App\Item;
use App\Response;

/**
 * Service to handle chart data generation for survey responses
 * Extracts logic from answerChart.blade.php and answerData.blade.php
 */
class ChartDataService
{
    /**
     * Generate chart data for horizontal bar charts
     *
     * @param Item $item
     * @return array
     */
    public function generateChartData(Item $item): array
    {
        $answers = $item->get_answers();
        $totalResponses = $item->get_responses()->count();

        $chartData = [
            'labels' => [],
            'datasets' => [
                [
                    'label' => '%',
                    'backgroundColor' => '#99BCDA',
                    'data' => [],
                    'borderWidth' => 1,
                    'count' => []
                ]
            ],
            'config' => [
                'type' => 'horizontalBar',
                'responsive' => true,
                'maintainAspectRatio' => true,
                'aspectRatio' => $this->calculateAspectRatio($answers->count()),
            ]
        ];

        foreach ($answers as $answer) {
            $answerCount = $item->get_responses($answer)->count();
            $percentage = $this->calculatePercentage($answerCount, $totalResponses);

            $chartData['labels'][] = $answer->title;
            $chartData['datasets'][0]['data'][] = $percentage;
            $chartData['datasets'][0]['count'][] = $answerCount;
        }

        return $chartData;
    }

    /**
     * Generate raw data for text display (alternative to charts)
     *
     * @param Item $item
     * @return array
     */
    public function generateRawData(Item $item): array
    {
        $answers = $item->question->answerlist->answers;
        $totalResponses = $this->getTotalResponses($item);
        $rawData = [];

        foreach ($answers as $answer) {
            $answerCount = $this->getAnswerResponseCount($item, $answer->id);
            $percentage = $this->calculatePercentage($answerCount, $totalResponses);

            $rawData[] = [
                'title' => $answer->title,
                'count' => $answerCount,
                'percentage' => $percentage,
                'formatted' => $this->formatRawDataText($answer->title, $answerCount, $percentage)
            ];
        }

        return $rawData;
    }

    /**
     * Calculate percentage with division by zero protection
     *
     * @param int $count
     * @param int $total
     * @return float
     */
    public function calculatePercentage(int $count, int $total): float
    {
        if ($total <= 0) {
            return 0.0;
        }

        return round(($count / $total) * 100, 2);
    }

    /**
     * Calculate aspect ratio for chart display
     * Based on answerChart.blade.php line 96
     *
     * @param int $labelsCount
     * @return float
     */
    public function calculateAspectRatio(int $labelsCount): float
    {
        return 15 / ($labelsCount + 5);
    }

    /**
     * Split long text for chart labels
     * Replicates splitter function from answerChart.blade.php lines 4-17
     *
     * @param string $text
     * @param int $maxLength
     * @return array
     */
    public function splitTextForLabels(string $text, int $maxLength = 30): array
    {
        $parts = [];
        
        while (strlen($text) > $maxLength) {
            $pos = strrpos(substr($text, 0, $maxLength), ' ');
            $pos = $pos <= 0 ? $maxLength : $pos;
            
            $parts[] = substr($text, 0, $pos);
            
            $i = strpos($text, ' ', $pos) + 1;
            if ($i < $pos || $i > $pos + $maxLength) {
                $i = $pos;
            }
            
            $text = substr($text, $i);
        }
        
        $parts[] = $text;
        
        return $parts;
    }

    /**
     * Format tooltip text for charts
     *
     * @param float $percentage
     * @param int $count
     * @return string
     */
    public function formatTooltip(float $percentage, int $count): string
    {
        return sprintf('%.2f%% [%d]', $percentage, $count);
    }

    /**
     * Get chart configuration with all options
     *
     * @param array $labels
     * @param array $data
     * @param array $counts
     * @return array
     */
    public function getChartConfiguration(array $labels, array $data, array $counts): array
    {
        return [
            'type' => 'horizontalBar',
            'data' => [
                'labels' => $labels,
                'datasets' => [
                    [
                        'label' => '%',
                        'backgroundColor' => '#99BCDA',
                        'data' => $data,
                        'borderWidth' => 1,
                        'count' => $counts
                    ]
                ]
            ],
            'options' => [
                'responsive' => true,
                'maintainAspectRatio' => true,
                'aspectRatio' => $this->calculateAspectRatio(count($labels)),
                'legend' => ['display' => false],
                'scales' => [
                    'xAxes' => [
                        [
                            'ticks' => [
                                'beginAtZero' => true,
                                'callback' => 'function(value) { return value + "%"; }'
                            ]
                        ]
                    ],
                    'yAxes' => [
                        [
                            'ticks' => [
                                'beginAtZero' => true,
                                'callback' => 'function(value) { return splitLabels(value, 30); }'
                            ]
                        ]
                    ]
                ],
                'tooltips' => [
                    'mode' => 'index',
                    'callbacks' => [
                        'label' => 'function(tooltipItem, data) { 
                            return tooltipItem.xLabel + "% [" + data.datasets[tooltipItem.datasetIndex].count[tooltipItem.index] + "]"; 
                        }',
                        'title' => 'function(tooltipItem, data) { 
                            return splitLabels(data.labels[tooltipItem[0].index], 40); 
                        }'
                    ]
                ]
            ]
        ];
    }

    /**
     * Validate chart data for consistency
     *
     * @param array $chartData
     * @return array
     */
    public function validateChartData(array $chartData): array
    {
        $validation = [
            'is_valid' => true,
            'errors' => [],
            'warnings' => []
        ];

        // Check required fields
        $requiredFields = ['labels', 'datasets'];
        foreach ($requiredFields as $field) {
            if (!isset($chartData[$field])) {
                $validation['is_valid'] = false;
                $validation['errors'][] = "Missing required field: {$field}";
            }
        }

        if (!$validation['is_valid']) {
            return $validation;
        }

        // Check data consistency
        $labels = $chartData['labels'];
        $dataset = $chartData['datasets'][0] ?? [];
        
        if (count($labels) !== count($dataset['data'] ?? [])) {
            $validation['is_valid'] = false;
            $validation['errors'][] = 'Labels and data arrays have different lengths';
        }

        if (count($labels) !== count($dataset['count'] ?? [])) {
            $validation['is_valid'] = false;
            $validation['errors'][] = 'Labels and count arrays have different lengths';
        }

        // Check for empty data
        if (empty($labels)) {
            $validation['warnings'][] = 'No data available for chart';
        }

        // Check percentage totals
        $totalPercentage = array_sum($dataset['data'] ?? []);
        if ($totalPercentage > 100.5) { // Small tolerance for rounding
            $validation['warnings'][] = 'Total percentage exceeds 100%';
        }

        return $validation;
    }

    /**
     * Get total responses for an item across all questionnaires in survey
     *
     * @param Item $item
     * @return int
     */
    protected function getTotalResponses(Item $item): int
    {
        return Response::whereIn('questionnaire_id', $item->survey->questionnaires->pluck('id'))
            ->where('question_id', $item->question_id)
            ->count();
    }

    /**
     * Get response count for specific answer
     *
     * @param Item $item
     * @param int $answerId
     * @return int
     */
    protected function getAnswerResponseCount(Item $item, int $answerId): int
    {
        return Response::whereIn('questionnaire_id', $item->survey->questionnaires->pluck('id'))
            ->where('question_id', $item->question_id)
            ->where('answer_id', $answerId)
            ->count();
    }

    /**
     * Format raw data text representation
     *
     * @param string $title
     * @param int $count
     * @param float $percentage
     * @return string
     */
    protected function formatRawDataText(string $title, int $count, float $percentage): string
    {
        return sprintf('%s: %.2f%% (%d)', $title, $percentage, $count);
    }

    /**
     * Generate chart data with caching
     *
     * @param Item $item
     * @param bool $useCache
     * @return array
     */
    public function generateChartDataWithCache(Item $item, bool $useCache = true): array
    {
        $cacheKey = "chart_data_{$item->id}";

        if ($useCache && cache()->has($cacheKey)) {
            return cache()->get($cacheKey);
        }

        $chartData = $this->generateChartData($item);

        if ($useCache) {
            // Cache for 30 minutes
            cache()->put($cacheKey, $chartData, 1800);
        }

        return $chartData;
    }

    /**
     * Clear chart data cache for survey
     *
     * @param int $surveyId
     * @return void
     */
    public function clearChartDataCache(int $surveyId): void
    {
        // Clear cache for all items in the survey
        $itemIds = \App\Item::where('survey_id', $surveyId)->pluck('id');
        
        foreach ($itemIds as $itemId) {
            cache()->forget("chart_data_{$itemId}");
        }
    }

    /**
     * Export chart data to various formats
     *
     * @param array $chartData
     * @param string $format
     * @return string|array
     */
    public function exportChartData(array $chartData, string $format = 'json')
    {
        switch ($format) {
            case 'csv':
                return $this->exportToCsv($chartData);
            case 'xml':
                return $this->exportToXml($chartData);
            case 'json':
            default:
                return json_encode($chartData, JSON_PRETTY_PRINT);
        }
    }

    /**
     * Export chart data to CSV format
     *
     * @param array $chartData
     * @return string
     */
    protected function exportToCsv(array $chartData): string
    {
        $csv = "Label,Percentage,Count\n";
        
        $labels = $chartData['labels'] ?? [];
        $data = $chartData['datasets'][0]['data'] ?? [];
        $counts = $chartData['datasets'][0]['count'] ?? [];
        
        for ($i = 0; $i < count($labels); $i++) {
            $csv .= sprintf(
                '"%s",%.2f,%d' . "\n",
                str_replace('"', '""', $labels[$i]),
                $data[$i] ?? 0,
                $counts[$i] ?? 0
            );
        }
        
        return $csv;
    }

    /**
     * Export chart data to XML format
     *
     * @param array $chartData
     * @return string
     */
    protected function exportToXml(array $chartData): string
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= "<chartData>\n";
        
        $labels = $chartData['labels'] ?? [];
        $data = $chartData['datasets'][0]['data'] ?? [];
        $counts = $chartData['datasets'][0]['count'] ?? [];
        
        for ($i = 0; $i < count($labels); $i++) {
            $xml .= sprintf(
                "  <item>\n    <label>%s</label>\n    <percentage>%.2f</percentage>\n    <count>%d</count>\n  </item>\n",
                htmlspecialchars($labels[$i]),
                $data[$i] ?? 0,
                $counts[$i] ?? 0
            );
        }
        
        $xml .= "</chartData>\n";
        
        return $xml;
    }
}