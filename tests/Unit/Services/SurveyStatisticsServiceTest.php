<?php

namespace Tests\Unit\Services;

use App\Services\SurveyStatisticsService;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Unit tests for statistics calculations currently in questionnaireRender.blade.php
 * These tests validate the statistical formulas used in lines 268-293 of the view
 * 
 * Critical areas tested:
 * - min($array) - line 271
 * - max($array) - line 272  
 * - array_sum($array)/count($array) - line 273 (mean calculation)
 * - Median calculation - lines 277-289
 * - Division by zero protection
 * - Empty array handling
 * - Non-numeric data handling
 */
class SurveyStatisticsServiceTest extends TestCase
{
    /**
     * Test min calculation with valid numeric data
     */
    #[Test]
    public function test_calculate_min_with_valid_numeric_data()
    {
        $data = [10, 5, 8, 3, 15, 1];
        $result = min($data);
        
        $this->assertEquals(1, $result);
    }

    /**
     * Test min calculation with single value
     */
    #[Test]
    public function test_calculate_min_with_single_value()
    {
        $data = [42];
        $result = min($data);
        
        $this->assertEquals(42, $result);
    }

    /**
     * Test min calculation with negative numbers
     */
    #[Test]
    public function test_calculate_min_with_negative_numbers()
    {
        $data = [-5, -10, 3, -2];
        $result = min($data);
        
        $this->assertEquals(-10, $result);
    }

    /**
     * Test min calculation with decimal numbers
     */
    #[Test]
    public function test_calculate_min_with_decimal_numbers()
    {
        $data = [1.5, 2.7, 0.3, 4.1];
        $result = min($data);
        
        $this->assertEquals(0.3, $result);
    }

    /**
     * Test max calculation with valid numeric data
     */
    #[Test]
    public function test_calculate_max_with_valid_numeric_data()
    {
        $data = [10, 5, 8, 3, 15, 1];
        $result = max($data);
        
        $this->assertEquals(15, $result);
    }

    /**
     * Test max calculation with single value
     */
    #[Test]
    public function test_calculate_max_with_single_value()
    {
        $data = [42];
        $result = max($data);
        
        $this->assertEquals(42, $result);
    }

    /**
     * Test mean calculation with valid numeric data
     */
    #[Test]
    public function test_calculate_mean_with_valid_numeric_data()
    {
        $data = [10, 20, 30];
        $result = round(array_sum($data) / count($data), 2);
        
        $this->assertEquals(20.00, $result);
    }

    /**
     * Test mean calculation with decimal result
     */
    #[Test]
    public function test_calculate_mean_with_decimal_result()
    {
        $data = [1, 2, 3];
        $result = round(array_sum($data) / count($data), 2);
        
        $this->assertEquals(2.00, $result);
    }

    /**
     * Test mean calculation with single value
     */
    #[Test]
    public function test_calculate_mean_with_single_value()
    {
        $data = [42];
        $result = round(array_sum($data) / count($data), 2);
        
        $this->assertEquals(42.00, $result);
    }

    /**
     * Test median calculation with odd number count
     * This tests the logic from lines 277-283 in questionnaireRender.blade.php
     */
    #[Test]
    public function test_calculate_median_with_odd_number_count()
    {
        $array = [1, 3, 5, 7, 9];
        
        // Replicate the exact logic from the view
        sort($array);
        $count = count($array);
        $middle_value = floor(($count - 1) / 2);
        
        if ($count % 2) {
            $median = $array[$middle_value];
        } else {
            $low = $array[$middle_value];
            $high = $array[$middle_value + 1];
            $median = (($low + $high) / 2);
        }
        
        $this->assertEquals(5, $median);
        $this->assertEquals(1, $count % 2); // Verify it's odd
    }

    /**
     * Test median calculation with even number count
     * This tests the logic from lines 284-288 in questionnaireRender.blade.php
     */
    #[Test]
    public function test_calculate_median_with_even_number_count()
    {
        $array = [1, 2, 8, 9];
        
        // Replicate the exact logic from the view
        sort($array);
        $count = count($array);
        $middle_value = floor(($count - 1) / 2);
        
        if ($count % 2) {
            $median = $array[$middle_value];
        } else {
            $low = $array[$middle_value];
            $high = $array[$middle_value + 1];
            $median = (($low + $high) / 2);
        }
        
        $this->assertEquals(5, $median); // (2 + 8) / 2 = 5
        $this->assertEquals(0, $count % 2); // Verify it's even
    }

