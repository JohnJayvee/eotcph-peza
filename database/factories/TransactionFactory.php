<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Laravel\Models\Application;
use App\Laravel\Models\Department;
use App\Laravel\Models\Transaction;
use Faker\Generator as Faker;

$factory->define(Transaction::class, function (Faker $faker) {
    return [
        'company_name' => $faker->company,
        'fname' => $faker->firstName,
        'lname' => $faker->lastName,
        'code' => $faker->bothify('???###'),
    ];
})->state(Transaction::class, 'pending', function (Faker $faker) {
    return [
        'status' => 'PENDING',
        'is_resent' => 0,
    ];
})->state(Transaction::class, 'office-head', function (Faker $faker) {
    return [
        'department_id' => factory(Department::class),
    ];
})->state(Transaction::class, 'processor', function (Faker $faker) {
    return [
        'department_id' => factory(Department::class),
        'applicatioin_id' => factory(Application::class),
    ];
})->state(Transaction::class, 'for-validation', function (Faker $faker) {
    return [
        'status' => $faker->randomElement(['PENDING', 'ONGOING']),
        'transaction_status' => 'COMPLETED',
        'is_validated' => 0,
    ];
});
