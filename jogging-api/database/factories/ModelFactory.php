<?php

$factory->define(App\Models\User::class, function (Faker\Generator $faker) {
    static $index = 0;
    static $password;

    return [
        'name' => $faker->name,
        'email' => "user{++$index}@example.com",
        'password' => $password ?: $password = bcrypt('123456'),
    ];
});
