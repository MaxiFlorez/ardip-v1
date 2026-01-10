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
        // Gates simples y directos basados en roles
        Gate::define('super-admin', fn(User $user) => $user->hasRole('super_admin'));
        Gate::define('admin', fn(User $user) => $user->hasRole('admin'));
        
        Gate::define('panel-carga', fn(User $user) => $user->hasRole('panel-carga'));
        
        // panel-consulta puede ver lo que ven los cargadores (panel-carga)
        Gate::define('panel-consulta', fn(User $user) => 
            $user->hasRole('panel-consulta') || $user->hasRole('panel-carga')
        );
        
        // acceso-operativo para cualquier rol no super_admin puro
        Gate::define('acceso-operativo', fn(User $user) => 
            $user->hasRole('admin') 
            || $user->hasRole('panel-carga') 
            || $user->hasRole('panel-consulta')
        );
    }
}

