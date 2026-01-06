<?php

namespace App\Http\Middleware;

use App\Models\ActivityLog;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware de Auditoría para Super Administradores
 * Registra cada acción crítica realizada por usuarios con rol super_admin
 */
class SuperAdminActivity
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // Solo registrar si el usuario es super_admin
        if ($user && $user->isSuperAdmin()) {
            // Registrar el acceso a la ruta
            ActivityLog::log(
                'super_admin_access',
                "Super Admin {$user->name} accedió a: {$request->path()}",
                [
                    'properties' => [
                        'method' => $request->method(),
                        'route' => $request->path(),
                        'route_name' => $request->route()?->getName(),
                        'params' => $request->all(),
                    ],
                    'severity' => $this->getSeverityByMethod($request->method()),
                ]
            );
        }

        return $next($request);
    }

    /**
     * Determinar la severidad según el método HTTP
     */
    private function getSeverityByMethod(string $method): string
    {
        return match ($method) {
            'DELETE' => 'critical',
            'PUT', 'PATCH', 'POST' => 'warning',
            default => 'info',
        };
    }
}
