<?php

$factory->define(App\Response::class, function (Faker\Generator $faker) {
    return [
        "question_id" => factory('App\Question')->create(),
        "content" => $faker->name,
    ];
});
