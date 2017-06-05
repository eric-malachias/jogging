<?php

namespace App\Models;

use Faker\Generator;
use Carbon\Carbon;

$factory->define(User::class, function (Generator $faker) {
    static $index = 0;
    static $password;

    return [
        'name' => $faker->name,
        'email' => sprintf('user%d@example.com', ++$index),
        'password' => $password ?: $password = '123456',
    ];
});

$factory->define(Jog::class, function (Generator $faker) {
    $startedAt = Carbon::now()->subHours(rand() % (30 * 24) + 1);
    $endedAt = $startedAt->copy()->addMinutes(15 * (rand() % 8 + 1));

    return [
        'started_at' => $startedAt,
        'ended_at' => $endedAt,
        'distance' => rand() % 9500 + 500,
    ];
});
