<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Laravel\Models\Customer;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

$factory->define(Customer::class, function (Faker $faker) {
    return [
        'fname' => $faker->firstName,
        'mname' => $faker->lastName,
        'lname' => $faker->lastName,
        'password' => bcrypt('password'),
    ];
})->afterMaking(Customer::class, function (Customer $customer) {
    $customer->email = Str::slug($customer->fname . ' ' . $customer->lname) . '@mail.com';
});
