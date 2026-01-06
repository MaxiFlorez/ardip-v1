<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.users.index') }}" class="text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100">
                ← Volver
            </a>
            <h2 class="font-semibold text-xl md:text-2xl text-gray-800 dark:text-gray-200 leading-tight">
                ➕ Crear Nuevo Usuario
            </h2>
        </div>
    </x-slot>

    <div class="py-6 md:py-12">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 md:p-8">
                    
                    {{-- Errores de validación --}}
                    @if ($errors->any())
                        <div class="mb-6 bg-danger-50 border-l-4 border-danger-500 p-4 rounded">
                            <p class="text-danger-700 font-medium mb-2">❌ Por favor corrige los siguientes errores:</p>
                            <ul class="list-disc list-inside text-sm text-danger-600 space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('admin.users.store') }}" class="space-y-6">
                        @csrf

                        {{-- Nombre --}}
                        <div>
                            <label for="name" class="block font-medium text-sm text-gray-700 dark:text-gray-300 mb-2">
                                Nombre Completo <span class="text-danger-500">*</span>
                            </label>
                            <input 
                                type="text" 
                                name="name" 
                                id="name" 
                                value="{{ old('name') }}"
                                required
                                class="w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-primary-500 dark:focus:border-primary-600 focus:ring-primary-500 dark:focus:ring-primary-600"
                                placeholder="Ej: Juan Pérez"
                            >
                        </div>

                        {{-- Email --}}
                        <div>
                            <label for="email" class="block font-medium text-sm text-gray-700 dark:text-gray-300 mb-2">
                                Email <span class="text-danger-500">*</span>
                            </label>
                            <input 
                                type="email" 
                                name="email" 
                                id="email" 
                                value="{{ old('email') }}"
                                required
                                class="w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-primary-500 dark:focus:border-primary-600 focus:ring-primary-500 dark:focus:ring-primary-600"
                                placeholder="usuario@ardip.com"
                            >
                        </div>

                        {{-- Contraseña --}}
                        <div>
                            <label for="password" class="block font-medium text-sm text-gray-700 dark:text-gray-300 mb-2">
                                Contraseña <span class="text-danger-500">*</span>
                            </label>
                            <div class="relative">
                                <input 
                                    type="password" 
                                    name="password" 
                                    id="password" 
                                    required
                                    class="w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-primary-500 dark:focus:border-primary-600 focus:ring-primary-500 dark:focus:ring-primary-600 pr-10"
                                    placeholder="Mínimo 8 caracteres"
                                >
                                <button 
                                    type="button" 
                                    onclick="togglePassword('password')" 
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200"
                                >
                                    <svg id="eye-password" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                    <svg id="eye-slash-password" class="h-5 w-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>
                                    </svg>
                                </button>
                            </div>
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Mínimo 8 caracteres</p>
                        </div>

                        {{-- Confirmar Contraseña --}}
                        <div>
                            <label for="password_confirmation" class="block font-medium text-sm text-gray-700 dark:text-gray-300 mb-2">
                                Confirmar Contraseña <span class="text-danger-500">*</span>
                            </label>
                            <div class="relative">
                                <input 
                                    type="password" 
                                    name="password_confirmation" 
                                    id="password_confirmation" 
                                    required
                                    class="w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-primary-500 dark:focus:border-primary-600 focus:ring-primary-500 dark:focus:ring-primary-600 pr-10"
                                    placeholder="Repite la contraseña"
                                >
                                <button 
                                    type="button" 
                                    onclick="togglePassword('password_confirmation')" 
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200"
                                >
                                    <svg id="eye-password_confirmation" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                    <svg id="eye-slash-password_confirmation" class="h-5 w-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        {{-- Rol --}}
                        <div>
                            <label for="role_id" class="block font-medium text-sm text-gray-700 dark:text-gray-300 mb-2">
                                Rol <span class="text-danger-500">*</span>
                            </label>
                            <select 
                                name="role_id" 
                                id="role_id" 
                                required
                                class="w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-primary-500 dark:focus:border-primary-600 focus:ring-primary-500 dark:focus:ring-primary-600"
                            >
                                <option value="">Selecciona un rol...</option>
                                @foreach ($roles as $role)
                                    <option value="{{ $role->id }}" @selected(old('role_id') == $role->id)>
                                        {{ $role->label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Brigada --}}
                        <div>
                            <label for="brigada_id" class="block font-medium text-sm text-gray-700 dark:text-gray-300 mb-2">
                                Brigada (Opcional)
                            </label>
                            <select 
                                name="brigada_id" 
                                id="brigada_id" 
                                class="w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-primary-500 dark:focus:border-primary-600 focus:ring-primary-500 dark:focus:ring-primary-600"
                            >
                                <option value="">Sin brigada asignada</option>
                                @foreach ($brigadas as $brigada)
                                    <option value="{{ $brigada->id }}" @selected(old('brigada_id') == $brigada->id)>
                                        {{ $brigada->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Estado Activo --}}
                        <div class="flex items-center gap-3">
                            <input 
                                type="checkbox" 
                                name="active" 
                                id="active" 
                                value="1"
                                checked
                                class="rounded border-gray-300 text-primary-600 shadow-sm focus:ring-primary-500 dark:focus:ring-primary-600 dark:focus:ring-offset-gray-800"
                            >
                            <label for="active" class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                Cuenta Activa
                            </label>
                        </div>

                        {{-- Botones --}}
                        <div class="flex items-center gap-4 pt-4">
                            <button type="submit" class="px-6 py-2 bg-primary-600 text-white rounded-md hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 transition duration-150 font-medium">
                                💾 Crear Usuario
                            </button>
                            <a href="{{ route('admin.users.index') }}" class="px-6 py-2 text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 transition duration-150">
                                Cancelar
                            </a>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

    {{-- Script para mostrar/ocultar contraseña --}}
    <script>
        function togglePassword(fieldId) {
            const field = document.getElementById(fieldId);
            const eyeIcon = document.getElementById('eye-' + fieldId);
            const eyeSlashIcon = document.getElementById('eye-slash-' + fieldId);
            
            if (field.type === 'password') {
                field.type = 'text';
                eyeIcon.classList.add('hidden');
                eyeSlashIcon.classList.remove('hidden');
            } else {
                field.type = 'password';
                eyeIcon.classList.remove('hidden');
                eyeSlashIcon.classList.add('hidden');
            }
        }
    </script>
</x-app-layout>







