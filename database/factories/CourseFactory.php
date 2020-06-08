<?php

/** @var Factory $factory */

use App\Course;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

$factory->define(Course::class, function (Faker $faker) {
    return [
        'code'=>$faker->unique()->randomElement(['CPEN401','CPEN302','CPEN404','CPEN105','CPEN409',
        'CPEN408','CPEN208']),
        'name'=>$faker->country,
        'tutor_id'=>$faker->numberBetween(15,400),
        'student_list'=>$faker->numberBetween(15,400),

    ];
});
