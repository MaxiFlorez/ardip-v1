<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        $user = auth()->user();
        
        // Admin → Dashboard
        if ($user->hasRole('admin')) {
            return redirect()->intended(route('dashboard', absolute: false));
        }
        
        // Cargador → Panel de Carga (ver procedimientos)
        if ($user->hasRole('cargador')) {
            return redirect()->intended(route('panel.carga', absolute: false));
        }
        
        // Operario → Panel de Consulta (buscar personas)
        if ($user->hasRole('consultor')) {
            return redirect()->intended(route('panel.consulta', absolute: false));
        }
        
        // Fallback por si hay un rol desconocido
        return redirect()->intended(route('personas.index', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        // Cerrar sesión
        Auth::guard('web')->logout();

        // Invalidar la sesión
        $request->session()->invalidate();

        // Regenerar token CSRF
        $request->session()->regenerateToken();

        // Limpiar todas las cookies
        $request->session()->flush();

        // Redirigir al login con mensaje
        return redirect()->route('login')->with('status', 'Sesión cerrada correctamente.');
    }
}