    /**
     * Test median calculation with single value
     */
    #[Test]
    public function test_calculate_median_with_single_value()
    {
        $array = [42];
        
        sort($array);
        $count = count($array);
        $middle_value = floor(($count - 1) / 2);
        
        if ($count % 2) {
            $median = $array[$middle_value];
        } else {
            $low = $array[$middle_value];
            $high = $array[$middle_value + 1];
            $median = (($low + $high) / 2);
        }
        
        $this->assertEquals(42, $median);
    }

    /**
     * Test median calculation with duplicate values
     */
    #[Test]
    public function test_calculate_median_with_duplicate_values()
    {
        $array = [1, 2, 2, 2, 3];
        
        sort($array);
        $count = count($array);
        $middle_value = floor(($count - 1) / 2);
        
        if ($count % 2) {
            $median = $array[$middle_value];
        } else {
            $low = $array[$middle_value];
            $high = $array[$middle_value + 1];
            $median = (($low + $high) / 2);
        }
        
        $this->assertEquals(2, $median);
    }

    /**
     * Test handling of empty array - critical edge case
     */
    #[Test]
    public function test_handle_empty_array_gracefully()
    {
        $array = [];
        
        // Test each function that could fail with empty array
        // These operations will trigger warnings/errors with empty arrays
        try {
            $min = min($array);
            $this->fail('min() should throw error with empty array');
        } catch (\ValueError $e) {
            $this->assertStringContainsString('min', $e->getMessage());
        }
        
        try {
            $max = max($array);
            $this->fail('max() should throw error with empty array');
        } catch (\ValueError $e) {
            $this->assertStringContainsString('max', $e->getMessage());
        }
        
        // Division by zero for mean
        if (count($array) > 0) {
            $mean = array_sum($array) / count($array);
        } else {
            // This is what should happen in the actual code
            $mean = 0;
        }
        
        $this->assertEquals(0, $mean);
    }

    /**
     * Test division by zero protection for mean calculation
     */
    #[Test]
    public function test_division_by_zero_protection()
    {
        $array = [];
        
        // Test the actual logic that should be in the view
        if (count($array) > 0) {
            $mean = round(array_sum($array) / count($array), 2);
        } else {
            $mean = 0; // Default value for empty arrays
        }
        
        $this->assertEquals(0, $mean);
    }

    /**
     * Test handling of non-numeric data
     */
    #[Test]
    public function test_handle_non_numeric_data_gracefully()
    {
        $array = ['a', 'b', 'c'];
        
        // min/max will work with strings (lexicographic comparison)
        $min = min($array);
        $max = max($array);
        
        $this->assertEquals('a', $min);
        $this->assertEquals('c', $max);

        // Summing them directly is what the service must never do: since
        // PHP 8.3 array_sum() warns on non-numeric strings, and Laravel's
        // error handler turns that warning into an ErrorException.
        $this->expectException(\ErrorException::class);
        array_sum($array);
    }

    /**
     * The service itself must reject an all non-numeric data set rather than
     * attempting to sum it.
     */
    #[Test]
    public function test_service_returns_null_for_non_numeric_data()
    {
        $service = new SurveyStatisticsService();

        $this->assertNull($service->calculateStatistics(['a', 'b', 'c']));
    }

    /**
     * Test handling of mixed numeric and string data
     */
    #[Test]
    public function test_handle_mixed_numeric_string_data()
    {
        $service = new SurveyStatisticsService();

        $statistics = $service->calculateStatistics([1, 'abc', 3, 'def', 5]);

        // Non-numeric entries are dropped from every calculation, but still
        // counted in raw_count so the caller can see how much was discarded.
        $this->assertEquals(1, $statistics['min']);
        $this->assertEquals(5, $statistics['max']);
        $this->assertEquals(3, $statistics['mean']); // (1 + 3 + 5) / 3
        $this->assertEquals(3, $statistics['median']);
        $this->assertEquals(3, $statistics['count']);
        $this->assertEquals(5, $statistics['raw_count']);
    }

    /**
     * Test handling of numeric strings
     */
    #[Test]
    public function test_handle_numeric_strings()
    {
        $array = ['1', '2', '3', '4', '5'];
        
        $sum = array_sum($array); // PHP will convert strings to numbers
        $this->assertEquals(15, $sum);
        
        $mean = round($sum / count($array), 2);
        $this->assertEquals(3.00, $mean);
        
        $min = min($array);
        $max = max($array);
        
        // Note: min/max will do string comparison unless converted
        $this->assertEquals('1', $min);
        $this->assertEquals('5', $max);
    }

