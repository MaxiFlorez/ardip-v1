<!DOCTYPE html>
<html lang="es" class="h-full">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Inicio de Sesión - ARDIP V1</title>
    @vite(['resources/css/app.css','resources/js/app.js'])
</head>
<body class="h-full flex flex-col" style="background-color: #0f172a; color: #1e293b;">
    <!-- Header Institucional -->
    <header class="w-full text-center pt-10 px-4">
        <h1 class="text-2xl md:text-3xl font-bold tracking-wide" style="color: #ffffff;">Policía de San Juan – Dirección D-5</h1>
        <p class="mt-2 text-lg font-semibold" style="color: #cbd5e1;">ARDIP- v1.0 -</p>
        <p class="text-sm md:text-base" style="color: #94a3b8;">Archivo y Registro de Datos de Investigaciones y Procedimientos</p>
    </header>

    <!-- Contenedor Principal -->
    <main class="flex-1 w-full flex items-center justify-center px-4 py-8">
        <div class="w-full max-w-md rounded-xl shadow-md p-8 space-y-6" style="background-color: #ffffff;">
            <h2 class="text-xl font-semibold text-center" style="color: #1e293b;">Panel de Acceso</h2>

            @if(session('status'))
                <div class="text-sm text-center" style="color: #16a34a;">{{ session('status') }}</div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-5" novalidate>
                @csrf

                <!-- Usuario -->
                <div>
                    <label for="email" class="block text-sm font-medium mb-1" style="color: #1e293b;">Usuario</label>
                    <div class="relative" x-data="{}">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none" style="color: #94a3b8;">
                            <!-- Ícono usuario -->
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M12 14c-4.418 0-8 1.79-8 4v1a1 1 0 001 1h14a1 1 0 001-1v-1c0-2.21-3.582-4-8-4z" />
                                <circle cx="12" cy="7" r="4" />
                            </svg>
                        </span>
                        <input id="email" name="email" type="text" placeholder="Usuario" value="{{ old('email') }}" autocomplete="username" required autofocus
                               class="block w-full pl-10 pr-3 py-2 rounded-md border bg-white focus:outline-none focus:ring-2 shadow-sm" 
                               style="border-color: #cbd5e1; color: #1e293b; --tw-ring-color: #3b82f6;" />
                    </div>
                    @error('email')<p class="text-xs mt-1" style="color: #dc2626;">{{ $message }}</p>@enderror
                </div>

                <!-- Contraseña -->
                <div x-data="{ show:false }">
                    <label for="password" class="block text-sm font-medium mb-1" style="color: #1e293b;">Contraseña</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none" style="color: #94a3b8;">
                            <!-- Ícono candado -->
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="4" y="11" width="16" height="9" rx="2" ry="2" />
                                <path d="M8 11V8a4 4 0 018 0v3" />
                            </svg>
                        </span>
                        <input :type="show ? 'text' : 'password'" id="password" name="password" placeholder="Contraseña" autocomplete="current-password" required
                               class="block w-full pl-10 pr-10 py-2 rounded-md border bg-white focus:outline-none focus:ring-2 shadow-sm" 
                               style="border-color: #cbd5e1; color: #1e293b; --tw-ring-color: #3b82f6;" />
                        <button type="button" @click="show = !show" :aria-label="show ? 'Ocultar contraseña' : 'Mostrar contraseña'"
                                class="absolute inset-y-0 right-0 pr-3 flex items-center focus:outline-none transition-colors"
                                style="color: #64748b;"
                                @mouseenter="$el.style.color='#475569'"
                                @mouseleave="$el.style.color='#64748b'">
                            <svg x-show="!show" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            <svg x-show="show" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.477 0-8.268-2.943-9.542-7a10.05 10.05 0 012.26-3.955M6.223 6.223A9.956 9.956 0 0112 5c4.477 0 8.268 2.943 9.542 7a9.956 9.956 0 01-4.28 5.223M15 12a3 3 0 00-3-3m0 0a3 3 0 013 3m-3-3L3 21m9-12L21 3" />
                            </svg>
                        </button>
                    </div>
                    @error('password')<p class="text-xs mt-1" style="color: #dc2626;">{{ $message }}</p>@enderror
                </div>

                <!-- Recordarme -->
                <div class="flex items-center justify-between">
                    <label class="inline-flex items-center text-sm select-none" style="color: #64748b;">
                        <input type="checkbox" name="remember" class="rounded" style="border-color: #cbd5e1; accent-color: #3b82f6;">
                        <span class="ms-2">Recordarme</span>
                    </label>
                    <a href="mailto:soporte@ardip.local?subject=Recuperar%20acceso%20-%20ARDIP" class="text-sm font-medium transition-colors" style="color: #3b82f6;" onmouseover="this.style.color='#2563eb'" onmouseout="this.style.color='#3b82f6'">Soporte</a>
                </div>

                <!-- Botones -->
                <div class="space-y-3">
                    <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 text-white font-semibold rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 transition" 
                            style="background-color: #3b82f6; --tw-ring-color: #3b82f6;"
                            onmouseover="this.style.backgroundColor='#2563eb'"
                            onmouseout="this.style.backgroundColor='#3b82f6'"
                            onmousedown="this.style.backgroundColor='#1d4ed8'">Iniciar Sesión</button>
                    <a href="mailto:soporte@ardip.local?subject=Ayuda%20-%20ARDIP" class="w-full inline-flex justify-center items-center px-4 py-2 font-medium rounded-md transition" 
                       style="border: 1px solid #cbd5e1; color: #1e293b; background-color: transparent;"
                       onmouseover="this.style.backgroundColor='#f1f5f9'"
                       onmouseout="this.style.backgroundColor='transparent'">Ayuda / Contacto soporte</a>
                </div>
            </form>
        </div>
    </main>

    <!-- Footer / Disclaimer -->
    <footer class="w-full text-center pb-6 px-4 space-y-1" style="color: #64748b; font-size: 0.6875rem;">
        <p class="leading-tight">Uso exclusivo de personal autorizado de la Policía de San Juan – Dirección D-5.</p>
        <p class="leading-tight">Datos personales protegidos por Ley N.º 25.326. Difusión no autorizada prohibida.</p>
        <p class="leading-tight">Actividad monitoreada.</p>
    </footer>
</body>
</html>
