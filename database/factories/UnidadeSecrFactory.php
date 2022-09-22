<?php

use App\Models\Avaliacao\Unidade;
use App\Models\Secretaria;
use Faker\Generator as Faker;

$factory->define(Unidade::class, function (Faker $faker) {
    $secretaria = Secretaria::inRandomOrder()->first();
    $description = rand(0, 50) % 5 == 0 ? $faker->realText() : null;
    return [
        'secretaria_id' => $secretaria->id,
        'nome' => $faker->company(),
        'descricao' => $description,
        'ativo' => true,
        'token' => substr(bin2hex(random_bytes(50)), 1),
    ];
});
