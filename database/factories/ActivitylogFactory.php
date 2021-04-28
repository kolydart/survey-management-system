<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ActivitylogFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \App\Activitylog::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'log_name' => $this->faker->name,
            'causer_type' => $this->faker->name,
            'causer_id' => $this->faker->randomNumber(2),
            'description' => $this->faker->name,
            'subject_type' => $this->faker->name,
            'subject_id' => $this->faker->randomNumber(2),
            'properties' => $this->faker->name,
        ];
    }
}
