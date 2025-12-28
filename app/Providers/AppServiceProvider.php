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


        Gate::define('panel-carga', function ($user) {
            return $user->hasRole('admin') || $user->hasRole('cargador');
        });

        Gate::define('panel-consulta', function ($user) {
            return $user->hasRole('admin') || $user->hasRole('cargador') || $user->hasRole('consultor');
        });

        // Procedimientos: gestión (CRUD) solo admin y cargador
        Gate::define('gestionar-procedimientos', function ($user) {
            return $user->hasRole('admin') || $user->hasRole('cargador');
        });

        // Procedimientos: ver (lectura) admin, cargador y consultor
        Gate::define('ver-procedimientos', function ($user) {
            return $user->hasRole('admin') || $user->hasRole('cargador') || $user->hasRole('consultor');
        });

        // Personas: gestión (CRUD) admin y cargador
        Gate::define('gestionar-personas', function ($user) {
            return $user->hasRole('admin') || $user->hasRole('cargador');
        });

        // Personas: ver (lectura) admin, cargador y consultor
        Gate::define('ver-personas', function ($user) {
            return $user->hasRole('admin') || $user->hasRole('cargador') || $user->hasRole('consultor');
        });

        // Domicilios: gestión (CRUD) admin y cargador
        Gate::define('gestionar-domicilios', function ($user) {
            return $user->hasRole('admin') || $user->hasRole('cargador');
        });

        // Domicilios: ver (lectura) admin, cargador y consultor
        Gate::define('ver-domicilios', function ($user) {
            return $user->hasRole('admin') || $user->hasRole('cargador') || $user->hasRole('consultor');
        });

        // Documentos: gestión (subir/borrar) admin y cargador
        Gate::define('gestionar-documentos', function ($user) {
            return $user->hasRole('admin') || $user->hasRole('cargador');
        });

        // Documentos: ver/descargar admin, cargador y consultor
        Gate::define('ver-documentos', function ($user) {
            return $user->hasRole('admin') || $user->hasRole('cargador') || $user->hasRole('consultor');
        });

    }
}
