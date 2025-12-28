<?php

namespace App\Support;

use Illuminate\Contracts\Auth\Authenticatable;

class RoleRedirector
{
    /**
     * Devuelve la ruta destino según el rol del usuario.
     */
    public static function intendedRouteFor(?Authenticatable $user): string
    {
        if (! $user) {
            return route('login', absolute: false);
        }

        if (method_exists($user, 'hasRole')) {
            if ($user->hasRole('admin')) {
                return route('dashboard', absolute: false);
            }
            if ($user->hasRole('cargador')) {
                return route('panel.carga', absolute: false);
            }
            if ($user->hasRole('consultor')) {
                return route('panel.consulta', absolute: false);
            }
        }

        return route('personas.index', absolute: false);
    }
}
