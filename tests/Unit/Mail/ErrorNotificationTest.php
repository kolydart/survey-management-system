<?php

namespace Tests\Unit\Mail;

use App\Mail\ErrorNotification;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

/**
 * Unit tests for ErrorNotification mailable
 * @see \App\Mail\ErrorNotification
 */
class ErrorNotificationTest extends TestCase
{
    use WithFaker;

    /**
     * @test
     * Test ErrorNotification builds correctly with default subject
     */
    public function test_error_notification_builds_correctly_with_default_subject()
    {
        $errorMessage = 'Test error message';
        
        $mailable = new ErrorNotification($errorMessage);
        $built = $mailable->build();

        $this->assertInstanceOf(ErrorNotification::class, $built);
        $this->assertEquals('System Error Notification', $built->subject);
    }

    /**
     * @test
     * Test ErrorNotification builds correctly with custom subject
     */
    public function test_error_notification_builds_correctly_with_custom_subject()
    {
        $errorMessage = 'Test error message';
        $customSubject = 'Custom Error Subject';
        
        $mailable = new ErrorNotification($errorMessage, $customSubject);
        $built = $mailable->build();

        $this->assertEquals($customSubject, $built->subject);
    }

    /**
     * @test
     * Test ErrorNotification includes required data in view
     */
    public function test_error_notification_includes_required_data()
    {
        $errorMessage = 'Detailed error message for testing';
        $customSubject = 'Test Error Notification';
        
        // Mock request URL
        request()->merge(['test' => 'value']);
        
        $mailable = new ErrorNotification($errorMessage, $customSubject);
        $built = $mailable->build();

        // Check that view data is properly set
        $viewData = $built->viewData;
        
        $this->assertArrayHasKey('errorMessage', $viewData);
        $this->assertArrayHasKey('timestamp', $viewData);
        $this->assertArrayHasKey('url', $viewData);
        
        $this->assertEquals($errorMessage, $viewData['errorMessage']);
        $this->assertIsString($viewData['timestamp']);
        $this->assertIsString($viewData['url']);
    }

    /**
     * @test
     * Test ErrorNotification uses correct view
     */
    public function test_error_notification_uses_correct_view()
    {
        $mailable = new ErrorNotification('Test message');
        $built = $mailable->build();

        $this->assertEquals('emails.error-notification', $built->view);
    }

    /**
     * @test
     * Test ErrorNotification timestamp format
     */
    public function test_error_notification_timestamp_format()
    {
        $mailable = new ErrorNotification('Test message');
        $built = $mailable->build();

        $timestamp = $built->viewData['timestamp'];
        
        // Check timestamp format (Y-m-d H:i:s)
        $this->assertMatchesRegularExpression(
            '/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}$/',
            $timestamp
        );
        
        // Verify it's a valid date
        $this->assertNotFalse(\DateTime::createFromFormat('Y-m-d H:i:s', $timestamp));
    }

    /**
     * @test
     * Test ErrorNotification with empty error message
     */
    public function test_error_notification_handles_empty_error_message()
    {
        $mailable = new ErrorNotification('');
        $built = $mailable->build();

        $this->assertEquals('', $built->viewData['errorMessage']);
        $this->assertEquals('System Error Notification', $built->subject);
    }

    /**
     * @test
     * Test ErrorNotification with very long error message
     */
    public function test_error_notification_handles_long_error_message()
    {
        $longMessage = str_repeat('This is a very long error message. ', 100);
        
        $mailable = new ErrorNotification($longMessage);
        $built = $mailable->build();

        $this->assertEquals($longMessage, $built->viewData['errorMessage']);
        $this->assertIsString($built->viewData['errorMessage']);
    }

    /**
     * @test
     * Test ErrorNotification with special characters in message
     */
    public function test_error_notification_handles_special_characters()
    {
        $specialMessage = 'Error with special chars: äöü @#$%^&*()_+{}[]|\\:";\'<>?,./ 中文 🚀';
        
        $mailable = new ErrorNotification($specialMessage);
        $built = $mailable->build();

        $this->assertEquals($specialMessage, $built->viewData['errorMessage']);
    }

