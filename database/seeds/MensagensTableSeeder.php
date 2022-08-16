<?php

use App\Models\Chat\Mensagem;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MensagensTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Mensagem::class, 500)->create();
    }
}
