<?php

use App\Models\Avaliacao\Setor;
use App\Models\Avaliacao\Unidade;
use Faker\Generator as Faker;

$factory->define(Setor::class, function (Faker $faker) {

    $unidadeSec = Unidade::inRandomOrder()->first();
    $principal = $unidadeSec->setores->count() == 0;
    return [
        'unidade_id' => $unidadeSec->id,
        'nome' => $principal ? 'Principal' : $faker->company(),
        'ativo' => true,
        'principal' => $principal,
        'token' => substr(bin2hex(random_bytes(50)), 1),
        'created_at' => $faker->dateTimeThisYear(),
    ];
});
