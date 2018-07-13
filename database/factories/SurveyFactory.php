<?php

$factory->define(App\Survey::class, function (Faker\Generator $faker) {
    return [
        "title" => $faker->name,
        "institution_id" => factory('App\Institution')->create(),
        "class_id" => factory('App\Class')->create(),
    ];
});
