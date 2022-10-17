<?php

use App\Constants\Permission as ConstantsPermission;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('givePermission {permission}', function ($permission) {
    $role = Role::find(1);
    $role->givePermission($permission);
    $this->info("Permissao: \"" . $permission . "\" Inserida!");
});

Artisan::command('removePermission {permission}', function ($permission) {
    $role = Role::find(1);
    $role->removePermission($permission);
    $this->info("Permissao: \"" . $permission . "\" Removida!");
});

Artisan::command('giveAllPermissions', function () {
    $role = Role::find(1);
    foreach (Permission::all() as $permission) {
        $role->permissions()->attach($permission);
    }
});

