<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Perfil') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            {{-- Ficha Operativa (Solo Lectura) --}}
            <div class="p-4 sm:p-8 bg-gradient-to-br from-primary-50 to-primary-100 dark:from-primary-900 dark:to-primary-800 shadow-md sm:rounded-lg border-l-4 border-primary-600">
                <div class="text-center">
                    <header>
                        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                            <i class="fas fa-id-badge mr-2"></i>{{ __('Ficha Operativa') }}
                        </h2>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                            {{ __('Información de identificación operativa (Solo lectura)') }}
                        </p>
                    </header>

                    <div class="mt-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        {{-- Jerarquía --}}
                        <div class="bg-white dark:bg-gray-800 rounded-lg p-4 border border-gray-200 dark:border-gray-700">
                            <div class="flex flex-col items-center">
                                <i class="fas fa-star text-warning-600 text-xl mb-2"></i>
                                <div>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 uppercase font-semibold">Jerarquía</p>
                                    <p class="text-sm font-bold text-gray-900 dark:text-gray-100">
                                        {{ Auth::user()->jerarquia ?? 'Sin Asignar' }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        {{-- Rol --}}
                        <div class="bg-white dark:bg-gray-800 rounded-lg p-4 border border-gray-200 dark:border-gray-700">
                            <div class="flex flex-col items-center">
                                <i class="fas fa-user-shield text-primary-600 text-xl mb-2"></i>
                                <div>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 uppercase font-semibold">Rol</p>
                                    <p class="text-sm font-bold text-gray-900 dark:text-gray-100">
                                        {{ Auth::user()->roles->first()->label ?? 'Sin Rol' }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        {{-- Destino --}}
                        <div class="bg-white dark:bg-gray-800 rounded-lg p-4 border border-gray-200 dark:border-gray-700">
                            <div class="flex flex-col items-center">
                                <i class="fas fa-map-marker-alt text-secondary-600 text-xl mb-2"></i>
                                <div>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 uppercase font-semibold">Destino</p>
                                    <p class="text-sm font-bold text-gray-900 dark:text-gray-100">
                                        {{ Auth::user()->brigada->nombre ?? 'Sin Asignar' }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        {{-- Estado --}}
                        <div class="bg-white dark:bg-gray-800 rounded-lg p-4 border border-gray-200 dark:border-gray-700">
                            <div class="flex flex-col items-center">
                                <i class="fas fa-circle-check text-success-600 text-xl mb-2"></i>
                                <div>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 uppercase font-semibold">Estado</p>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-success-100 text-success-800 dark:bg-success-800 dark:text-success-100">
                                        <i class="fas fa-check-circle mr-1"></i> Activo
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg mx-auto w-full">
                <div class="max-w-2xl mx-auto">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg mx-auto w-full">
                <div class="max-w-2xl mx-auto">
                    @include('profile.partials.update-password-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
