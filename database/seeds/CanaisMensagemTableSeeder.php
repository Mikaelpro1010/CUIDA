<?php

use App\Models\Chat\CanalMensagem;
use Illuminate\Database\Seeder;

class CanaisMensagemTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(CanalMensagem::class, 90)->create();
    }
}
