<?php

namespace App\Mail;

use App\Questionnaire;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class QuestionnaireSubmitted extends Mailable
{
    use Queueable, SerializesModels;

    public $questionnaire;

    /**
     * Create a new message instance.
     *
     * @param Questionnaire $questionnaire
     */
    public function __construct(Questionnaire $questionnaire)
    {
        $this->questionnaire = $questionnaire;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('New questionnaire for survey «' . $this->questionnaire->survey->title . '»')
                    ->view('emails.questionnaire-submitted')
                    ->with([
                        'questionnaire_url' => route('admin.questionnaires.show', $this->questionnaire->id),
                        'survey_alias' => $this->questionnaire->survey->alias ?? '',
                        'survey_title' => $this->questionnaire->survey->title,
                    ]);
    }
}