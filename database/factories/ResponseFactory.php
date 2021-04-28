<?php

namespace Database\Factories;

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
        return [
            'questionnaire_id' => \App\Questionnaire::factory()->create(),
            'question_id' => \App\Question::factory()->create(),
            'answer_id' => \App\Answer::factory()->create(),
            'content' => $this->faker->name,
        ];
    }
}
