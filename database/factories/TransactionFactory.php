<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Laravel\Models\Application;
use App\Laravel\Models\Customer;
use App\Laravel\Models\Department;
use App\Laravel\Models\Transaction;
use App\Laravel\Models\ZoneLocation;
use Faker\Generator as Faker;

$factory->define(Transaction::class, function (Faker $faker) {
    return [
        'transaction_code' => $faker->bothify('???###'),
        'company_name' => $faker->company,
        'fname' => $faker->firstName,
        'lname' => $faker->lastName,
        'code' => $faker->bothify('???###'),
        'customer_id' => factory(Customer::class),
        'zone_id' => factory(ZoneLocation::class),
        'department_id' => factory(Department::class),
        'application_id' => factory(Application::class),
        'notes' => $faker->sentence,
        'remarks' => $faker->sentence,
        'amount' => $faker->numberBetween(10, 50),
    ];
})->state(Transaction::class, 'for-validation', function (Faker $faker) {
    return [
        'status' => $faker->randomElement(['PENDING', 'ONGOING']),
        'transaction_status' => 'COMPLETED',
        'is_validated' => 0,
    ];
})->state(Transaction::class, 'pending', function (Faker $faker) {
    return [
        'status' => 'PENDING',
        'is_resent' => 0,
    ];
})->state(Transaction::class, 'declined', function (Faker $faker) {
    return [
        'status' => 'DECLINED',
        'is_resent' => 0,
    ];
})->state(Transaction::class, 'resent', function (Faker $faker) {
    return [
        'status' => 'DECLINED',
        'is_resent' => 1,
    ];
})->state(Transaction::class, 'approved', function (Faker $faker) {
    return [
        'status' => 'APPROVED',
        'is_resent' => $faker->numberBetween(0, 1),
    ];
})->afterMaking(Transaction::class, function (Transaction $transaction) {
    $transaction->email = $transaction->customer->email;
    $transaction->application_name = $transaction->application->name;
    $transaction->department_name = $transaction->department->name;
});
