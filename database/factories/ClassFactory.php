<?php

$factory->define(App\Class::class, function (Faker\Generator $faker) {
    return [
        "title" => $faker->name,
    ];
});
