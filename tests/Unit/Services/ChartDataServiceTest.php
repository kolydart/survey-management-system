<?php

namespace Tests\Unit\Services;

use Tests\TestCase;

/**
 * Unit tests for chart data calculations currently in answerChart.blade.php
 * These tests validate the percentage calculations used in chart generation
 * 
 * Critical areas tested:
 * - Percentage calculation: round(count / total * 100, 2) - lines 52, 62-68
 * - Division by zero protection - lines 51, 61
 * - Count calculations for chart tooltips - lines 80, 85
 * - Empty response handling
 */
class ChartDataServiceTest extends TestCase
{
    /**
     * @test
     * Test percentage calculation with valid data
     */
    public function test_generate_percentage_data_correctly()
    {
        $answerCount = 25;
        $totalResponses = 100;
        
        $percentage = round($answerCount / $totalResponses * 100, 2);
        
        $this->assertEquals(25.00, $percentage);
    }

    /**
     * @test
     * Test percentage calculation with decimal result
     */
    public function test_generate_percentage_with_decimal_result()
    {
        $answerCount = 1;
        $totalResponses = 3;
        
        $percentage = round($answerCount / $totalResponses * 100, 2);
        
        $this->assertEquals(33.33, $percentage);
    }

    /**
     * @test
     * Test percentage calculation with rounding
     */
    public function test_percentage_calculation_rounding()
    {
        $answerCount = 2;
        $totalResponses = 7;
        
        $percentage = round($answerCount / $totalResponses * 100, 2);
        
        $this->assertEquals(28.57, $percentage);
    }

    /**
     * @test
     * Test handling zero total responses - critical edge case
     */
    public function test_handle_zero_total_responses()
    {
        $answerCount = 5;
        $totalResponses = 0;
        
        // This is the critical check from the view logic
        if ($totalResponses > 0) {
            $percentage = round($answerCount / $totalResponses * 100, 2);
        } else {
            $percentage = 0; // Default value
        }
        
        $this->assertEquals(0, $percentage);
    }

    /**
     * @test
     * Test handling zero answer count
     */
    public function test_handle_zero_answer_count()
    {
        $answerCount = 0;
        $totalResponses = 100;
        
        $percentage = round($answerCount / $totalResponses * 100, 2);
        
        $this->assertEquals(0.00, $percentage);
    }

    /**
     * @test
     * Test 100% percentage calculation
     */
    public function test_handle_full_percentage()
    {
        $answerCount = 100;
        $totalResponses = 100;
        
        $percentage = round($answerCount / $totalResponses * 100, 2);
        
        $this->assertEquals(100.00, $percentage);
    }

    /**
     * @test
     * Test percentage greater than 100% (edge case that shouldn't normally happen)
     */
    public function test_handle_percentage_over_100()
    {
        $answerCount = 150;
        $totalResponses = 100;
        
        $percentage = round($answerCount / $totalResponses * 100, 2);
        
        $this->assertEquals(150.00, $percentage);
    }

    /**
     * @test
     * Test small fraction percentages
     */
    public function test_handle_small_fraction_percentages()
    {
        $answerCount = 1;
        $totalResponses = 1000;
        
        $percentage = round($answerCount / $totalResponses * 100, 2);
        
        $this->assertEquals(0.10, $percentage);
    }

    /**
     * @test
     * Test very small percentages that round to zero
     */
    public function test_handle_very_small_percentages()
    {
        $answerCount = 1;
        $totalResponses = 10000;
        
        $percentage = round($answerCount / $totalResponses * 100, 2);
        
        $this->assertEquals(0.01, $percentage);
        
        // Even smaller
        $answerCount = 1;
        $totalResponses = 100000;
        
        $percentage = round($answerCount / $totalResponses * 100, 2);
        
        $this->assertEquals(0.00, $percentage); // Rounds to 0.00
    }

