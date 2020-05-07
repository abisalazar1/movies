<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\MovieTime;
use Faker\Generator as Faker;

$factory->define(MovieTime::class, function (Faker $faker) {
    return [
        'movie_id' => $faker->numberBetween(1, 50),
        'date_time' => $faker->dateTimeBetween(now()->subDays(10), now()->addDay(7))
    ];
});
