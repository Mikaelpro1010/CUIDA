<?php

use App\Models\AppUser;
use Faker\Generator as Faker;

$factory->define(AppUser::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
    ];
});
