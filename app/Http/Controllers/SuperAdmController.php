<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;

class SuperAdmController extends Controller
{
    public function migrarDados()
    {
        // $this->authorize(permissionConstant()::SUPER_MIGRAR_DADOS);
        Cache::flush();

        Artisan::call('migrate', ['--path' => 'database/migrations/release/']);
        Artisan::call('db:seed', ['--class' => 'PermissionsTableSeeder']);
        Artisan::call('db:seed', ['--class' => 'RolesTableSeeder']);
        Artisan::call('db:seed', ['--class' => 'PermissionRoleTableSeeder']);
        Artisan::call('db:seed', ['--class' => 'SecretariasTableSeeder']);
        Artisan::call('db:seed', ['--class' => 'UsersTableSeeder']);
        Artisan::call('db:seed', ['--class' => 'SecretariaUserTableSeeder']);
        Artisan::call('db:seed', ['--class' => 'TiposAvaliacaoTableSeeder']);

        return 'Migração Completa!';
    }
}
