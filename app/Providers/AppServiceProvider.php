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
        // ============================================================================
        // GATES DE ROLES FUNDAMENTALES
        // ============================================================================
        
        Gate::define('super-admin', fn(User $user) => $user->hasRole('super_admin'));
        Gate::define('admin', fn(User $user) => $user->hasRole('admin'));
        
        // ============================================================================
        // GATES DE ACCESO A DASHBOARDS
        // ============================================================================
        
        // admin-dashboard: SOLO para el rol 'admin' (exluye super_admin)
        // El dashboard es un panel específico para admins operativos, NO para super_admin puro
        Gate::define('admin-dashboard', fn(User $user) => 
            $user->hasRole('admin')
        );
        
        // admin-supervisor: admin que no es super_admin puro (usado en navegación)
        Gate::define('admin-supervisor', fn(User $user) => 
            $user->hasRole('admin') && (!$user->hasRole('super_admin') || $user->roles()->count() > 1)
        );
        
        // ============================================================================
        // GATES DE ROLES OPERATIVOS
        // ============================================================================
        
        Gate::define('panel-carga', fn(User $user) => $user->hasRole('panel-carga'));
        
        // panel-consulta puede ver lo que ven los cargadores (panel-carga)
        Gate::define('panel-consulta', fn(User $user) => 
            $user->hasRole('panel-consulta') || $user->hasRole('panel-carga')
        );
        
        // ============================================================================
        // GATES DE ACCESO A MÓDULOS
        // ============================================================================
        
        // acceso-operativo: Lectura en módulos operativos (admin, panel-carga, panel-consulta)
        Gate::define('acceso-operativo', fn(User $user) => 
            $user->hasRole('admin') 
            || $user->hasRole('panel-carga') 
            || $user->hasRole('panel-consulta')
        );
        
        // operativo-escritura: CRUD en módulos operativos (SOLO panel-carga, NO admin)
        Gate::define('operativo-escritura', fn(User $user) => 
            $user->hasRole('panel-carga')
        );
    }
}

