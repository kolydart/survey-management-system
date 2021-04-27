<?php

$factory->define(App\Questionnaire::class, function (Faker\Generator $faker) {
    return [
        'survey_id' => factory('App\Survey')->create(),
        'name' => $faker->name,
    ];
});
