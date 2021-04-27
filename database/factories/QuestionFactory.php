<?php

$factory->define(App\Question::class, function (Faker\Generator $faker) {
    return [
        'title' => $faker->name,
        'answerlist_id' => factory('App\Answerlist')->create(),
    ];
});
