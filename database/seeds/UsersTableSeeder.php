<?php

use App\Models\User;
use Carbon\Carbon;
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
                'email' => "asd@mail.com",
                'password' => bcrypt('123'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => "Ouvidor",
                'role_id' => 2,
                'email' => "ouvidor@mail.com",
                'password' => bcrypt('123'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => "Avaliador",
                'role_id' => 3,
                'email' => "avaliador@mail.com",
                'password' => bcrypt('123'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => "SauloDc",
                'role_id' => 1,
                'email' => "saulocastro@sobral.ce.gov.br",
                'password' => bcrypt('123123'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);

        factory(User::class, 10)->create();
    }
}
