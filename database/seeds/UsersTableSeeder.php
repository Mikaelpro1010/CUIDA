<?php

use App\Models\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::query()->insert([
            [
                'name' => "SauloDc",
                'role_id' => 1,
                'email' => "saulocastro@sobral.ce.gov.br",
                'password' => bcrypt('123123'),
            ],
            [
                'name' => "SauloDc",
                'role_id' => 1,
                'email' => "asd@mail.com",
                'password' => bcrypt('123'),
            ],
            [
                'name' => "Ouvidor",
                'role_id' => 2,
                'email' => "ouvidor@mail.com",
                'password' => bcrypt('123'),
            ],
        ]);
    }
}
