<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ErrorNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $errorMessage;
    public $errorSubject;

    /**
     * Create a new message instance.
     *
     * @param mixed $errorMessage
     * @param mixed $errorSubject
     */
    public function __construct($errorMessage, $errorSubject = 'System Error Notification')
    {
        // Convert to strings to ensure consistency
        $this->errorMessage = $this->convertToString($errorMessage);
        $this->errorSubject = $this->convertToString($errorSubject);
    }

    /**
     * Convert various types to string representation
     *
     * @param mixed $value
     * @return string|null
     */
    private function convertToString($value)
    {
        if ($value === null) {
            return null;
        }
        
        if (is_string($value)) {
            return $value;
        }
        
        if (is_numeric($value) || is_bool($value)) {
            return (string) $value;
        }
        
        if (is_array($value)) {
            return json_encode($value);
        }
        
        if (is_object($value)) {
            return json_encode($value);
        }
        
        return (string) $value;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject($this->errorSubject)
                    ->view('emails.error-notification')
                    ->with([
                        'errorMessage' => $this->errorMessage,
                        'timestamp' => now()->format('Y-m-d H:i:s'),
                        'url' => request()->url(),
                    ]);
    }
}