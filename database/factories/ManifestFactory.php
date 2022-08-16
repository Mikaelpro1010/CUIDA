<?php

use App\Models\AppUser;
use App\Models\Manifest\Manifest;
use Carbon\Carbon;
use Faker\Generator as Faker;

$factory->define(Manifest::class, function (Faker $faker) {

    $situacao = array_rand(Manifest::SITUACAO);
    $tipo = array_rand(Manifest::TIPO_MANIFESTACAO);
    $estado = array_rand(Manifest::ESTADO_PROCESSO);
    $motivacao = array_rand(Manifest::MOTIVACAO);

    return [
        'protocolo' => $faker->unique()->numberBetween(9000, 10000),
        'id_app_user' => AppUser::inRandomOrder()->first()->id,
        'id_situacao' => $situacao,
        'id_tipo_manifestacao' => $tipo,
        'id_estado_processo' => $estado,
        'id_motivacao' => $motivacao,
        'manifestacao' => $faker->text(),
        'contextualizacao' => $faker->paragraph(3),
        'providencia_adotada' => $faker->paragraph(2),
        'conclusao' => $faker->paragraph(1),
        'data_finalizacao' => Carbon::now(),
    ];
});
