<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-6" :status="session('status')" />

    <div class="text-center mb-8">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">🏛️ ARDIP</h1>
        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Sistema de Gestión Operativa</p>
    </div>

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                📧 Correo Electrónico
            </label>
            <input 
                id="email" 
                type="email" 
                name="email" 
                value="{{ old('email') }}" 
                required 
                autofocus 
                autocomplete="username"
                placeholder="tu.email@ardip.gob.ar"
                class="w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-primary-500 dark:focus:border-primary-600 focus:ring-primary-500 dark:focus:ring-primary-600"
            />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div>
            <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                🔐 Contraseña
            </label>
            <input 
                id="password" 
                type="password" 
                name="password" 
                required 
                autocomplete="current-password"
                placeholder="••••••••••"
                class="w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-primary-500 dark:focus:border-primary-600 focus:ring-primary-500 dark:focus:ring-primary-600"
            />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center">
            <input 
                id="remember_me" 
                type="checkbox" 
                name="remember" 
                class="rounded border-gray-300 text-primary-600 shadow-sm focus:ring-primary-500 dark:bg-gray-900 dark:border-gray-700 dark:checked:bg-primary-600"
            >
            <label for="remember_me" class="ms-2 text-sm text-gray-700 dark:text-gray-300">
                Recordarme en este dispositivo
            </label>
        </div>

        <!-- Submit Button -->
        <button 
            type="submit" 
            class="w-full flex items-center justify-center px-4 py-2 bg-primary-600 border border-transparent rounded-md font-semibold text-sm text-white uppercase tracking-widest hover:bg-primary-700 active:bg-primary-900 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150"
        >
            🔓 Iniciar Sesión
        </button>

        <!-- Support Link -->
        <div class="text-center mt-4">
            <a 
                href="mailto:soporte@ardip.gob.ar" 
                class="text-sm text-primary-600 dark:text-primary-400 hover:text-primary-700 dark:hover:text-primary-300 underline"
            >
                ¿Problemas de acceso? Contactar soporte
            </a>
        </div>
    </form>
</x-guest-layout>
