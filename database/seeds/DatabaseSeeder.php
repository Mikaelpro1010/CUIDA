<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Cache;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Cache::flush();

        $this->call(PermissionsTableSeeder::class);
        $this->call(RolesTableSeeder::class);
        $this->call(PermissionRoleTableSeeder::class);
        $this->call(SecretariasTableSeeder::class);
        $this->call(UsersTableSeeder::class);
        $this->call(SecretariaUserTableSeeder::class);

        //Configs de manifestaçao
        $this->call(TiposManifestacaoTableSeeder::class);
        $this->call(EstadosProcessoTableSeeder::class);
        $this->call(MotivacaoTableSeeder::class);
        $this->call(SituacaoTableSeeder::class);

        //Users from App
        $this->call(AppUsersTableSeeder::class);

        //manifestaçoes
        $this->call(ManifestsTableSeeder::class);
        $this->call(RecursosTableSeeder::class);
        $this->call(LocationTableSeeder::class);

        //Modulo Chat 
        $this->call(CanaisMensagemTableSeeder::class);
        $this->call(MensagensTableSeeder::class);

        //Modulo de Avaliaçoes 
        $this->call(UnidadesSecrTableSeeder::class);
        $this->call(AvaliacoesTableSeeder::class);

        //FAQ
        $this->call(FaqTableSeeder::class);

        $this->call(ManifestacoesTableSeeder::class);
        $this->call(HistoricoTableSeeder::class);
    }
}
