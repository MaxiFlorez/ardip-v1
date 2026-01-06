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
        /**
         * Gate para super administradores.
         * ACCESO TOTAL AL SISTEMA - Máxima jerarquía.
         */
        Gate::define('super-admin', function (User $user) {
            return $user->hasRole('super_admin');
        });

        /**
         * Gate para administradores.
         * Solo los usuarios con el rol 'admin' o 'super_admin' pueden pasar.
         */
        Gate::define('admin', function (User $user) {
            return $user->hasRole('admin') || $user->hasRole('super_admin');
        });

        /**
         * Gate para el panel de carga.
         * ÚNICAMENTE los usuarios con el rol 'panel-carga' pueden pasar.
         * El rol 'admin' está explícitamente excluido de esta puerta.
         * Super admin SÍ tiene acceso para auditoría y supervisión.
         */
        Gate::define('panel-carga', function (User $user) {
            return $user->hasRole('panel-carga') || $user->hasRole('super_admin');
        });

        /**
         * Gate para el panel de consulta.
         * Los usuarios con los roles 'panel-consulta', 'panel-carga', 'admin' o 'super_admin' pueden pasar.
         */
        Gate::define('panel-consulta', function (User $user) {
            return $user->hasRole('panel-consulta')
                || $user->hasRole('panel-carga')
                || $user->hasRole('admin')
                || $user->hasRole('super_admin');
        });
    }
}

