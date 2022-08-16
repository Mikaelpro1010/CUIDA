<?php

use App\Models\AppUser;
use Faker\Generator as Faker;
use App\Models\Chat\CanalMensagem;
use App\Models\Manifest\Manifest;

$factory->define(CanalMensagem::class, function (Faker $faker) {
    $manifest = Manifest::inRandomOrder()->first();
    return [
        'id_manifestacao' => $manifest->id,
        'id_status' => random_int(1, 3),
    ];
});
