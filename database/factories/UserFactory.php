<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Laravel\Models\Department;
use App\Laravel\Models\User;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
 */

$factory->define(User::class, function (Faker $faker) {
    return [
        'fname' => $faker->firstName,
        'mname' => $faker->lastName,
        'lname' => $faker->lastName,
        'contact_number' => $faker->numerify('+639#########'),
        'email' => $faker->unique()->safeEmail,
        'email_verified_at' => now(),
        'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        'remember_token' => Str::random(10),
    ];
})->state(User::class, 'admin', function (Faker $faker) {
    return [
        'type' => 'admin',
    ];
})->state(User::class, 'office-head', function (Faker $faker) {
    return [
        'type' => 'office_head',
        'department_id' => factory(Department::class),
    ];
})->state(User::class, 'processor', function (Faker $faker) {
    return [
        'type' => 'processor',
    ];
})->afterMaking(User::class, function (User $user, Faker $faker) {
    $user->username = Str::slug($user->fname . ' ' . $user->lname);
    $user->email = $user->username . '@mail.com';
})->afterCreating(User::class, function (User $user) {
    $user->update([
        'reference_id' => str_pad($user->id, 5, '0', STR_PAD_LEFT),
    ]);
});
