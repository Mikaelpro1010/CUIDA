<?php

use App\Models\Historico;
use Illuminate\Database\Seeder;

class HistoricoTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Historico::class, 200)->create();
    }
}
