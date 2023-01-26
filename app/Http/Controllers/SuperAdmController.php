<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;

class SuperAdmController extends Controller
{
    public function migrarDados()
    {
        $this->authorize(permissionConstant()::SUPER_MIGRAR_DADOS);
        Cache::flush();

        Artisan::call('migrate', ['--path' => 'database/migrations/release/']);
        Artisan::call('db:seed', ['--class' => PermissionsTableSeeder::class]);
        Artisan::call('db:seed', ['--class' => RolesTableSeeder::class]);
        Artisan::call('db:seed', ['--class' => PermissionRoleTableSeeder::class]);
        Artisan::call('db:seed', ['--class' => SecretariasTableSeeder::class]);
        Artisan::call('db:seed', ['--class' => UsersTableSeeder::class]);
        Artisan::call('db:seed', ['--class' => SecretariaUserTableSeeder::class]);
        Artisan::call('db:seed', ['--class' => UnidadesSecrTableSeeder::class]);
        Artisan::call('db:seed', ['--class' => TiposAvaliacaoTableSeeder::class]);

        return 'Migração Completa!';
    }
}
