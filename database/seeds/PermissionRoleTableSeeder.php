<?php

use App\Constants\Permission as ConstantsPermission;
use App\Constants\PermissionRolesConstants;
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
        $ouvidorPermissions = collect(PermissionRolesConstants::SUPERADM);
        foreach ($ouvidorPermissions->flatten() as $permission) {
            $role->givePermission($permission);
        }

        // Ouvidor
        $role = Role::find(2);
        $ouvidorPermissions = collect(PermissionRolesConstants::OUVIDOR);
        foreach ($ouvidorPermissions->flatten() as $permission) {
            $role->givePermission($permission);
        }

        // Avaliador
        $role = Role::find(3);
        $avaliadorPermissions = collect(PermissionRolesConstants::AVALIADOR);
        foreach ($avaliadorPermissions->flatten() as $permission) {
            $role->givePermission($permission);
        }
    }
}
