<?php

use App\Models\Manifestacoes;
use App\Models\Prorrogacao;
use App\Models\User;
use Faker\Generator as Faker;

$factory->define(Prorrogacao::class, function (Faker $faker) {
    $prorrogacao = [
        'motivo_solicitacao'=> $faker-> text(),
        'manifestacao_id'=> Manifestacoes::inRandomOrder()->first()->id,
        'user_id'=> User::inRandomOrder()->first()->id,
        'situacao' => Prorrogacao::SITUACAO_ESPERA,
    ];

    return $prorrogacao;
});
