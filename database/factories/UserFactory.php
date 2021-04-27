<?php

use Illuminate\Support\Str;

$factory->define(App\User::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->safeEmail,
        'password' => Str::random(10),
        'role_id' => factory(\App\Role::class)->create(),
        'remember_token' => $faker->name,
    ];
});
