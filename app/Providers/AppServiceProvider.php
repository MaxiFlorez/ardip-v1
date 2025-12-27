<?php

namespace App\Providers;

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
        // Puertas (Gates) para RBAC
        Gate::define('admin-general', function ($user) {
            return $user->hasRole('admin');
        });

        Gate::define('acceso-total', function ($user) {
            return $user->hasRole('admin');
        });

        Gate::define('admin-general', function ($user) {
            return $user->hasRole('admin');
        });

        Gate::define('panel-carga', function ($user) {
            return $user->hasRole('admin') || $user->hasRole('cargador');
        });

        Gate::define('panel-consulta', function ($user) {
            return $user->hasRole('admin') || $user->hasRole('cargador') || $user->hasRole('consultor');
        });

        Gate::define('gestion-usuarios', function ($user) {
            return $user->hasRole('admin');
        });
    }
}
