<?php

use App\Models\AppUser;
use App\Models\EstadosProcesso;
use App\Models\Manifestacoes;
use App\Models\Motivacao;
use App\Models\Situacao;
use App\Models\TiposManifestacao;
use Faker\Generator as Faker;
use Carbon\Carbon;

$factory->define(Manifestacoes::class, function (Faker $faker) {
    return [
        'protocolo' => $faker->unique()->numberBetween(9000, 10000),
        'senha' => $faker->unique()->numberBetween(9000, 10000),
        'situacao_id' => Situacao::inRandomOrder()->first()->id,
        'tipo_manifestacao_id' => TiposManifestacao::inRandomOrder()->first()->id,
        'estado_processo_id' => EstadosProcesso::inRandomOrder()->first()->id,
        'motivacao_id' => Motivacao::inRandomOrder()->first()->id,
        'manifestacao' => $faker->text(),
        'contextualizacao' => $faker->paragraph(3),
        'providencia_adotada' => $faker->paragraph(2),
        'conclusao' => $faker->paragraph(1),
        'data_finalizacao' => Carbon::now(),
    ];
});
