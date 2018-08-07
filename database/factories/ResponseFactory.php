<?php

$factory->define(App\Response::class, function (Faker\Generator $faker) {
    return [
        "question_id" => factory('App\Question')->create(),
        "answer_id" => factory('App\Answer')->create(),
        "content" => $faker->name,
    ];
});
