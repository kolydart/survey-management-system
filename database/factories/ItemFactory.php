<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ItemFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \App\Item::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'survey_id' => \App\Survey::factory()->create(),
            'question_id' => \App\Question::factory()->create(),
            'label' => 0,
            'order' => $this->faker->name,
        ];
    }
}