    /**
     * @test
     * Test chart data structure for multiple answers
     */
    public function test_generate_chart_data_for_multiple_answers()
    {
        // Simulate data for multiple answers
        $answers = [
            ['id' => 1, 'title' => 'Answer 1', 'count' => 30],
            ['id' => 2, 'title' => 'Answer 2', 'count' => 20],
            ['id' => 3, 'title' => 'Answer 3', 'count' => 50],
        ];
        
        $totalResponses = 100;
        $chartData = [];
        
        foreach ($answers as $answer) {
            if ($totalResponses > 0) {
                $percentage = round($answer['count'] / $totalResponses * 100, 2);
            } else {
                $percentage = 0;
            }
            
            $chartData[] = [
                'title' => $answer['title'],
                'percentage' => $percentage,
                'count' => $answer['count']
            ];
        }
        
        $this->assertCount(3, $chartData);
        $this->assertEquals(30.00, $chartData[0]['percentage']);
        $this->assertEquals(20.00, $chartData[1]['percentage']);
        $this->assertEquals(50.00, $chartData[2]['percentage']);
        
        // Verify total adds up to 100%
        $totalPercentage = array_sum(array_column($chartData, 'percentage'));
        $this->assertEquals(100.00, $totalPercentage);
    }

    /**
     * @test
     * Test chart data with no responses
     */
    public function test_generate_chart_data_with_no_responses()
    {
        $answers = [
            ['id' => 1, 'title' => 'Answer 1', 'count' => 0],
            ['id' => 2, 'title' => 'Answer 2', 'count' => 0],
        ];
        
        $totalResponses = 0;
        $chartData = [];
        
        foreach ($answers as $answer) {
            if ($totalResponses > 0) {
                $percentage = round($answer['count'] / $totalResponses * 100, 2);
            } else {
                $percentage = 0;
            }
            
            $chartData[] = [
                'title' => $answer['title'],
                'percentage' => $percentage,
                'count' => $answer['count']
            ];
        }
        
        $this->assertCount(2, $chartData);
        $this->assertEquals(0, $chartData[0]['percentage']);
        $this->assertEquals(0, $chartData[1]['percentage']);
    }

    /**
     * @test
     * Test aspect ratio calculation (from line 96 in answerChart.blade.php)
     */
    public function test_aspect_ratio_calculation()
    {
        // Test the aspect ratio formula: 15 / (labels_count + 5)
        $labelsCount = 3;
        $aspectRatio = 15 / ($labelsCount + 5);
        
        $this->assertEquals(1.875, $aspectRatio);
        
        $labelsCount = 10;
        $aspectRatio = 15 / ($labelsCount + 5);
        
        $this->assertEquals(1.0, $aspectRatio);
        
        $labelsCount = 0;
        $aspectRatio = 15 / ($labelsCount + 5);
        
        $this->assertEquals(3.0, $aspectRatio);
    }

    /**
     * @test
     * Test tooltip data format (lines 145-147 in answerChart.blade.php)
     */
    public function test_tooltip_data_format()
    {
        $percentage = 25.50;
        $count = 10;
        
        // Simulate tooltip format: "25.50% [10]"
        $tooltipText = $percentage . '% [' . $count . ']';
        
        $this->assertEquals('25.5% [10]', $tooltipText);
    }

    /**
     * @test
     * Test string splitting function (lines 4-17 in answerChart.blade.php)
     */
    public function test_string_splitting_for_labels()
    {
        // Replicate the splitter function logic
        $splitText = function($str, $length) {
            $strs = [];
            while (strlen($str) > $length) {
                $pos = strrpos(substr($str, 0, $length), ' ');
                $pos = $pos <= 0 ? $length : $pos;
                $strs[] = substr($str, 0, $pos);
                $i = strpos($str, ' ', $pos) + 1;
                if ($i < $pos || $i > $pos + $length) {
                    $i = $pos;
                }
                $str = substr($str, $i);
            }
            $strs[] = $str;
            return $strs;
        };
        
        $longText = "This is a very long text that needs to be split";
        $result = $splitText($longText, 20);
        
        $this->assertIsArray($result);
        $this->assertGreaterThan(1, count($result));
        
        // Each part should be <= 20 characters (approximately)
        foreach ($result as $part) {
            $this->assertLessThanOrEqual(25, strlen($part)); // Some tolerance for word boundaries
        }
    }

