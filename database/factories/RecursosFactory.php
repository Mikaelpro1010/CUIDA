<?php

use App\Models\Manifest\Manifest;
use App\Models\Manifest\Recurso;
use Carbon\Carbon;
use Faker\Generator as Faker;

$factory->define(Recurso::class, function (Faker $faker) {
    $manifest = Manifest::has('recursos', '<', 4)->inRandomOrder()->first();
    if ($manifest->id_estado_processo != Manifest::ESTADO_PROCESSO_RECURSO) {
        $manifest->id_estado_processo = Manifest::ESTADO_PROCESSO_RECURSO;
        $manifest->save();
    }
    return [
        'id_manifestacao' => $manifest->id,
        'recurso' => $faker->paragraph(2),
        'resposta' => $faker->paragraph(3),
        'data_resposta' => Carbon::now(),
    ];
});
