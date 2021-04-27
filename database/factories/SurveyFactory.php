<?php

$factory->define(App\Survey::class, function (Faker\Generator $faker) {
    return [
        'title' => $faker->word,
        'alias' => $faker->word,
        'institution_id' => factory('App\Institution')->create(),
        'introduction' => $faker->name,
        'javascript' => '',
        'notes' => $faker->sentence,
        'inform' => 0,
        'access' => collect(['public', 'invited', 'registered'])->random(),
        'completed' => 0,
    ];
});
