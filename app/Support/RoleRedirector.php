<?php

namespace App\Support;

use App\Models\User;

class RoleRedirector
{
    /**
     * Devuelve la ruta destino según el rol del usuario.
     */
    public static function intendedRouteFor(?User $user): string
    {
        if (! $user) {
            return route('login', absolute: false);
        }

        if ($user->hasRole('admin')) {
            return route('dashboard', absolute: false);
        }
        if ($user->hasRole('cargador')) {
            return route('panel.carga', absolute: false);
        }
        if ($user->hasRole('consultor')) {
            return route('panel.consulta', absolute: false);
        }

        return route('personas.index', absolute: false);
    }
}
