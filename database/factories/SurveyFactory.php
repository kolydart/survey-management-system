<?php

$factory->define(App\Survey::class, function (Faker\Generator $faker) {
    return [
        "title" => $faker->name,
        "alias" => $faker->name,
        "institution_id" => factory('App\Institution')->create(),
        "introduction" => $faker->name,
        "notes" => $faker->name,
        "access" => collect(["public","invited","registered",])->random(),
        "completed" => 0,
    ];
});
