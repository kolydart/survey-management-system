<?php

namespace Database\Factories;

use App\Question;
use App\Survey;
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
        static $order = 1;

        return [
            'survey_id' => Survey::factory()->create(),
            'question_id' => Question::factory()->create(),
            'label' => $this->faker->boolean(90),
            'order' => $order++,
        ];
    }
}
