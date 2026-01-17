<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <h2 class="font-semibold text-xl md:text-2xl text-gray-800 dark:text-gray-200 leading-tight">
                👤 Mi Perfil
            </h2>
        </div>
    </x-slot>

    <div class="py-6 md:py-12">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- Alertas --}}
            @if (session('status') === 'profile-updated')
                <div class="mb-6 bg-success-50 dark:bg-success-900/30 border-l-4 border-success-500 p-4 rounded-lg shadow-sm">
                    <div class="flex items-center">
                        <span class="text-2xl mr-3">✅</span>
                        <span class="text-success-800 dark:text-success-200 font-medium">Perfil actualizado correctamente</span>
                    </div>
                </div>
            @endif

            @if (session('status') === 'password-updated')
                <div class="mb-6 bg-success-50 dark:bg-success-900/30 border-l-4 border-success-500 p-4 rounded-lg shadow-sm">
                    <div class="flex items-center">
                        <span class="text-2xl mr-3">✅</span>
                        <span class="text-success-800 dark:text-success-200 font-medium">Contraseña actualizada correctamente</span>
                    </div>
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    
                    {{-- Información Institucional --}}
                    <div class="mb-8">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                                🏛️ Información Institucional
                            </h3>
                            <span class="text-xs text-gray-500 dark:text-gray-400 italic">Solo lectura</span>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                            {{-- Jerarquía --}}
                            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                                <div class="flex items-start gap-3">
                                    <div class="text-primary-600 dark:text-primary-400 text-xl">⭐</div>
                                    <div class="flex-1">
                                        <p class="text-xs text-gray-500 dark:text-gray-400 font-medium mb-1">Jerarquía</p>
                                        <p class="text-sm font-semibold text-gray-900 dark:text-gray-100">
                                            {{ $user->jerarquia ?? 'Sin Asignar' }}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            {{-- Rol del Sistema --}}
                            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                                <div class="flex items-start gap-3">
                                    <div class="text-primary-600 dark:text-primary-400 text-xl">🛡️</div>
                                    <div class="flex-1">
                                        <p class="text-xs text-gray-500 dark:text-gray-400 font-medium mb-1">Rol del Sistema</p>
                                        <p class="text-sm font-semibold text-gray-900 dark:text-gray-100">
                                            {{ $user->roles->first()->label ?? 'Sin Rol' }}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            {{-- Destino --}}
                            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                                <div class="flex items-start gap-3">
                                    <div class="text-primary-600 dark:text-primary-400 text-xl">📍</div>
                                    <div class="flex-1">
                                        <p class="text-xs text-gray-500 dark:text-gray-400 font-medium mb-1">Destino</p>
                                        <p class="text-sm font-semibold text-gray-900 dark:text-gray-100">
                                            {{ $user->brigada->nombre ?? 'Sin Asignar' }}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            {{-- Estado --}}
                            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                                <div class="flex items-start gap-3">
                                    <div class="text-success-600 dark:text-success-400 text-xl">✓</div>
                                    <div class="flex-1">
                                        <p class="text-xs text-gray-500 dark:text-gray-400 font-medium mb-1">Estado</p>
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-success-100 text-success-800 dark:bg-success-900 dark:text-success-200">
                                            {{ $user->active ? 'Activo' : 'Inactivo' }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr class="border-gray-200 dark:border-gray-700 mb-8">

                    {{-- Datos Personales --}}
                    <div class="mb-8" x-data="{ editing: false }">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                                👤 Datos Personales
                            </h3>
                            <button 
                                @click="editing = !editing"
                                type="button"
                                class="inline-flex items-center px-4 py-2 bg-primary-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-primary-700 active:bg-primary-900 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 transition ease-in-out duration-150"
                            >
                                <span x-show="!editing">✏️ Editar</span>
                                <span x-show="editing">❌ Cancelar</span>
                            </button>
                        </div>

                        {{-- Vista de Solo Lectura --}}
                        <div x-show="!editing" class="space-y-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                                    <p class="text-xs text-gray-500 dark:text-gray-400 font-medium mb-1">Nombre Completo</p>
                                    <p class="text-sm font-semibold text-gray-900 dark:text-gray-100">{{ $user->name }}</p>
                                </div>
                                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                                    <p class="text-xs text-gray-500 dark:text-gray-400 font-medium mb-1">Correo Electrónico</p>
                                    <p class="text-sm font-semibold text-gray-900 dark:text-gray-100">{{ $user->email }}</p>
                                </div>
                            </div>
                        </div>

                        {{-- Formulario de Edición --}}
                        <div x-show="editing" x-cloak>
                            @include('profile.partials.update-profile-information-form')
                        </div>
                    </div>

                    <hr class="border-gray-200 dark:border-gray-700 mb-8">

                    {{-- Seguridad --}}
                    <div x-data="{ changing: false }">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                                🔒 Seguridad
                            </h3>
                            <button 
                                @click="changing = !changing"
                                type="button"
                                class="inline-flex items-center px-4 py-2 bg-primary-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-primary-700 active:bg-primary-900 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 transition ease-in-out duration-150"
                            >
                                <span x-show="!changing">🔑 Cambiar Contraseña</span>
                                <span x-show="changing">❌ Cancelar</span>
                            </button>
                        </div>

                        {{-- Vista de Solo Lectura --}}
                        <div x-show="!changing" class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                            <p class="text-xs text-gray-500 dark:text-gray-400 font-medium mb-1">Contraseña</p>
                            <p class="text-sm text-gray-900 dark:text-gray-100">••••••••••••</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                                Última actualización: {{ $user->updated_at->diffForHumans() }}
                            </p>
                        </div>

                        {{-- Formulario de Cambio de Contraseña --}}
                        <div x-show="changing" x-cloak>
                            @include('profile.partials.update-password-form')
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
