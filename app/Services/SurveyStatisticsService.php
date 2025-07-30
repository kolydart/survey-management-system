<?php

namespace App\Services;

use App\Response;

/**
 * Service to handle statistical calculations for survey data
 * Extracts logic from questionnaireRender.blade.php (lines 268-293)
 */
class SurveyStatisticsService
{
    /**
     * Calculate comprehensive statistics for numeric survey responses
     *
     * @param array $data Raw response data
     * @return array Statistical calculations or null if not applicable
     */
    public function calculateStatistics(array $data): ?array
    {
        if (empty($data)) {
            return null;
        }

        // Filter and convert to numeric values
        $numericData = $this->filterNumericData($data);
        
        if (empty($numericData)) {
            return null;
        }

        return [
            'min' => $this->calculateMin($numericData),
            'max' => $this->calculateMax($numericData),
            'mean' => $this->calculateMean($numericData),
            'median' => $this->calculateMedian($numericData),
            'count' => count($numericData),
            'raw_count' => count($data), // Original data count including non-numeric
        ];
    }

    /**
     * Get response data for statistical calculation
     *
     * @param int $surveyId
     * @param int $questionId
     * @return array
     */
    public function getResponseDataForStatistics(int $surveyId, int $questionId): array
    {
        return Response::whereHas('questionnaire', function ($query) use ($surveyId) {
                $query->where('survey_id', $surveyId);
            })
            ->where('question_id', $questionId)
            ->whereNotNull('content')
            ->where('content', '!=', '')
            ->pluck('content')
            ->toArray();
    }

    /**
     * Check if answer type supports statistical calculations
     *
     * @param string $answerType
     * @return bool
     */
    public function supportsStatistics(string $answerType): bool
    {
        return in_array($answerType, [
            'number', 
            'range', 
            'date', 
            'time', 
            'datetime-local', 
            'week', 
            'month'
        ]);
    }

    /**
     * Calculate minimum value with error handling
     *
     * @param array $data
     * @return float|int|null
     */
    protected function calculateMin(array $data)
    {
        if (empty($data)) {
            return null;
        }

        try {
            return min($data);
        } catch (\ArgumentCountError $e) {
            return null;
        }
    }

    /**
     * Calculate maximum value with error handling
     *
     * @param array $data
     * @return float|int|null
     */
    protected function calculateMax(array $data)
    {
        if (empty($data)) {
            return null;
        }

        try {
            return max($data);
        } catch (\ArgumentCountError $e) {
            return null;
        }
    }

    /**
     * Calculate mean (average) with division by zero protection
     *
     * @param array $data
     * @return float|null
     */
    protected function calculateMean(array $data): ?float
    {
        if (empty($data)) {
            return null;
        }

        $sum = array_sum($data);
        $count = count($data);

        if ($count === 0) {
            return null;
        }

        return round($sum / $count, 2);
    }

    /**
     * Calculate median value - replicates exact logic from view
     * Based on questionnaireRender.blade.php lines 277-289
     *
     * @param array $data
     * @return float|int|null
     */
    protected function calculateMedian(array $data)
    {
        if (empty($data)) {
            return null;
        }

        // Sort the array (modifies the original, but we're working with a copy)
        sort($data);
        
        $count = count($data);
        $middleValue = floor(($count - 1) / 2);

        if ($count % 2) {
            // Odd number of elements
            return $data[$middleValue];
        } else {
            // Even number of elements
            $low = $data[$middleValue];
            $high = $data[$middleValue + 1];
            return ($low + $high) / 2;
        }
    }

    /**
     * Filter array to only include numeric values
     *
     * @param array $data
     * @return array
     */
    protected function filterNumericData(array $data): array
    {
        return array_filter($data, function ($value) {
            return is_numeric($value);
        });
    }

