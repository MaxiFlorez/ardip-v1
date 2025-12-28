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
        // ═══════════════════════════════════════════════════════════
        // Sistema de permisos RBAC simplificado (3 gates)
        // ═══════════════════════════════════════════════════════════
        
        // Admin:  acceso completo al dashboard y gestión del sistema
        Gate::define('admin-general', function ($user) {
            return $user->hasRole('admin');
        });

        // Panel de carga: admin y cargadores pueden crear/editar/eliminar
        Gate::define('panel-carga', function ($user) {
            return $user->hasRole('admin') || $user->hasRole('cargador');
        });

        // Panel de consulta:  todos los roles pueden ver información (lectura)
        Gate::define('panel-consulta', function ($user) {
            return $user->hasRole('admin') 
                || $user->hasRole('cargador') 
                || $user->hasRole('consultor');
        });

    }
}