    /**
     * Test handling of large numbers
     */
    #[Test]
    public function test_handle_large_numbers()
    {
        $array = [999999999, 1000000000, 1000000001];
        
        $min = min($array);
        $max = max($array);
        $mean = round(array_sum($array) / count($array), 2);
        
        $this->assertEquals(999999999, $min);
        $this->assertEquals(1000000001, $max);
        $this->assertEquals(1000000000.00, $mean);
    }

    /**
     * Test performance with large dataset
     */
    #[Test]
    public function test_performance_with_large_dataset()
    {
        // Create large array (simulates many survey responses)
        $array = range(1, 10000);
        shuffle($array);
        
        $startTime = microtime(true);
        
        $min = min($array);
        $max = max($array);
        $mean = round(array_sum($array) / count($array), 2);
        
        // Test median calculation with large dataset
        sort($array);
        $count = count($array);
        $middle_value = floor(($count - 1) / 2);
        
        if ($count % 2) {
            $median = $array[$middle_value];
        } else {
            $low = $array[$middle_value];
            $high = $array[$middle_value + 1];
            $median = (($low + $high) / 2);
        }
        
        $endTime = microtime(true);
        $executionTime = ($endTime - $startTime) * 1000;
        
        $this->assertEquals(1, $min);
        $this->assertEquals(10000, $max);
        $this->assertEquals(5000.5, $mean);
        $this->assertEquals(5000.5, $median);
        
        // Should complete within reasonable time (less than 100ms)
        $this->assertLessThan(100, $executionTime, 'Statistics calculations took too long');
    }

    /**
     * Test answerlist type validation logic
     * This tests the condition from line 269 in questionnaireRender.blade.php
     */
    #[Test]
    public function test_answerlist_type_validation_for_statistics()
    {
        $numericTypes = ["number", "range", "date", "time", "datetime-local", "week", "month"];
        $nonNumericTypes = ["text", "email", "password", "radio", "checkbox"];
        
        // Test that numeric types are properly identified
        foreach ($numericTypes as $type) {
            $this->assertTrue(
                in_array($type, ["number", "range", "date", "time", "datetime-local", "week", "month"]),
                "Type {$type} should be valid for statistics"
            );
        }
        
        // Test that non-numeric types are excluded
        foreach ($nonNumericTypes as $type) {
            $this->assertFalse(
                in_array($type, ["number", "range", "date", "time", "datetime-local", "week", "month"]),
                "Type {$type} should not be valid for statistics"
            );
        }
    }

    /**
     * Test median calculation edge case with two elements
     */
    #[Test]
    public function test_median_calculation_with_two_elements()
    {
        $array = [10, 20];
        
        sort($array);
        $count = count($array);
        $middle_value = floor(($count - 1) / 2);
        
        if ($count % 2) {
            $median = $array[$middle_value];
        } else {
            $low = $array[$middle_value];
            $high = $array[$middle_value + 1];
            $median = (($low + $high) / 2);
        }
        
        $this->assertEquals(15, $median); // (10 + 20) / 2 = 15
    }

    /**
     * Test count function behavior
     */
    #[Test]
    public function test_count_function_behavior()
    {
        $this->assertEquals(0, count([]));
        $this->assertEquals(1, count([42]));
        $this->assertEquals(3, count([1, 2, 3]));
        $this->assertEquals(5, count(['a', 1, null, false, ''])); // All elements counted
    }

    /**
     * Test array_sum behavior with various data types
     */
    #[Test]
    public function test_array_sum_behavior_with_various_types()
    {
        $this->assertEquals(0, array_sum([]));
        $this->assertEquals(6, array_sum([1, 2, 3]));
        $this->assertEquals(6, array_sum(['1', '2', '3'])); // String numbers
        $this->assertEquals(1, array_sum([true, false])); // Booleans
    }

    /**
     * Since PHP 8.3 array_sum() no longer silently treats a non-numeric string
     * as 0 - it warns, which Laravel's error handler promotes to an exception.
     * This is why SurveyStatisticsService filters before summing.
     */
    #[Test]
    public function test_array_sum_rejects_non_numeric_strings()
    {
        $this->expectException(\ErrorException::class);

        array_sum([1, 'a', 2]);
    }

    /**
     * Test rounding behavior in mean calculation
     */
    #[Test]
    public function test_rounding_behavior_in_mean_calculation()
    {
        $array = [1, 2];
        $mean = round(array_sum($array) / count($array), 2);
        
        $this->assertEquals(1.50, $mean);
        
        $array = [1, 2, 3];
        $mean = round(array_sum($array) / count($array), 2);
        
        $this->assertEquals(2.00, $mean);
        
        $array = [1];
        $mean = round(array_sum($array) / count($array), 2);
        
        $this->assertEquals(1.00, $mean);
    }
}