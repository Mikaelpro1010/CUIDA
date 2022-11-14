<?php

use App\Models\FAQ;
use Faker\Generator as Faker;

$factory->define(FAQ::class, function (Faker $faker) {
    return [
        'ativo'=> true,
        'pergunta'=> $faker-> text(),
        'resposta'=> $faker-> text(),
        'ordem' => -1,
        
    ];
});
