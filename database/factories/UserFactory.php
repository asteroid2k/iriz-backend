<?php

/** @var Factory $factory */
use App\User;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;
//use Illuminate\Support\Str;

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
    $x = $faker->unique()->numberBetween(10500000,11000000);
    return [
        'title' => $faker->title,
        'sex' => $faker->randomElement(["male","female","other","rather not say"]),
        'first_name' => $faker->firstName,
        'last_name' => $faker->lastName,
        'email' => $faker->unique()->safeEmail,
        'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        'phone' => $faker->unique()->phoneNumber,
        'ID' => (string) $x,
        'avatar' => $faker->fileExtension,
        'role' => $faker->randomElement(['STUDENT','TUTOR']),
        'isVerified'=>$faker->boolean

    ];
});
