<?php

namespace App\Observers;

use App\Models\Permission;
use Illuminate\Support\Facades\Cache;

class PermissionObserver
{
    /**
     * Listen to the Permission created event.
     *
     * @param  \App\Permission $permission
     * @return void
     */
    public function created(Permission $permission): void
    {
        Cache::forget('permissions');
        Cache::rememberForever('permissions', function () {
            return Permission::all();
        });
    }
}
