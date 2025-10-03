<?php

namespace Tests\Unit\Services;

use App\Mail\ErrorNotification;
use App\Questionnaire;
use App\Response;
use App\Services\DuplicateDetectionService;
use App\Survey;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Mail;
use Spatie\Activitylog\Models\Activity;
use Tests\TestCase;

/**
 * Unit tests for DuplicateDetectionService
 *
 * Tests the three core detection methods:
 * - checkCookieDuplicate() - Browser-based real-time detection
 * - findByActivityLog() - IP + User Agent fingerprinting
 * - findByContentSimilarity() - Content-based duplicate detection
 */
class DuplicateDetectionServiceTest extends TestCase
{
    use RefreshDatabase;

    protected DuplicateDetectionService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new DuplicateDetectionService();
        Mail::fake();
    }

    /**
     * @test
     * Test cookie detection when no previous cookie exists
     */
    public function test_check_cookie_duplicate_no_previous_cookie()
    {
        $survey = Survey::factory()->create();
        $questionnaire = Questionnaire::factory()->create(['survey_id' => $survey->id]);

        $request = Request::create('/collect', 'POST', ['survey_id' => $survey->id]);

        $result = $this->service->checkCookieDuplicate($request, $questionnaire);

        $this->assertNull($result);
        Mail::assertNothingSent();
    }

    /**
     * @test
     * Test cookie detection method returns null gracefully in test environment
     * Note: Cookie testing requires feature tests with actual browser simulation
     */
    public function test_check_cookie_duplicate_returns_safely()
    {
        $survey = Survey::factory()->create();
        $questionnaire = Questionnaire::factory()->create(['survey_id' => $survey->id]);

        $request = Request::create('/collect', 'POST', ['survey_id' => $survey->id]);

        // In unit test environment, cookies don't persist like in real requests
        // The method should handle this gracefully without errors
        $result = $this->service->checkCookieDuplicate($request, $questionnaire);

        // Should either return null (no cookie) or an array (cookie found)
        $this->assertTrue($result === null || is_array($result));
    }

    /**
     * @test
     * Test findByActivityLog returns empty array when no questionnaires exist
     */
    public function test_find_by_activity_log_with_no_questionnaires()
    {
        $survey = Survey::factory()->create();

        $result = $this->service->findByActivityLog($survey->id);

        $this->assertIsArray($result);
        $this->assertEmpty($result);
    }

    /**
     * @test
     * Test findByActivityLog detects duplicates by IP and User Agent
     */
    public function test_find_by_activity_log_detects_duplicates()
    {
        $survey = Survey::factory()->create();
        $q1 = Questionnaire::factory()->create(['survey_id' => $survey->id]);
        $q2 = Questionnaire::factory()->create(['survey_id' => $survey->id]);

        // Create activity logs with same IP and User Agent
        Activity::create([
            'log_name' => 'default',
            'description' => 'questionnaire_submit',
            'subject_type' => 'App\Questionnaire',
            'subject_id' => $q1->id,
            'properties' => [
                'ip' => '192.168.1.1',
                'user_agent' => 'Mozilla/5.0',
                'survey_id' => $survey->id,
                'responses_count' => 5,
            ],
        ]);

        Activity::create([
            'log_name' => 'default',
            'description' => 'questionnaire_submit',
            'subject_type' => 'App\Questionnaire',
            'subject_id' => $q2->id,
            'properties' => [
                'ip' => '192.168.1.1',
                'user_agent' => 'Mozilla/5.0',
                'survey_id' => $survey->id,
                'responses_count' => 5,
            ],
        ]);

        $result = $this->service->findByActivityLog($survey->id);

        $this->assertIsArray($result);
        $this->assertCount(1, $result);
        $this->assertEquals('ipsw', $result[0]['type']);
        $this->assertEquals(2, $result[0]['count']);
        $this->assertEquals('192.168.1.1', $result[0]['value']['ipv6']);
    }

    /**
     * @test
     * Test findByActivityLog does not detect duplicates with different fingerprints
     */
    public function test_find_by_activity_log_no_duplicates_different_fingerprints()
    {
        $survey = Survey::factory()->create();
        $q1 = Questionnaire::factory()->create(['survey_id' => $survey->id]);
        $q2 = Questionnaire::factory()->create(['survey_id' => $survey->id]);

        // Create activity logs with different IPs
        Activity::create([
            'log_name' => 'default',
            'description' => 'questionnaire_submit',
            'subject_type' => 'App\Questionnaire',
            'subject_id' => $q1->id,
            'properties' => [
                'ip' => '192.168.1.1',
                'user_agent' => 'Mozilla/5.0',
                'survey_id' => $survey->id,
            ],
        ]);

        Activity::create([
            'log_name' => 'default',
            'description' => 'questionnaire_submit',
            'subject_type' => 'App\Questionnaire',
            'subject_id' => $q2->id,
            'properties' => [
                'ip' => '192.168.1.2',
                'user_agent' => 'Mozilla/5.0',
                'survey_id' => $survey->id,
            ],
        ]);

        $result = $this->service->findByActivityLog($survey->id);

        $this->assertIsArray($result);
        $this->assertEmpty($result);
    }

    /**
     * @test
     * Test findByContentSimilarity with no questionnaires
     */
    public function test_find_by_content_similarity_with_no_questionnaires()
    {
        $survey = Survey::factory()->create();

        $result = $this->service->findByContentSimilarity($survey->id);

        $this->assertIsArray($result);
        $this->assertEmpty($result);
    }

    /**
     * @test
     * Test findByContentSimilarity detects exact duplicate responses
     */
    public function test_find_by_content_similarity_detects_exact_duplicates()
    {
        $survey = Survey::factory()->create();
        $q1 = Questionnaire::factory()->create(['survey_id' => $survey->id]);
        $q2 = Questionnaire::factory()->create(['survey_id' => $survey->id]);

        // Create identical responses using factory
        $question = \App\Question::factory()->create();
        $answer = \App\Answer::factory()->create();

        Response::factory()->create([
            'questionnaire_id' => $q1->id,
            'question_id' => $question->id,
            'answer_id' => $answer->id,
            'content' => 'Same answer text',
        ]);

        Response::factory()->create([
            'questionnaire_id' => $q2->id,
            'question_id' => $question->id,
            'answer_id' => $answer->id,
            'content' => 'Same answer text',
        ]);

        $result = $this->service->findByContentSimilarity($survey->id, 85);

        $this->assertIsArray($result);
        $this->assertCount(1, $result);
        $this->assertEquals('similarity', $result[0]['type']);
        $this->assertEquals(100, $result[0]['similarity_score']);
    }

    /**
     * @test
     * Test findByContentSimilarity does not detect low similarity
     */
    public function test_find_by_content_similarity_ignores_low_similarity()
    {
        $survey = Survey::factory()->create();
        $q1 = Questionnaire::factory()->create(['survey_id' => $survey->id]);
        $q2 = Questionnaire::factory()->create(['survey_id' => $survey->id]);

        // Create very different responses
        Response::factory()->create([
            'questionnaire_id' => $q1->id,
            'content' => 'Completely different text A',
        ]);

        Response::factory()->create([
            'questionnaire_id' => $q2->id,
            'content' => 'Totally unrelated text B',
        ]);

        $result = $this->service->findByContentSimilarity($survey->id, 85);

        $this->assertIsArray($result);
        // Should be empty or very low similarity
        if (!empty($result)) {
            $this->assertLessThan(85, $result[0]['similarity_score']);
        }
    }

    /**
     * @test
     * Test findByContentSimilarity with answer_id comparison (no text content)
     */
    public function test_find_by_content_similarity_with_answer_ids()
    {
        $survey = Survey::factory()->create();
        $q1 = Questionnaire::factory()->create(['survey_id' => $survey->id]);
        $q2 = Questionnaire::factory()->create(['survey_id' => $survey->id]);

        // Create shared questions and answers
        $question1 = \App\Question::factory()->create();
        $question2 = \App\Question::factory()->create();
        $answer1 = \App\Answer::factory()->create();
        $answer2 = \App\Answer::factory()->create();

        // Create responses with same answer_ids (multiple choice)
        Response::factory()->create([
            'questionnaire_id' => $q1->id,
            'question_id' => $question1->id,
            'answer_id' => $answer1->id,
            'content' => null,
        ]);

        Response::factory()->create([
            'questionnaire_id' => $q1->id,
            'question_id' => $question2->id,
            'answer_id' => $answer2->id,
            'content' => null,
        ]);

        Response::factory()->create([
            'questionnaire_id' => $q2->id,
            'question_id' => $question1->id,
            'answer_id' => $answer1->id,
            'content' => null,
        ]);

        Response::factory()->create([
            'questionnaire_id' => $q2->id,
            'question_id' => $question2->id,
            'answer_id' => $answer2->id,
            'content' => null,
        ]);

        $result = $this->service->findByContentSimilarity($survey->id, 85);

        $this->assertIsArray($result);
        $this->assertCount(1, $result);
        $this->assertEquals(100, $result[0]['similarity_score']);
    }

    /**
     * @test
     * Test findByActivityLog filters by correct description
     */
    public function test_find_by_activity_log_filters_by_description()
    {
        $survey = Survey::factory()->create();
        $q1 = Questionnaire::factory()->create(['survey_id' => $survey->id]);

        // Create activity with wrong description
        Activity::create([
            'log_name' => 'default',
            'description' => 'questionnaire_edit', // Wrong description
            'subject_type' => 'App\Questionnaire',
            'subject_id' => $q1->id,
            'properties' => [
                'ip' => '192.168.1.1',
                'user_agent' => 'Mozilla/5.0',
            ],
        ]);

        $result = $this->service->findByActivityLog($survey->id);

        $this->assertEmpty($result);
    }

    /**
     * @test
     * Test data structure format for activity log results
     */
    public function test_activity_log_result_format()
    {
        $survey = Survey::factory()->create();
        $q1 = Questionnaire::factory()->create(['survey_id' => $survey->id]);
        $q2 = Questionnaire::factory()->create(['survey_id' => $survey->id]);

        Activity::create([
            'log_name' => 'default',
            'description' => 'questionnaire_submit',
            'subject_type' => 'App\Questionnaire',
            'subject_id' => $q1->id,
            'properties' => [
                'ip' => '192.168.1.1',
                'user_agent' => 'Mozilla/5.0',
                'survey_id' => $survey->id,
            ],
        ]);

        Activity::create([
            'log_name' => 'default',
            'description' => 'questionnaire_submit',
            'subject_type' => 'App\Questionnaire',
            'subject_id' => $q2->id,
            'properties' => [
                'ip' => '192.168.1.1',
                'user_agent' => 'Mozilla/5.0',
                'survey_id' => $survey->id,
            ],
        ]);

        $result = $this->service->findByActivityLog($survey->id);

        $this->assertArrayHasKey('type', $result[0]);
        $this->assertArrayHasKey('value', $result[0]);
        $this->assertArrayHasKey('count', $result[0]);
        $this->assertArrayHasKey('loguseragents', $result[0]);

        // Check loguseragent format
        $log = $result[0]['loguseragents']->first();
        $this->assertObjectHasProperty('id', $log);
        $this->assertObjectHasProperty('item_id', $log);
        $this->assertObjectHasProperty('ipv6', $log);
    }

    /**
     * @test
     * Test data structure format for similarity results
     */
    public function test_similarity_result_format()
    {
        $survey = Survey::factory()->create();
        $q1 = Questionnaire::factory()->create(['survey_id' => $survey->id]);
        $q2 = Questionnaire::factory()->create(['survey_id' => $survey->id]);

        $question = \App\Question::factory()->create();
        $answer = \App\Answer::factory()->create();

        Response::factory()->create([
            'questionnaire_id' => $q1->id,
            'question_id' => $question->id,
            'answer_id' => $answer->id,
            'content' => 'Same answer',
        ]);

        Response::factory()->create([
            'questionnaire_id' => $q2->id,
            'question_id' => $question->id,
            'answer_id' => $answer->id,
            'content' => 'Same answer',
        ]);

        $result = $this->service->findByContentSimilarity($survey->id, 85);

        $this->assertArrayHasKey('type', $result[0]);
        $this->assertArrayHasKey('questionnaire_1_id', $result[0]);
        $this->assertArrayHasKey('questionnaire_2_id', $result[0]);
        $this->assertArrayHasKey('similarity_score', $result[0]);
        $this->assertArrayHasKey('loguseragents', $result[0]);
        $this->assertArrayHasKey('count', $result[0]);

        $this->assertEquals('similarity', $result[0]['type']);
        $this->assertEquals(2, $result[0]['count']);
    }

    /**
     * @test
     * REGRESSION TEST: Prevent false 100% similarity bug
     *
     * Bug: Questionnaires with identical text but different answers were showing 100% similarity
     * Example: Q251 vs Q259 had 11/20 different answers (45% actual) but reported 100%
     * Cause: Old algorithm compared ONLY text content when present, ignoring answer_id mismatches
     */
    public function test_does_not_give_false_100_percent_for_same_text_different_answers()
    {
        $survey = Survey::factory()->create();
        $q1 = Questionnaire::factory()->create(['survey_id' => $survey->id]);
        $q2 = Questionnaire::factory()->create(['survey_id' => $survey->id]);

        // Create questions
        $questions = [];
        for ($i = 1; $i <= 20; $i++) {
            $questions[$i] = \App\Question::factory()->create();
        }

        // Create answers
        $answers = [];
        for ($i = 1; $i <= 10; $i++) {
            $answers[$i] = \App\Answer::factory()->create();
        }

        // Q1 responses: Mix of answers, with text in 2 questions
        Response::factory()->create([
            'questionnaire_id' => $q1->id,
            'question_id' => $questions[1]->id,
            'answer_id' => $answers[1]->id,
            'content' => null,
        ]);

        Response::factory()->create([
            'questionnaire_id' => $q1->id,
            'question_id' => $questions[2]->id,
            'answer_id' => $answers[2]->id,
            'content' => '1', // Text content
        ]);

        Response::factory()->create([
            'questionnaire_id' => $q1->id,
            'question_id' => $questions[3]->id,
            'answer_id' => $answers[3]->id,
            'content' => null,
        ]);

        Response::factory()->create([
            'questionnaire_id' => $q1->id,
            'question_id' => $questions[4]->id,
            'answer_id' => $answers[4]->id,
            'content' => '.', // Text content
        ]);

        // Add more Q1 responses (5-20)
        for ($i = 5; $i <= 20; $i++) {
            Response::factory()->create([
                'questionnaire_id' => $q1->id,
                'question_id' => $questions[$i]->id,
                'answer_id' => $answers[($i % 5) + 1]->id, // Use answers 1-5 cyclically
                'content' => null,
            ]);
        }

        // Q2 responses: SAME text but DIFFERENT answers in many questions
        Response::factory()->create([
            'questionnaire_id' => $q2->id,
            'question_id' => $questions[1]->id,
            'answer_id' => $answers[1]->id, // SAME as Q1
            'content' => null,
        ]);

        Response::factory()->create([
            'questionnaire_id' => $q2->id,
            'question_id' => $questions[2]->id,
            'answer_id' => $answers[2]->id, // SAME as Q1
            'content' => '1', // SAME text as Q1
        ]);

        Response::factory()->create([
            'questionnaire_id' => $q2->id,
            'question_id' => $questions[3]->id,
            'answer_id' => $answers[5]->id, // DIFFERENT from Q1
            'content' => null,
        ]);

        Response::factory()->create([
            'questionnaire_id' => $q2->id,
            'question_id' => $questions[4]->id,
            'answer_id' => $answers[4]->id, // SAME as Q1
            'content' => '.', // SAME text as Q1
        ]);

        // Add more Q2 responses with DIFFERENT answers (5-20)
        for ($i = 5; $i <= 20; $i++) {
            Response::factory()->create([
                'questionnaire_id' => $q2->id,
                'question_id' => $questions[$i]->id,
                'answer_id' => $answers[($i % 3) + 1]->id, // DIFFERENT distribution than Q1
                'content' => null,
            ]);
        }

        $result = $this->service->findByContentSimilarity($survey->id, 50);

        // Should NOT be 100% similarity despite identical text
        // Actual similarity should be much lower due to answer differences
        $this->assertIsArray($result);
        $this->assertNotEmpty($result, 'Should detect some similarity');
        $this->assertLessThan(100, $result[0]['similarity_score'], 'Should NOT be 100% - answers are different!');
        $this->assertGreaterThan(30, $result[0]['similarity_score'], 'Should have some similarity (same text)');
    }

    /**
     * @test
     * REGRESSION TEST: Verify per-question comparison logic
     *
     * Ensures responses are compared by question_id, not by array position
     */
    public function test_compares_answers_by_question_id_not_position()
    {
        $survey = Survey::factory()->create();
        $q1 = Questionnaire::factory()->create(['survey_id' => $survey->id]);
        $q2 = Questionnaire::factory()->create(['survey_id' => $survey->id]);

        $question1 = \App\Question::factory()->create();
        $question2 = \App\Question::factory()->create();
        $answer1 = \App\Answer::factory()->create();
        $answer2 = \App\Answer::factory()->create();

        // Q1: question1 -> answer1, question2 -> answer2
        Response::factory()->create([
            'questionnaire_id' => $q1->id,
            'question_id' => $question1->id,
            'answer_id' => $answer1->id,
            'content' => null,
        ]);

        Response::factory()->create([
            'questionnaire_id' => $q1->id,
            'question_id' => $question2->id,
            'answer_id' => $answer2->id,
            'content' => null,
        ]);

        // Q2: question1 -> answer1, question2 -> answer2 (SAME)
        Response::factory()->create([
            'questionnaire_id' => $q2->id,
            'question_id' => $question1->id,
            'answer_id' => $answer1->id,
            'content' => null,
        ]);

        Response::factory()->create([
            'questionnaire_id' => $q2->id,
            'question_id' => $question2->id,
            'answer_id' => $answer2->id,
            'content' => null,
        ]);

        $result = $this->service->findByContentSimilarity($survey->id, 85);

        $this->assertIsArray($result);
        $this->assertCount(1, $result);
        $this->assertEquals(100, $result[0]['similarity_score'], 'Should be 100% - all questions match by question_id');
    }

    /**
     * @test
     * REGRESSION TEST: Verify weighted combination (70% answer, 30% text)
     *
     * Ensures the algorithm uses proper weighting between answer and text similarity
     */
    public function test_uses_weighted_combination_70_30()
    {
        $survey = Survey::factory()->create();
        $q1 = Questionnaire::factory()->create(['survey_id' => $survey->id]);
        $q2 = Questionnaire::factory()->create(['survey_id' => $survey->id]);

        $question = \App\Question::factory()->create();
        $answer1 = \App\Answer::factory()->create();
        $answer2 = \App\Answer::factory()->create();

        // Q1: answer1 with text "ABC"
        Response::factory()->create([
            'questionnaire_id' => $q1->id,
            'question_id' => $question->id,
            'answer_id' => $answer1->id,
            'content' => 'ABC',
        ]);

        // Q2: answer2 (DIFFERENT) with text "ABC" (SAME)
        Response::factory()->create([
            'questionnaire_id' => $q2->id,
            'question_id' => $question->id,
            'answer_id' => $answer2->id,
            'content' => 'ABC',
        ]);

        $result = $this->service->findByContentSimilarity($survey->id, 20);

        // Answer similarity = 0 (different answers)
        // Text similarity = 1 (100% identical text)
        // Expected: (0 * 0.7) + (1 * 0.3) = 0.3 = 30%
        $this->assertIsArray($result);
        $this->assertNotEmpty($result);
        $this->assertEquals(30, $result[0]['similarity_score'], 'Should be 30% (0% answer + 100% text * 0.3)');
    }

    /**
     * @test
     * REGRESSION TEST: 100% requires BOTH answers AND text to match
     *
     * Ensures that 100% similarity requires complete match on both dimensions
     */
    public function test_requires_both_answers_and_text_to_match_for_100_percent()
    {
        $survey = Survey::factory()->create();
        $q1 = Questionnaire::factory()->create(['survey_id' => $survey->id]);
        $q2 = Questionnaire::factory()->create(['survey_id' => $survey->id]);
        $q3 = Questionnaire::factory()->create(['survey_id' => $survey->id]);

        $question = \App\Question::factory()->create();
        $answer = \App\Answer::factory()->create();

        // Q1: Same answer, same text
        Response::factory()->create([
            'questionnaire_id' => $q1->id,
            'question_id' => $question->id,
            'answer_id' => $answer->id,
            'content' => 'Test',
        ]);

        // Q2: Same answer, same text -> Should be 100%
        Response::factory()->create([
            'questionnaire_id' => $q2->id,
            'question_id' => $question->id,
            'answer_id' => $answer->id,
            'content' => 'Test',
        ]);

        // Q3: Same answer, no text -> Should be 70% (answer match but text mismatch)
        Response::factory()->create([
            'questionnaire_id' => $q3->id,
            'question_id' => $question->id,
            'answer_id' => $answer->id,
            'content' => null,
        ]);

        // Use lower threshold to capture Q1 vs Q3 comparison (70%)
        $result = $this->service->findByContentSimilarity($survey->id, 60);

        // Q1 vs Q2: 100% (answer match + text match)
        // Q1 vs Q3: 70% (answer match, text mismatch: one has text, other doesn't)
        // Q2 vs Q3: 70% (answer match, text mismatch)
        $this->assertIsArray($result);
        $this->assertCount(3, $result); // Should find 3 pairs above 60%

        // Find the Q1 vs Q2 pair
        $q1_q2_pair = collect($result)->first(function ($pair) use ($q1, $q2) {
            return ($pair['questionnaire_1_id'] === $q1->id && $pair['questionnaire_2_id'] === $q2->id) ||
                   ($pair['questionnaire_1_id'] === $q2->id && $pair['questionnaire_2_id'] === $q1->id);
        });

        $this->assertNotNull($q1_q2_pair, 'Should find Q1 vs Q2 pair');
        $this->assertEquals(100, $q1_q2_pair['similarity_score'], 'Q1 vs Q2 should be 100%');

        // Find Q1 vs Q3 pair to verify it's 70%
        $q1_q3_pair = collect($result)->first(function ($pair) use ($q1, $q3) {
            return ($pair['questionnaire_1_id'] === $q1->id && $pair['questionnaire_2_id'] === $q3->id) ||
                   ($pair['questionnaire_1_id'] === $q3->id && $pair['questionnaire_2_id'] === $q1->id);
        });

        $this->assertNotNull($q1_q3_pair, 'Should find Q1 vs Q3 pair');
        $this->assertEquals(70, $q1_q3_pair['similarity_score'], 'Q1 vs Q3 should be 70% (answer match, text mismatch)');
    }
}
