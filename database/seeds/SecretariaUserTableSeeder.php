<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SecretariaUserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('secretaria_user')->insert([
            [
                'user_id' => 1,
                'secretaria_id' => 18,
            ],
            [
                'user_id' => 2,
                'secretaria_id' => 18,
            ],
            [
                'user_id' => 3,
                'secretaria_id' => 18,
            ],
        ]);
    }
}
