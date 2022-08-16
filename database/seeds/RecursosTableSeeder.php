<?php

use App\Models\Manifest\Recurso;
use Illuminate\Database\Seeder;

class RecursosTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Recurso::class, 50)->create();
    }
}
