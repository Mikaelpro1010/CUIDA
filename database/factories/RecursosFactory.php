<?php

use App\Models\Manifest\Recurso;
use App\Models\Manifestacoes;
use Carbon\Carbon;
use Faker\Generator as Faker;

$factory->define(Recurso::class, function (Faker $faker) {
    $manifest = Manifestacoes::has('recursos', '<', 4)->inRandomOrder()->first();
    if ($manifest->estado_processo_id != 'Recurso') {
        $manifest->estado_processo_id = '1'; //Inicial
        $manifest->save();
    }
    return [
        'manifestacoes_id' => $manifest->id,
        'recurso' => $faker->paragraph(2),
        'resposta' => $faker->paragraph(3),
        'data_resposta' => Carbon::now(),
    ];
});
