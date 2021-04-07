<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Laravel\Models\AccountCode;
use Faker\Generator as Faker;

$factory->define(AccountCode::class, function (Faker $faker) {
    return [
        'code' => $faker->bothify('???###'),
    ];
});
