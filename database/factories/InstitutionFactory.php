<?php

$factory->define(App\Institution::class, function (Faker\Generator $faker) {
    return [
        "title" => $faker->name,
    ];
});
