<?php

$factory->define(App\Survey::class, function (Faker\Generator $faker) {
    return [
        "title" => $faker->name,
        "institution_id" => factory('App\Institution')->create(),
        "group_id" => factory('App\Group')->create(),
        "introduction" => $faker->name,
        "notes" => $faker->name,
        "completed" => 0,
    ];
});
