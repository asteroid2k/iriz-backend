<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\StudentInfo;
use Faker\Generator as Faker;

$factory->define(StudentInfo::class, function (Faker $faker) {
    return [
        'student_id'=>$faker->unique()->randomElement(['10628439','10628436','10628432','10628430','10628437']),
        'courses'=>$faker->randomElements(['CPEN401','CPEN302','CPEN404','CPEN105','CPEN409',
            'CPEN408','CPEN208'],$faker->numberBetween(1,6)),
        'level'=>$faker->randomElement(['100','200','300','400'])
    ];
});
