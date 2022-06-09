<?php

namespace Database\Factories;

use App\Institution;
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
            'title' => $this->faker->words(5,true),
            'alias' => $this->faker->words(1,true),
            'institution_id' => Institution::factory(),
            'introduction' => $this->faker->sentence(),
            'javascript' => '',
            'notes' => $this->faker->optional()->sentence(),
            'inform' => $this->faker->boolean(),
            'access' => collect(['public', 'invited', 'registered'])->first(),
            'completed' => $this->faker->boolean(10),
        ];
    }
}
