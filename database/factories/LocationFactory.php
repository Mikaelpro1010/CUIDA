<?php

use App\Models\Manifest\Location;
use App\Models\Manifest\Manifest;
use Faker\Generator as Faker;

$factory->define(Location::class, function (Faker $faker) {
    $manifest_id = Manifest::has('location', '<', 1)->inRandomOrder()->first()->id;
    return [
        'id_manifestacao' => $manifest_id,
        'latitude' => $faker->latitude('-3.66100101772886', "-3.67627070267722"),
        'longitude' => $faker->longitude('-40.335867242042376', '-40.3530150788405'),
        'bairro' => $faker->streetSuffix(),
        'numero' => $faker->buildingNumber(),
        'rua' => $faker->streetName(),
    ];
});
