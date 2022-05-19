<?php

namespace Database\Factories;

use App\Survey;
use Illuminate\Database\Eloquent\Factories\Factory;

class QuestionnaireFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \App\Questionnaire::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'survey_id' => Survey::factory()->create(),
            'name' => $this->faker->words(5,true),
        ];
    }
}
