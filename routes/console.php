<?php

use App\Constants\Permission as ConstantsPermission;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;

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

// Artisan::command('givePermission {permission}', function ($permission) {
//     $role = Role::find(3);
//     $role->givePermission($permission);
//     $this->info("Permissao: \"" . $permission . "\" Inserida!");
// });

// Artisan::command('removePermission {permission}', function ($permission) {
//     $role = Role::find(3);
//     $role->removePermission($permission);
//     $this->info("Permissao: \"" . $permission . "\" Removida!");
// });

// Artisan::command('giveAllPermissions', function () {
//     $role = Role::find(1);
//     foreach (Permission::all() as $permission) {
//         $role->permissions()->attach($permission);
//     }
// });

// Artisan::command('cacheFlush', function () {
//     Cache::flush();
// });


// Artisan::command('setPassword {password}', function ($password) {
//     User::where('id', 1)->update([
//         'password' => bcrypt($password)
//     ]);
//     $this->info("Senha Alterada!");
// });
