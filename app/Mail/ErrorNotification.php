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
     * @param string $errorMessage
     * @param string $errorSubject
     */
    public function __construct($errorMessage, $errorSubject = 'System Error Notification')
    {
        $this->errorMessage = $errorMessage;
        $this->errorSubject = $errorSubject;
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