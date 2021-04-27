<?php

$factory->define(App\Activitylog::class, function (Faker\Generator $faker) {
    return [
        'log_name' => $faker->name,
        'causer_type' => $faker->name,
        'causer_id' => $faker->randomNumber(2),
        'description' => $faker->name,
        'subject_type' => $faker->name,
        'subject_id' => $faker->randomNumber(2),
        'properties' => $faker->name,
    ];
});
