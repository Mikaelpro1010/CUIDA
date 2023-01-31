<?php

use App\Models\Avaliacao\Avaliacao;
use App\Models\Avaliacao\Setor;
use App\Models\Avaliacao\TipoAvaliacao;
use App\Models\Avaliacao\Unidade;
use Faker\Generator as Faker;

$factory->define(Avaliacao::class, function (Faker $faker) {
    $setor = Setor::inRandomOrder()->first();
    $tipoAvaliacao = TipoAvaliacao::inRandomOrder()->first();
    $comment = rand(0, 50) % 5 == 0 ? $faker->realText() : null;

    return [
        'setor_id' => $setor->id,
        'tipos_avaliacao_id' => $tipoAvaliacao->id,
        'nota' => random_int(1, 5) * 2,
        'comentario' => $comment,
        'created_at' => $faker->dateTimeThisYear(),
    ];
});
