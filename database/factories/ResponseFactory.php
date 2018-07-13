<?php

$factory->define(App\Response::class, function (Faker\Generator $faker) {
    return [
        "question_id" => factory('App\Question')->create(),
        "content" => $faker->name,
        "answer_id" => factory('App\Answer')->create(),
    ];
});
