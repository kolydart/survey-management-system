<?php

$factory->define(App\Loguseragent::class, function (Faker\Generator $faker) {
    return [
        "os" => $faker->name,
        "os_version" => $faker->name,
        "browser" => $faker->name,
        "browser_version" => $faker->name,
        "device" => $faker->name,
        "language" => $faker->name,
        "item_id" => $faker->randomNumber(2),
        "ipv6" => $faker->name,
        "uri" => $faker->name,
        "form_submitted" => 0,
        "user_id" => factory('App\User')->create(),
    ];
});
