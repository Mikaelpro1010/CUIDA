<?php

namespace App\Providers;

use App\Models\Avaliacao\Avaliacao;
use App\Models\Permission;
use App\Models\User;
use App\Observers\AvaliacoesObserver;
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
        Avaliacao::observe(AvaliacoesObserver::class);
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
