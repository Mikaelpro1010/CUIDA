<?php

use App\Models\Avaliacao\Avaliacao;
use Illuminate\Database\Seeder;

class AvaliacoesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Avaliacao::class, 50)->create();
    }
}
