<?php

use App\Models\Manifestacoes;
use Illuminate\Database\Seeder;

class ManifestacoesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Manifestacoes::class, 200)->create();
    }
}
