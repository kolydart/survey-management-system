<?php

$factory->define(App\Response::class, function (Faker\Generator $faker) {
    return [
        "questionnaire_id" => factory('App\Questionnaire')->create(),
        "question_id" => factory('App\Question')->create(),
        "answer_id" => factory('App\Answer')->create(),
        "content" => $faker->name,
    ];
});
