<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Helper: verificar si usuario es super_admin puro (sin otros roles)
        $isSuperAdminOnly = fn(User $user) => $user->hasRole('super_admin') && $user->roles()->count() === 1;

        Gate::define('super-admin', fn(User $user) => $user->hasRole('super_admin'));
        Gate::define('admin', fn(User $user) => $user->hasRole('admin'));

        Gate::define('acceso-operativo', function (User $user) use ($isSuperAdminOnly) {
            return !$isSuperAdminOnly($user) && (
                $user->hasRole('admin')
                || $user->hasRole('panel-carga')
                || $user->hasRole('panel-consulta')
            );
        });

        Gate::define('panel-carga', function (User $user) use ($isSuperAdminOnly) {
            return !$isSuperAdminOnly($user) && $user->hasRole('panel-carga');
        });

        Gate::define('panel-consulta', function (User $user) use ($isSuperAdminOnly) {
            return !$isSuperAdminOnly($user) && (
                $user->hasRole('panel-consulta')
                || $user->hasRole('panel-carga')
            );
        });
    }
}

