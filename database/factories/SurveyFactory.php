<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class SurveyFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \App\Survey::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => $this->faker->word,
            'alias' => $this->faker->word,
            'institution_id' => \App\Institution::factory()->create(),
            'introduction' => $this->faker->name,
            'javascript' => '',
            'notes' => $this->faker->sentence,
            'inform' => 0,
            'access' => collect(['public', 'invited', 'registered'])->random(),
            'completed' => 0,
        ];
    }
}