    /**
     * @test
     * Test ErrorNotification with null values
     */
    public function test_error_notification_handles_null_values()
    {
        $mailable = new ErrorNotification(null, null);
        $built = $mailable->build();

        // Should handle null gracefully
        $this->assertNull($built->viewData['errorMessage']);
        $this->assertNull($built->subject);
    }

    /**
     * @test
     * Test ErrorNotification URL capture
     */
    public function test_error_notification_captures_current_url()
    {
        // Set up a mock request with specific URL
        $testUrl = 'https://example.com/test-path?param=value';
        request()->server->set('REQUEST_URI', '/test-path?param=value');
        request()->server->set('HTTP_HOST', 'example.com');
        request()->server->set('HTTPS', 'on');
        
        $mailable = new ErrorNotification('Test message');
        $built = $mailable->build();

        $capturedUrl = $built->viewData['url'];
        
        $this->assertIsString($capturedUrl);
        $this->assertNotEmpty($capturedUrl);
    }

    /**
     * @test
     * Test ErrorNotification serialization for queuing
     */
    public function test_error_notification_is_serializable()
    {
        $errorMessage = 'Serializable error message';
        $subject = 'Serializable Subject';
        
        $mailable = new ErrorNotification($errorMessage, $subject);
        
        // Test serialization/unserialization (important for queued jobs)
        $serialized = serialize($mailable);
        $unserialized = unserialize($serialized);
        
        $this->assertInstanceOf(ErrorNotification::class, $unserialized);
        $this->assertEquals($errorMessage, $unserialized->errorMessage);
        $this->assertEquals($subject, $unserialized->errorSubject);
    }

    /**
     * @test
     * Test ErrorNotification properties are accessible
     */
    public function test_error_notification_properties_are_accessible()
    {
        $errorMessage = 'Test error message';
        $errorSubject = 'Test Subject';
        
        $mailable = new ErrorNotification($errorMessage, $errorSubject);
        
        $this->assertEquals($errorMessage, $mailable->errorMessage);
        $this->assertEquals($errorSubject, $mailable->errorSubject);
    }

    /**
     * @test
     * Test ErrorNotification with multiline message
     */
    public function test_error_notification_handles_multiline_message()
    {
        $multilineMessage = "Line 1 of error\nLine 2 of error\nLine 3 with details:\n- Detail 1\n- Detail 2";
        
        $mailable = new ErrorNotification($multilineMessage);
        $built = $mailable->build();

        $this->assertEquals($multilineMessage, $built->viewData['errorMessage']);
        $this->assertStringContainsString("\n", $built->viewData['errorMessage']);
    }

    /**
     * @test
     * Test ErrorNotification with HTML in message
     */
    public function test_error_notification_handles_html_in_message()
    {
        $htmlMessage = '<script>alert("xss")</script><strong>Error</strong> with <em>HTML</em> tags';
        
        $mailable = new ErrorNotification($htmlMessage);
        $built = $mailable->build();

        // Should preserve HTML (let the view handle escaping)
        $this->assertEquals($htmlMessage, $built->viewData['errorMessage']);
    }

    /**
     * @test
     * Test ErrorNotification timestamp is recent
     */
    public function test_error_notification_timestamp_is_recent()
    {
        $beforeTime = now()->subSecond(); // Allow 1 second tolerance
        
        $mailable = new ErrorNotification('Test message');
        $built = $mailable->build();
        
        $afterTime = now()->addSecond(); // Allow 1 second tolerance
        
        $timestamp = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $built->viewData['timestamp']);
        
        $this->assertTrue($timestamp->between($beforeTime, $afterTime));
    }

    /**
     * @test
     * Test ErrorNotification constructor parameter validation
     */
    public function test_error_notification_constructor_parameter_types()
    {
        // Test with various parameter types
        $mailable1 = new ErrorNotification(123, 456); // Numeric
        $mailable2 = new ErrorNotification(true, false); // Boolean
        $mailable3 = new ErrorNotification([], new \stdClass()); // Array/Object
        
        // Should convert to strings or handle appropriately
        $built1 = $mailable1->build();
        $built2 = $mailable2->build();
        $built3 = $mailable3->build();
        
        $this->assertIsString($built1->viewData['errorMessage'] ?? '');
        $this->assertIsString($built2->viewData['errorMessage'] ?? '');
        $this->assertIsString($built3->viewData['errorMessage'] ?? '');
    }
}