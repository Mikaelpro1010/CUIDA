<?php

namespace App\Providers;

use App\Models\Permission;
use App\Observers\PermissionObserver;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Permission::observe(PermissionObserver::class);
        Gate::before(function (User $user, string $ability) {
            if (Permission::existsOnCache($ability)) {
                return $user->role->hasPermission($ability);
            }
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
