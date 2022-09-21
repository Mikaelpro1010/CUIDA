<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UsersTableSeeder::class);
        $this->call(AppUsersTableSeeder::class);
        $this->call(ManifestsTableSeeder::class);
        $this->call(RecursosTableSeeder::class);
        $this->call(LocationTableSeeder::class);
        $this->call(CanaisMensagemTableSeeder::class);
        $this->call(MensagensTableSeeder::class);
        $this->call(SecretariasTableSeeder::class);
        $this->call(UnidadesSecrTableSeeder::class);
        $this->call(AvaliacoesTableSeeder::class);
    }
}
