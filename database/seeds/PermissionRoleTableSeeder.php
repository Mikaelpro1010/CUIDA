<?php

use App\Constants\PermissionRolesConstants;
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
        // SuperAdmin
        $role = Role::find(1);
        $ouvidorPermissions = collect(PermissionRolesConstants::SUPERADM);
        foreach ($ouvidorPermissions->flatten() as $permission) {
            $role->givePermission($permission);
        }

        // Admin
        $role = Role::find(2);
        $ouvidorPermissions = collect(PermissionRolesConstants::ADMIN);
        foreach ($ouvidorPermissions->flatten() as $permission) {
            $role->givePermission($permission);
        }
        // gerente
        $role = Role::find(3);
        $ouvidorPermissions = collect(PermissionRolesConstants::GERENTE);
        foreach ($ouvidorPermissions->flatten() as $permission) {
            $role->givePermission($permission);
        }
        // Ouvidor
        $role = Role::find(4);
        $ouvidorPermissions = collect(PermissionRolesConstants::OUVIDOR);
        foreach ($ouvidorPermissions->flatten() as $permission) {
            $role->givePermission($permission);
        }

        // Avaliador
        $role = Role::find(5);
        $avaliadorPermissions = collect(PermissionRolesConstants::AVALIADOR);
        foreach ($avaliadorPermissions->flatten() as $permission) {
            $role->givePermission($permission);
        }
    }
}
