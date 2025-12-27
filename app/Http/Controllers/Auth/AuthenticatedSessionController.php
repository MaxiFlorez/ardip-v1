<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        
        // Determinar la ruta por defecto según el rol del usuario
        $defaultRoute = match (true) {
            $user->hasRole('admin') => route('dashboard', absolute: false),
            $user->hasRole('cargador') => route('procedimientos.create', absolute: false),
            $user->hasRole('consultor') => route('personas.index', absolute: false),
            default => route('personas.index', absolute: false),
        };
        
        // Redirigir a la URL prevista o a la ruta por defecto según el rol
        return redirect()->intended($defaultRoute);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        $request->session()->flush();

        return redirect()->route('login')->with('status', 'Sesión cerrada correctamente.');
    }
}
