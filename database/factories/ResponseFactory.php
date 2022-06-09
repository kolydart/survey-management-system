<?php

namespace Database\Factories;

use App\Answer;
use App\Question;
use App\Questionnaire;
use Illuminate\Database\Eloquent\Factories\Factory;

class ResponseFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \App\Response::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $answer = Answer::factory()->create();
        return [
            'questionnaire_id' => Questionnaire::factory(),
            'question_id' => Question::factory(),
            'answer_id' => $answer->id,
            'content' => $answer->open ? $this->faker->words(5,true) : null,
        ];
    }
}
