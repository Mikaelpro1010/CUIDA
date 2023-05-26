<?php

use App\Models\Avaliacao\Avaliacao;
use App\Models\Avaliacao\Setor;
use App\Models\Avaliacao\TipoAvaliacao;
use App\Models\Avaliacao\Unidade;
use Faker\Generator as Faker;


// o metodo with permite um carregamento adiantado de relacionamentos 
$factory->define(Avaliacao::class, function (Faker $faker) {
    $setor = Setor::with('unidade', 'unidade.secretaria', 'unidade.secretaria.tiposAvaliacao')->inRandomOrder()->first();
    $tipoAvaliacao = $setor->unidade->secretaria->tiposAvaliacao()->inRandomOrder()->first();
    $comment = rand(0, 50) % 5 == 0 ? $faker->realText() : null;

    return [
        'setor_id' => $setor->id,
        'tipo_avaliacao_id' => $tipoAvaliacao->id,
        'nota' => random_int(1, 5) * 2,
        'comentario' => $comment,
        'created_at' => $faker->dateTimeThisYear(),
    ];
});
