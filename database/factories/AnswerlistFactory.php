<?php

$factory->define(App\Answerlist::class, function (Faker\Generator $faker) {
    return [
        "title" => $faker->name,
        "type" => collect(["radio","checkbox","0",])->random(),
    ];
});
