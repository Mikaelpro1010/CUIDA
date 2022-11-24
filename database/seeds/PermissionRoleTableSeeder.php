<?php

use App\Constants\Permission as ConstantsPermission;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class PermissionRoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Admin
        $role = Role::find(1);
        foreach (Permission::all() as $permission) {
            $role->permissions()->attach($permission);
        }

        // Ouvidor
        $role = Role::find(2);
        $ouvidorPermissions = collect(ConstantsPermission::OUVIDOR_PERMISSIONS);

        foreach ($ouvidorPermissions->flatten() as $permission) {
            $role->givePermission($permission);
        }

        // Avaliador
        $role = Role::find(3);
        $avaliadorPermissions = collect(ConstantsPermission::AVALIADOR_PERMISSIONS);
        foreach ($avaliadorPermissions->flatten() as $permission) {
            $role->givePermission($permission);
        }
    }
}
