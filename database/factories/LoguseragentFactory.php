<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class LoguseragentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \App\Loguseragent::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            // 'os' => $this->faker->name,
            // 'os_version' => $this->faker->name,
            // 'browser' => $this->faker->name,
            // 'browser_version' => $this->faker->name,
            // 'device' => $this->faker->name,
            // 'language' => $this->faker->name,
            // 'item_id' => $this->faker->randomNumber(2),
            // 'ipv6' => $this->faker->name,
            // 'uri' => $this->faker->name,
            // 'form_submitted' => 0,
            // 'user_id' => \App\User::factory(),
        ];
    }
}
