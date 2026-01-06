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
         * Gate para Super Administrador (TÉCNICO + AUDITORÍA).
         * ACCESO EXCLUSIVO A: Gestión de Usuarios, Brigadas, UFIs, Logs.
         * NEGADO EXPLÍCITAMENTE: Operaciones, Procedimientos, Personas, Documentos.
         */
        Gate::define('super-admin', function (User $user) {
            return $user->hasRole('super_admin');
        });

        /**
         * Gate para Administrador.
         * Usuarios con rol 'admin' tienen acceso administrativo (pero NO super-admin).
         */
        Gate::define('admin', function (User $user) {
            return $user->hasRole('admin');
        });

        /**
         * Gate OPERATIVO: Acceso a Procedimientos, Personas, Documentos.
         * RETORNA FALSE EXPLÍCITAMENTE si el usuario es super_admin puro (sin otro rol).
         * Permite: admin, cargador, consultor.
         */
        Gate::define('acceso-operativo', function (User $user) {
            // Si es super_admin PURO (sin otros roles), deniega acceso operativo
            if ($user->hasRole('super_admin') && $user->roles()->count() === 1) {
                return false;
            }
            
            // Permite acceso a: admin, cargador, consultor
            return $user->hasRole('admin')
                || $user->hasRole('panel-carga')
                || $user->hasRole('panel-consulta');
        });

        /**
         * Gate para el panel de carga.
         * ÚNICAMENTE los usuarios con el rol 'panel-carga' pueden pasar.
         * Super admin puro está EXCLUIDO.
         */
        Gate::define('panel-carga', function (User $user) {
            // Denegar si es super_admin puro
            if ($user->hasRole('super_admin') && $user->roles()->count() === 1) {
                return false;
            }
            
            return $user->hasRole('panel-carga');
        });

        /**
         * Gate para el panel de consulta.
         * Permite a: cargador, consultor.
         * Super admin puro está EXCLUIDO.
         */
        Gate::define('panel-consulta', function (User $user) {
            // Denegar si es super_admin puro
            if ($user->hasRole('super_admin') && $user->roles()->count() === 1) {
                return false;
            }
            
            return $user->hasRole('panel-consulta')
                || $user->hasRole('panel-carga');
        });
    }
}

