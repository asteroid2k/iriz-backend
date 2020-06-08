<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\TutorInfo;
use Faker\Generator as Faker;
//use Illuminate\Support\Facades\Hash;

$factory->define(TutorInfo::class, function (Faker $faker) {
    return [
        'ID' => $faker->unique()->randomElement(['90000', '90001', '90002', '90003', '90004']),
        'courses' => $faker->unique()->randomElements(['CPEN401', 'CPEN302', 'CPEN404', 'CPEN105', 'CPEN409',
            'CPEN408', 'CPEN208'], $faker->numberBetween(1, 6), false),
        'pin' => "123456",
        'name' => $faker->name()
    ];
});
