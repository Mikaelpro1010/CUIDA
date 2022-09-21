<?php

use App\Models\Avaliacao\Unidade;
use Illuminate\Database\Seeder;

class UnidadesSecrTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Unidade::class, 50)->create();
    }
}
