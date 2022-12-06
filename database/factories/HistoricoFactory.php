<?php

use App\Models\Historico;
use App\Models\Manifestacoes;
use Faker\Generator as Faker;


$factory->define(Historico::class, function (Faker $faker) {
    return [
        'manifestacao_id' => Manifestacoes::inRandomOrder()->first()->id,
        'etapas' => $faker->paragraph(1),
        'alternativo' => $faker->paragraph(1),
    ];
});
