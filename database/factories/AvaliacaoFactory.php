<?php

use App\Models\Avaliacao\Avaliacao;
use App\Models\Avaliacao\Unidade;
use Faker\Generator as Faker;

$factory->define(Avaliacao::class, function (Faker $faker) {
    $unidadeSec = Unidade::inRandomOrder()->first();
    $comment = rand(0, 50) % 5 == 0 ? $faker->realText() : null;

    return [
        'unidade_secr_id' => $unidadeSec->id,
        'nota' => random_int(1, 5),
        'comentario' => $comment,
        'created_at' => $faker->dateTimeInInterval('-2 years', '2years'),
    ];
});
