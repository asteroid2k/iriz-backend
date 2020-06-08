<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\AttendanceInfo;
use Faker\Generator as Faker;

$factory->define(AttendanceInfo::class, function (Faker $faker) {
    return [
        'course_id'=>$faker->randomElement(['CPEN401','CPEN302','CPEN404','CPEN105','CPEN409',
            'CPEN408','CPEN208']),
        'attendance_data'=>$faker->address,
        'date'=>$faker->dateTime
    ];
});
