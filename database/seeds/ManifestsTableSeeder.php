<?php

use App\Models\Manifest\Manifest;
use Illuminate\Database\Seeder;

class ManifestsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Manifest::class, 100)->create();
    }
}
