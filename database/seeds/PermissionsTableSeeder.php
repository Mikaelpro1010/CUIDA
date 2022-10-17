<?php

use App\Constants\Permission as ConstantsPermission;
use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [];

        foreach (ConstantsPermission::PERMISSIONS as $permission) {
            $permissions[] = [
                'permission' => $permission
            ];
        }

        Permission::query()->insert($permissions);
    }
}
