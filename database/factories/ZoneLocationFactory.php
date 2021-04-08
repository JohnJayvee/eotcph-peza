<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Laravel\Models\ZoneLocation;
use Faker\Generator as Faker;

$factory->define(ZoneLocation::class, function (Faker $faker) {
    return [
        'ecozone' => $faker->city,
    ];
});
