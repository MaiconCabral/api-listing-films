<?php

namespace Database\Factories;

use App\Models\Films;
use Faker\Generator as Faker;

$factory->define(Films::class, function (Faker $faker) {
    return [
        'year' => $faker->year,
        'title' => $faker->unique()->sentence,
        'studios' => $faker->sentence,
        'producers' => $faker->name,
        'winner' => $faker->randomElement(['yes', 'no']),
    ];
});