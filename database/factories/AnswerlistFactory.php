<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class AnswerlistFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \App\Answerlist::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => $this->faker->sentence(),
            'type' => $this->faker->boolean(90) ?  
                $this->faker->randomElement([
                'radio', 
                'checkbox', 
                // 'text', 
                // 'number', 
                // 'range', 
                // 'color', 
                // 'date', 
                // 'time', 
                // 'datetime-local', 
                // 'email', 
                // 'url', 
                // 'week', 
                // 'month', 
                // 'password', 
                // 'tel',
                ]) : 'text',
            'remove_unused' => fake()->boolean(80),
        ];
    }
}