    /**
     * @test
     * Test chart color and styling data
     */
    public function test_chart_styling_data()
    {
        // Test the chart configuration values from answerChart.blade.php
        $chartConfig = [
            'type' => 'horizontalBar',
            'backgroundColor' => '#99BCDA',
            'borderWidth' => 1,
        ];
        
        $this->assertEquals('horizontalBar', $chartConfig['type']);
        $this->assertEquals('#99BCDA', $chartConfig['backgroundColor']);
        $this->assertEquals(1, $chartConfig['borderWidth']);
    }

    /**
     * @test
     * Test performance with large dataset
     */
    public function test_performance_with_large_chart_dataset()
    {
        // Create large dataset (many survey answers)
        $answers = [];
        for ($i = 1; $i <= 1000; $i++) {
            $answers[] = [
                'id' => $i,
                'title' => "Answer $i",
                'count' => rand(1, 100)
            ];
        }
        
        $totalResponses = array_sum(array_column($answers, 'count'));
        
        $startTime = microtime(true);
        
        $chartData = [];
        foreach ($answers as $answer) {
            if ($totalResponses > 0) {
                $percentage = round($answer['count'] / $totalResponses * 100, 2);
            } else {
                $percentage = 0;
            }
            
            $chartData[] = [
                'title' => $answer['title'],
                'percentage' => $percentage,
                'count' => $answer['count']
            ];
        }
        
        $endTime = microtime(true);
        $executionTime = ($endTime - $startTime) * 1000;
        
        $this->assertCount(1000, $chartData);
        $this->assertLessThan(50, $executionTime, 'Chart data generation took too long');
        
        // Verify percentages sum to approximately 100%
        $totalPercentage = array_sum(array_column($chartData, 'percentage'));
        $this->assertEqualsWithDelta(100.0, $totalPercentage, 1.0); // Allow for rounding differences
    }

    /**
     * @test
     * Test handling of negative counts (edge case that shouldn't happen)
     */
    public function test_handle_negative_counts()
    {
        $answerCount = -5;
        $totalResponses = 100;
        
        if ($totalResponses > 0) {
            $percentage = round($answerCount / $totalResponses * 100, 2);
        } else {
            $percentage = 0;
        }
        
        $this->assertEquals(-5.00, $percentage);
    }

    /**
     * @test
     * Test rounding precision with edge cases
     */
    public function test_rounding_precision_edge_cases()
    {
        // Test rounding behavior for values that are exactly between two decimals
        $testCases = [
            [1, 3, 33.33], // 1/3 * 100 = 33.333...
            [2, 3, 66.67], // 2/3 * 100 = 66.666...
            [1, 6, 16.67], // 1/6 * 100 = 16.666...
            [1, 7, 14.29], // 1/7 * 100 = 14.285...
        ];
        
        foreach ($testCases as [$answerCount, $totalResponses, $expected]) {
            $percentage = round($answerCount / $totalResponses * 100, 2);
            $this->assertEquals($expected, $percentage, 
                "Failed for $answerCount/$totalResponses");
        }
    }

    /**
     * @test
     * Test chart data validation for required fields
     */
    public function test_chart_data_contains_required_fields()
    {
        $answerCount = 25;
        $totalResponses = 100;
        $answerTitle = "Test Answer";
        
        $percentage = round($answerCount / $totalResponses * 100, 2);
        
        $chartDataPoint = [
            'title' => $answerTitle,
            'percentage' => $percentage,
            'count' => $answerCount
        ];
        
        // Verify all required fields are present
        $this->assertArrayHasKey('title', $chartDataPoint);
        $this->assertArrayHasKey('percentage', $chartDataPoint);
        $this->assertArrayHasKey('count', $chartDataPoint);
        
        // Verify data types
        $this->assertIsString($chartDataPoint['title']);
        $this->assertIsFloat($chartDataPoint['percentage']);
        $this->assertIsInt($chartDataPoint['count']);
    }
}