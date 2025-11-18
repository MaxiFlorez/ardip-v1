<!DOCTYPE html>
<html lang="es" class="h-full">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Inicio de Sesión - ARDIP V1</title>
    @vite(['resources/css/app.css','resources/js/app.js'])
</head>
<body class="h-full min-h-screen flex flex-col items-center justify-center p-4" style="background-color: #0f172a;">
    <div class="w-full max-w-md">
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold tracking-wide mb-2" style="color: #ffffff;">ARDIP v1.0</h1>
            <p class="text-sm" style="color: #cbd5e1;">Archivo y Registro de Datos de Investigaciones y Procedimientos</p>
        </div>

        <!-- Panel de Login -->
        <div class="rounded-lg shadow-xl p-8" style="background-color: #ffffff;">
            <h2 class="text-2xl font-semibold text-center mb-6" style="color: #1e293b;">Panel de Acceso</h2>

            @if(session('status'))
                <div class="text-sm text-center mb-4" style="color: #16a34a;">{{ session('status') }}</div>
            @endif

            <form method="POST" action="{{ route('login') }}" novalidate>
                @csrf

                <!-- Usuario -->
                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium mb-2" style="color: #1e293b;">Usuario</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none" style="color: #94a3b8;">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                                <circle cx="12" cy="7" r="4" />
                            </svg>
                        </span>
                        <input id="email" name="email" type="text" placeholder="Usuario" value="{{ old('email') }}" autocomplete="username" required autofocus
                               class="block w-full pl-10 pr-4 py-2.5 rounded-lg border focus:outline-none focus:ring-2 transition" 
                               style="border-color: #cbd5e1; color: #1e293b; background-color: #ffffff; --tw-ring-color: #3b82f6;" />
                    </div>
                    @error('email')<p class="text-xs mt-1" style="color: #dc2626;">{{ $message }}</p>@enderror
                </div>

                <!-- Contraseña -->
                <div class="mb-4">
                    <label for="password" class="block text-sm font-medium mb-2" style="color: #1e293b;">Contraseña</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none" style="color: #94a3b8;">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="11" width="18" height="11" rx="2" ry="2" />
                                <path d="M7 11V7a5 5 0 0 1 10 0v4" />
                            </svg>
                        </span>
                        <input id="password" name="password" type="password" placeholder="Contraseña" autocomplete="current-password" required
                               class="block w-full pl-10 pr-10 py-2.5 rounded-lg border focus:outline-none focus:ring-2 transition" 
                               style="border-color: #cbd5e1; color: #1e293b; background-color: #ffffff; --tw-ring-color: #3b82f6;" />
                        <button type="button" id="toggle-password" class="absolute inset-y-0 right-0 pr-3 flex items-center focus:outline-none transition-colors"
                                style="color: #64748b;"
                                onmouseenter="this.style.color='#475569'"
                                onmouseleave="this.style.color='#64748b'">
                            <!-- Icono mostrar (visible cuando password oculto) -->
                            <svg id="icon-show" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            <!-- Icono ocultar (inicialmente hidden) -->
                            <svg id="icon-hide" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.477 0-8.268-2.943-9.542-7a10.05 10.05 0 012.26-3.955M6.223 6.223A9.956 9.956 0 0112 5c4.477 0 8.268 2.943 9.542 7a9.956 9.956 0 01-4.28 5.223M15 12a3 3 0 00-3-3m0 0a3 3 0 013 3m-3-3L3 21m9-12L21 3" />
                            </svg>
                        </button>
                    </div>
                    @error('password')<p class="text-xs mt-1" style="color: #dc2626;">{{ $message }}</p>@enderror
                </div>

                <!-- Recordarme -->
                <div class="mb-6 flex items-center">
                    <label class="inline-flex items-center text-sm select-none cursor-pointer" style="color: #64748b;">
                        <input type="checkbox" name="remember" class="rounded" style="border-color: #cbd5e1; accent-color: #3b82f6; width: 18px; height: 18px;">
                        <span class="ms-2">Recordarme</span>
                    </label>
                </div>

                <!-- Botones -->
                <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2.5 text-white font-semibold rounded-lg shadow-md focus:outline-none focus:ring-2 transition mb-3" 
                        style="background-color: #1e40af;"
                        onmouseover="this.style.backgroundColor='#1e3a8a'"
                        onmouseout="this.style.backgroundColor='#1e40af'"
                        onmousedown="this.style.backgroundColor='#172554'">Iniciar Sesión</button>
                <a href="mailto:soporte@ardip.local?subject=Ayuda%20-%20ARDIP" class="w-full inline-flex justify-center items-center px-4 py-2.5 font-medium rounded-lg transition" 
                   style="border: 1px solid #cbd5e1; color: #1e293b; background-color: transparent;"
                   onmouseover="this.style.backgroundColor='#f8fafc'"
                   onmouseout="this.style.backgroundColor='transparent'">Ayuda / Contacto soporte</a>
            </form>
        </div>

        <!-- Footer -->
        <div class="mt-6 text-center space-y-1" style="color: #64748b; font-size: 0.75rem;">
            <p class="leading-tight">Uso exclusivo de personal autorizado de la Policía de San Juan – Dirección D-5.</p>
            <p class="leading-tight">Datos personales protegidos por Ley N.º 25.326. Difusión no autorizada prohibida.</p>
            <p class="leading-tight">Actividad monitoreada.</p>
        </div>
    </div>
</body>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const btn = document.getElementById('toggle-password');
    if(!btn) return;
    const input = document.getElementById('password');
    const iconShow = document.getElementById('icon-show');
    const iconHide = document.getElementById('icon-hide');
    btn.addEventListener('click', () => {
        const isText = input.type === 'text';
        input.type = isText ? 'password' : 'text';
        iconShow.classList.toggle('hidden', !isText);
        iconHide.classList.toggle('hidden', isText);
    });
});
</script>
</html>
