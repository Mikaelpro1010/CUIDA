<?php

use App\Models\Compartilhamento;
use App\Models\Manifestacoes;
use App\Models\Secretaria;
use Faker\Generator as Faker;

$factory->define(Compartilhamento::class, function (Faker $faker) {
    return [
        'manifestacao_id'=> Manifestacoes::inRandomOrder()->first()->id,
        'secretaria_id'=> Secretaria::inRandomOrder()->first()->id,
        'texto_compartilhamento'=> $faker->text(),
        'resposta'=> $faker->text(),
        'data_inicial'=> $faker->dateTime(),
        'data_resposta'=> $faker->dateTime(),
    ];
});
