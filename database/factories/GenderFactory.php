<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Gender;
use Faker\Generator as Faker;

$factory->define(Gender::class, function (Faker $faker) {
    return [
        'name' => $faker->name(),
        'is_active'=> rand(1,10) % 2 == 0 ? true :false
    ];
});
