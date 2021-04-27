<?php

$factory->define(App\Questionnaire::class, function (Faker\Generator $faker) {
    return [
        'survey_id' => factory(\App\Survey::class)->create(),
        'name' => $faker->name,
    ];
});
