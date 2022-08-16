<?php

use App\Models\AppUser;
use App\Models\Chat\CanalMensagem;
use App\User;
use Faker\Generator as Faker;

$factory->define(App\Models\Chat\Mensagem::class, function (Faker $faker) {
    $number = random_int(1, 2);

    if ($number % 2 == 0) {
        return [
            'id_canal_mensagem' => CanalMensagem::inRandomOrder()->first()->id,
            'msg_type' => 1, // "ouvidor",
            'id_user' => User::inRandomOrder()->first()->id,
            'mensagem' => $faker->realText(),
        ];
    } else {
        $canal_msg = CanalMensagem::inRandomOrder()->first();
        return [
            'id_canal_mensagem' => $canal_msg->id,
            'msg_type' => 2, //"app_user",
            'id_app_user' => $canal_msg->manifestacao->autor->id,
            'mensagem' => $faker->realText(),
        ];
    }
});
