<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Laravel\Models\AccountCode;
use App\Laravel\Models\Application;
use App\Laravel\Models\Department;
use Faker\Generator as Faker;

$factory->define(Application::class, function (Faker $faker) {
    return [
        'department_id' => factory(Department::class),
        'name' => $faker->catchPhrase,
        'pre_processing_code' => factory(AccountCode::class),
        'pre_processing_cost' => $faker->numberBetween(5, 15),
        'post_processing_code' => factory(AccountCode::class),
        'post_processing_cost' => $faker->numberBetween(5, 15),
    ];
});