    /**
     * Format statistics for display
     *
     * @param array $statistics
     * @return array
     */
    public function formatStatisticsForDisplay(array $statistics): array
    {
        if (empty($statistics)) {
            return [];
        }

        return [
            'min' => $statistics['min'],
            'max' => $statistics['max'],
            'mean' => number_format($statistics['mean'], 2),
            'median' => is_float($statistics['median']) 
                ? number_format($statistics['median'], 2) 
                : $statistics['median'],
            'count' => number_format($statistics['count']),
        ];
    }

    /**
     * Get statistics text representation (matching view output)
     *
     * @param array $statistics
     * @param string $answerType
     * @return string
     */
    public function getStatisticsText(array $statistics, string $answerType): string
    {
        if (empty($statistics)) {
            return '';
        }

        $text = sprintf(
            'min: %s, max: %s, mean: %s, count: %s',
            $statistics['min'],
            $statistics['max'],
            number_format($statistics['mean'], 2),
            $statistics['count']
        );

        // Add median for number and range types only
        if (in_array($answerType, ['number', 'range']) && isset($statistics['median'])) {
            $medianFormatted = is_float($statistics['median']) 
                ? number_format($statistics['median'], 2) 
                : $statistics['median'];
                
            $text .= sprintf(', median: %s', $medianFormatted);
        }

        return $text;
    }
    
    /**
     * Validate data integrity for statistical calculations
     *
     * @param array $data
     * @return array Validation results
     */
    public function validateDataIntegrity(array $data): array
    {
        $results = [
            'is_valid' => true,
            'warnings' => [],
            'errors' => [],
        ];

        if (empty($data)) {
            $results['warnings'][] = 'Empty dataset provided';
            return $results;
        }

        $numericData = $this->filterNumericData($data);
        $nonNumericCount = count($data) - count($numericData);

        if ($nonNumericCount > 0) {
            $results['warnings'][] = sprintf(
                '%d non-numeric values found and will be excluded from calculations',
                $nonNumericCount
            );
        }

        if (empty($numericData)) {
            $results['is_valid'] = false;
            $results['errors'][] = 'No numeric data available for statistical calculations';
        }

        // Check for extreme values that might indicate data quality issues
        if (!empty($numericData)) {
            $min = min($numericData);
            $max = max($numericData);
            
            if ($max - $min === 0) {
                $results['warnings'][] = 'All values are identical';
            }
            
            // Check for potential outliers (very basic check)
            $mean = array_sum($numericData) / count($numericData);
            $range = $max - $min;
            
            if ($range > $mean * 1000) { // Arbitrary threshold
                $results['warnings'][] = 'Large range detected, possible outliers present';
            }
        }

        return $results;
    }

    /**
     * Calculate statistics with caching support
     *
     * @param int $surveyId
     * @param int $questionId
     * @param string $answerType
     * @param bool $useCache
     * @return array|null
     */
    public function getStatisticsWithCache(int $surveyId, int $questionId, string $answerType, bool $useCache = true): ?array
    {
        if (!$this->supportsStatistics($answerType)) {
            return null;
        }

        $cacheKey = "survey_statistics_{$surveyId}_{$questionId}";

        if ($useCache && cache()->has($cacheKey)) {
            return cache()->get($cacheKey);
        }

        $data = $this->getResponseDataForStatistics($surveyId, $questionId);
        $statistics = $this->calculateStatistics($data);

        if ($useCache && $statistics) {
            // Cache for 1 hour
            cache()->put($cacheKey, $statistics, 3600);
        }

        return $statistics;
    }

    /**
     * Clear statistics cache for a survey
     *
     * @param int $surveyId
     * @return void
     */
    public function clearStatisticsCache(int $surveyId): void
    {
        // This is a simple implementation - in production you might want
        // to use cache tags or a more sophisticated approach
        $keys = cache()->getRedis()->keys("*survey_statistics_{$surveyId}_*");
        
        foreach ($keys as $key) {
            cache()->forget($key);
        }
    }
}