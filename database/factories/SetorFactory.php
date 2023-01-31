<?php

use App\Models\Avaliacao\Setor;
use App\Models\Avaliacao\Unidade;
use Faker\Generator as Faker;

$factory->define(Setor::class, function (Faker $faker) {

    $unidadeSec = Unidade::inRandomOrder()->first();
    $principal = $unidadeSec->setores->count() == 0;
    return [
        'unidade_id' => $unidadeSec->id,
        'nome' => $faker->company(),
        'ativo' => true,
        'principal' => $principal,
        'created_at' => $faker->dateTimeThisYear(),
    ];
});
