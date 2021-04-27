<?php

$factory->define(App\Response::class, function (Faker\Generator $faker) {
    return [
        'questionnaire_id' => factory(\App\Questionnaire::class)->create(),
        'question_id' => factory(\App\Question::class)->create(),
        'answer_id' => factory(\App\Answer::class)->create(),
        'content' => $faker->name,
    ];
});
