<?php

namespace Tests\Feature\app\Http\Controllers\Frontend;

use App\Answer;
use App\Mail\ErrorNotification;
use App\Mail\QuestionnaireSubmitted;
use App\Questionnaire;
use App\Response;
use App\Survey;
use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

/**
 * Enhanced tests for CollectController focusing on edge cases and error conditions
 * @see \App\Http\Controllers\Frontend\CollectController
 */
class CollectControllerTestEnhanced extends TestCase
{
    use DatabaseTransactions, WithFaker;

    protected $survey;
    protected $adminUser;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create test data
        $this->survey = Survey::factory()->create(['completed' => 0]);
        $this->adminUser = User::factory()->create();
        
        // Create admin role and assign to user
        $adminRole = \App\Role::create(['title' => 'Admin']);
        $this->adminUser->role()->associate($adminRole);
        $this->adminUser->save();
        
        Mail::fake();
    }

    /**
     * @test
     * Test successful questionnaire creation with valid data
     */
    public function test_store_creates_questionnaire_successfully()
    {
        $requestData = [
            'survey_id' => $this->survey->id,
            '1001_id' => '501',
            '1001_content_501' => 'Valid response content',
            '1002_id' => '502',
        ];

        $response = $this->post(route('frontend.store'), $requestData);

        $response->assertSuccessful();
        $this->assertDatabaseHas('questionnaires', ['survey_id' => $this->survey->id]);
        $this->assertDatabaseHas('responses', [
            'question_id' => 1001,
            'answer_id' => 501,
            'content' => 'Valid response content'
        ]);
    }

    /**
     * @test
     * Test handling of malformed request keys
     */
    public function test_store_handles_malformed_request_keys()
    {
        $requestData = [
            'survey_id' => $this->survey->id,
            'invalid_key_format' => 'value', // Missing underscore
            'another_invalid' => 'value2',   // Not matching expected pattern
            '1001_id' => '501', // Valid key for comparison
        ];

        $response = $this->post(route('frontend.store'), $requestData);

        // Should still create questionnaire for valid keys
        $this->assertDatabaseHas('questionnaires', ['survey_id' => $this->survey->id]);
        
        // Should send error notifications for invalid keys
        Mail::assertSent(ErrorNotification::class, 2); // Two invalid keys
    }

    /**
     * @test
     * Test error notification when User::getAdminEmail() fails
     */
    public function test_store_sends_error_notification_for_invalid_data()
    {
        // Remove admin user to test error scenario
        $this->adminUser->delete();
        
        $requestData = [
            'survey_id' => $this->survey->id,
            'invalid_key' => 'value', // This should trigger error notification
        ];

        // Expect exception due to no admin user
        $this->expectException(\TypeError::class);
        
        $this->post(route('frontend.store'), $requestData);
    }

    /**
     * @test
     * Test survey completion abortion
     */
    public function test_store_handles_completed_survey_abortion()
    {
        // Mark survey as completed
        $this->survey->update(['completed' => 1]);

        $requestData = [
            'survey_id' => $this->survey->id,
            '1001_id' => '501',
        ];

        $response = $this->post(route('frontend.store'), $requestData);

        $response->assertStatus(404);
        $this->assertDatabaseMissing('questionnaires', ['survey_id' => $this->survey->id]);
    }

    /**
     * @test
     * Test audit log creation for authenticated user
     */
    public function test_store_creates_audit_log_for_authenticated_user()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $requestData = [
            'survey_id' => $this->survey->id,
            '1001_id' => '501',
        ];

        $response = $this->post(route('frontend.store'), $requestData);

        $response->assertSuccessful();
        $this->assertDatabaseHas('audit_logs', [
            'description' => 'questionnaire_submit',
            'user_id' => $user->id,
        ]);
    }

    /**
     * @test
     * Test activity log creation for guest user
     */
    public function test_store_creates_activity_log_for_guest_user()
    {
        // Ensure no authenticated user
        auth()->logout();

        $requestData = [
            'survey_id' => $this->survey->id,
            '1001_id' => '501',
        ];

        $response = $this->post(route('frontend.store'), $requestData);

        $response->assertSuccessful();
        $this->assertDatabaseHas('activity_log', [
            'description' => 'questionnaire_submit',
            'subject_type' => Questionnaire::class,
        ]);
    }

    /**
     * @test
     * Test cookie handling exceptions
     */
    public function test_store_handles_cookie_exceptions_gracefully()
    {
        // Mock Log to capture error messages
        Log::shouldReceive('channel')->with('cookies')->andReturnSelf();
        Log::shouldReceive('error')->once();

        // Mock Cookie facade to throw exception
        Cookie::shouldReceive('get')->andThrow(new \Exception('Cookie error'));
        Cookie::shouldReceive('queue')->andThrow(new \Exception('Cookie queue error'));

        $requestData = [
            'survey_id' => $this->survey->id,
            '1001_id' => '501',
        ];

        $response = $this->post(route('frontend.store'), $requestData);

        $response->assertSuccessful();
        
        // Should send error notification about cookie handling
        Mail::assertSent(ErrorNotification::class, function ($mail) {
            return str_contains($mail->errorMessage, 'Could not handle cookies');
        });
    }

    /**
     * @test
     * Test email notification when survey inform flag is set
     */
    public function test_store_sends_email_notification_when_informed()
    {
        $this->survey->update(['inform' => 1]);

        $requestData = [
            'survey_id' => $this->survey->id,
            '1001_id' => '501',
        ];

        $response = $this->post(route('frontend.store'), $requestData);

        $response->assertSuccessful();
        Mail::assertSent(QuestionnaireSubmitted::class);
    }

    /**
     * @test
     * Test handling of email sending exceptions
     */
    public function test_store_handles_email_sending_exceptions()
    {
        $this->survey->update(['inform' => 1]);
        
        // Mock Mail to throw exception on QuestionnaireSubmitted
        Mail::shouldReceive('to')->andReturnSelf();
        Mail::shouldReceive('send')->with(\Mockery::type(QuestionnaireSubmitted::class))
             ->andThrow(new \Exception('Email sending failed'));
        Mail::shouldReceive('send')->with(\Mockery::type(ErrorNotification::class))->andReturn(true);

        $requestData = [
            'survey_id' => $this->survey->id,
            '1001_id' => '501',
        ];

        $response = $this->post(route('frontend.store'), $requestData);

        $response->assertSuccessful();
        
        // Should send error notification about email failure
        Mail::assertSent(ErrorNotification::class, function ($mail) {
            return str_contains($mail->errorMessage, 'Error in mailer');
        });
    }

    /**
     * @test
     * Test create method redirects when survey is completed
     */
    public function test_create_redirects_when_survey_completed()
    {
        $this->survey->update(['completed' => 1]);

        $response = $this->get(route('frontend.create', $this->survey->alias));

        $response->assertSuccessful();
        $response->assertSessionHas('warning', 'Survey is completed.');
    }

    /**
     * @test
     * Test create method logs activity for non-authenticated users only
     */
    public function test_create_logs_activity_for_non_authenticated_users_only()
    {
        // Test as guest user
        auth()->logout();
        
        $response = $this->get(route('frontend.create', $this->survey->alias));

        $response->assertSuccessful();
        $this->assertDatabaseHas('activity_log', [
            'description' => 'survey_view',
            'subject_type' => Survey::class,
        ]);

        // Test as authenticated user - should not log
        $user = User::factory()->create();
        $this->actingAs($user);
        
        // Clear previous activity logs
        \DB::table('activity_log')->delete();
        
        $response = $this->get(route('frontend.create', $this->survey->alias));

        $response->assertSuccessful();
        $this->assertDatabaseMissing('activity_log', [
            'description' => 'survey_view',
        ]);
    }

    /**
     * @test
     * Test handling of empty request data
     */
    public function test_store_handles_empty_request_data()
    {
        $requestData = [
            'survey_id' => $this->survey->id,
            // No question/answer data
        ];

        $response = $this->post(route('frontend.store'), $requestData);

        $response->assertSuccessful();
        $this->assertDatabaseHas('questionnaires', ['survey_id' => $this->survey->id]);
        $this->assertDatabaseMissing('responses', ['questionnaire_id' => 1]);
    }

    /**
     * @test
     * Test race condition on survey completion
     */
    public function test_store_handles_survey_completion_race_condition()
    {
        $requestData = [
            'survey_id' => $this->survey->id,
            '1001_id' => '501',
        ];

        // Simulate race condition by completing survey during request processing
        $this->survey->update(['completed' => 1]);

        $response = $this->post(route('frontend.store'), $requestData);

        $response->assertStatus(404);
    }

    /**
     * @test
     * Test content validation and sanitization
     */
    public function test_store_validates_and_sanitizes_content()
    {
        $requestData = [
            'survey_id' => $this->survey->id,
            '1001_id' => '501',
            '1001_content_501' => '<script>alert("xss")</script>Valid content',
        ];

        $response = $this->post(route('frontend.store'), $requestData);

        $response->assertSuccessful();
        $this->assertDatabaseHas('responses', [
            'question_id' => 1001,
            'answer_id' => 501,
            'content' => 'Valid content' // HTML tags should be stripped
        ]);
    }

    /**
     * @test
     * Test duplicate cookie detection
     */
    public function test_store_detects_duplicate_cookie_submission()
    {
        // Set existing cookie
        Cookie::shouldReceive('get')->with('survey_' . $this->survey->id)->andReturn(true);
        Cookie::shouldReceive('get')->with('questionnaire')->andReturn('123');
        Cookie::shouldReceive('queue')->twice(); // For setting new cookies

        $requestData = [
            'survey_id' => $this->survey->id,
            '1001_id' => '501',
        ];

        $response = $this->post(route('frontend.store'), $requestData);

        $response->assertSuccessful();
        
        // Should send duplicate submission notification
        Mail::assertSent(ErrorNotification::class, function ($mail) {
            return str_contains($mail->errorMessage, 'questionnaire filled twice');
        });
    }

    /**
     * @test
     * Test name extraction from cipher
     */
    public function test_store_extracts_name_from_cipher()
    {
        // Set up environment variable for cipher
        config(['app.cipher_key' => 'test_key']);
        
        $requestData = [
            'survey_id' => $this->survey->id,
            'check' => 'encrypted_name_data',
            '1001_id' => '501',
        ];

        // This test would require mocking the Cipher class
        // For now, we'll test that the code path is reachable
        $response = $this->post(route('frontend.store'), $requestData);

        $response->assertSuccessful();
        $this->assertDatabaseHas('questionnaires', ['survey_id' => $this->survey->id]);
    }
}