<?php

$factory->define(App\Answerlist::class, function (Faker\Generator $faker) {
    return [
        "title" => $faker->name,
        "type" => collect(["radio","radio + text","checkbox","checkbox + text","text",])->random(),
    ];
});
