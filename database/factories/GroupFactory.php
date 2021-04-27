<?php

$factory->define(App\Group::class, function (Faker\Generator $faker) {
    return [
        'title' => $faker->name,
    ];
});
