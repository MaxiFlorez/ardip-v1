<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-primary-600 text-white">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
            </div>
            <h2 class="font-semibold text-xl md:text-2xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Cargar Nuevo Procedimiento') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">

                    <!-- Info Box -->
                    <div class="mb-6 bg-primary-50 dark:bg-primary-900/20 border-l-4 border-primary-600 p-4 rounded-r-lg">
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-primary-600 dark:text-primary-400 mt-0.5 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                            </svg>
                            <p class="text-sm text-primary-800 dark:text-primary-300">
                                Complete los datos del procedimiento. Después podrá agregar domicilios, personas involucradas y documentación.
                            </p>
                        </div>
                    </div>

                    <form action="{{ route('procedimientos.store') }}" method="POST">
                        @csrf

                        <!-- Sección: Información Básica -->
                        <div class="mb-8">
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4 pb-2 border-b border-gray-200 dark:border-gray-700">
                                📋 Información Básica
                            </h3>

                            <div class="space-y-4">
                                <div>
                                    <label for="legajo_fiscal" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Legajo Fiscal *
                                    </label>
                                    <input type="text" name="legajo_fiscal" id="legajo_fiscal" value="{{ old('legajo_fiscal') }}"
                                           class="block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-primary-600 focus:ring-primary-600" required>
                                    @error('legajo_fiscal')
                                        <p class="mt-1 text-sm text-danger-600 dark:text-danger-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="caratula" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Carátula *
                                    </label>
                                    <textarea name="caratula" id="caratula" rows="3"
                                              class="block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-primary-600 focus:ring-primary-600" required>{{ old('caratula') }}</textarea>
                                    @error('caratula')
                                        <p class="mt-1 text-sm text-danger-600 dark:text-danger-400">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Sección: Fecha y Hora -->
                        <div class="mb-8">
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4 pb-2 border-b border-gray-200 dark:border-gray-700">
                                📅 Fecha y Hora
                            </h3>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="fecha_procedimiento" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Fecha del Procedimiento *
                                    </label>
                                    <input type="date" name="fecha_procedimiento" id="fecha_procedimiento" value="{{ old('fecha_procedimiento', date('Y-m-d')) }}"
                                           class="block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-primary-600 focus:ring-primary-600" required>
                                    @error('fecha_procedimiento')
                                        <p class="mt-1 text-sm text-danger-600 dark:text-danger-400">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="hora_procedimiento" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Hora (Opcional)
                                    </label>
                                    <input type="time" name="hora_procedimiento" id="hora_procedimiento" value="{{ old('hora_procedimiento') }}"
                                           class="block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-primary-600 focus:ring-primary-600">
                                </div>
                            </div>
                        </div>

                        <!-- Sección: Organismos Intervinientes -->
                        <div class="mb-8">
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4 pb-2 border-b border-gray-200 dark:border-gray-700">
                                👮 Organismos Intervinientes
                            </h3>

                            <div class="space-y-4">
                                <div>
                                    <label for="brigada_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Brigada Actuante *
                                    </label>
                                    <select name="brigada_id" id="brigada_id" class="block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-primary-600 focus:ring-primary-600" required>
                                        <option value="">Seleccione una brigada...</option>
                                        @foreach ($brigadas as $brigada)
                                            <option value="{{ $brigada->id }}" {{ old('brigada_id') == $brigada->id ? 'selected' : '' }}>
                                                {{ $brigada->nombre }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('brigada_id')
                                        <p class="mt-1 text-sm text-danger-600 dark:text-danger-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="ufi_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        UFI Interviniente *
                                    </label>
                                    <select name="ufi_id" id="ufi_id" class="block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-primary-600 focus:ring-primary-600" required>
                                        <option value="">Seleccione una UFI...</option>
                                        @foreach ($ufis as $ufi)
                                            <option value="{{ $ufi->id }}" {{ old('ufi_id') == $ufi->id ? 'selected' : '' }}>
                                                {{ $ufi->nombre }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('ufi_id')
                                        <p class="mt-1 text-sm text-danger-600 dark:text-danger-400">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Sección: Órdenes Judiciales -->
                        <div class="mb-8">
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4 pb-2 border-b border-gray-200 dark:border-gray-700">
                                ⚖️ Órdenes Judiciales
                            </h3>

                            <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">
                                    Órdenes Otorgadas por el Juez
                                </label>
                                <div class="space-y-3">
                                    <label class="flex items-center p-2 rounded hover:bg-gray-100 dark:hover:bg-gray-600/50 transition cursor-not-allowed opacity-60">
                                        <input type="checkbox" name="orden_allanamiento" value="1" 
                                               class="rounded border-gray-300 dark:border-gray-600 text-primary-600 focus:ring-primary-600" checked disabled>
                                        <span class="ml-3 text-sm text-gray-600 dark:text-gray-400">
                                            Orden de Allanamiento (siempre incluida)
                                        </span>
                                    </label>
                                    <label class="flex items-center p-2 rounded hover:bg-gray-100 dark:hover:bg-gray-600/50 transition cursor-pointer">
                                        <input type="checkbox" name="orden_secuestro" value="1" 
                                               class="rounded border-gray-300 dark:border-gray-600 text-primary-600 focus:ring-primary-600" {{ old('orden_secuestro') ? 'checked' : '' }}>
                                        <span class="ml-3 text-sm text-gray-700 dark:text-gray-300">
                                            Orden de Secuestro
                                        </span>
                                    </label>
                                    <label class="flex items-center p-2 rounded hover:bg-gray-100 dark:hover:bg-gray-600/50 transition cursor-pointer">
                                        <input type="checkbox" name="orden_detencion" value="1" 
                                               class="rounded border-gray-300 dark:border-gray-600 text-primary-600 focus:ring-primary-600" {{ old('orden_detencion') ? 'checked' : '' }}>
                                        <span class="ml-3 text-sm text-gray-700 dark:text-gray-300">
                                            Orden de Detención
                                        </span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Botones de Acción -->
                        <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200 dark:border-gray-700">
                            <a href="{{ url()->previous() }}" class="inline-flex items-center px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white font-medium rounded-lg shadow-sm transition">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                Cancelar
                            </a>
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-success-600 hover:bg-success-700 text-white font-medium rounded-lg shadow-sm transition">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Guardar y Continuar
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>