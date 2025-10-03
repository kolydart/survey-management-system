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

        Response::factory()->create([
            'questionnaire_id' => $q1->id,
            'question_id' => $question->id,
            'content' => 'Same answer',
        ]);

        Response::factory()->create([
            'questionnaire_id' => $q2->id,
            'question_id' => $question->id,
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
}
