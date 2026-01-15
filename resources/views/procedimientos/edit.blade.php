<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('procedimientos.show', $procedimiento) }}" class="text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100">
                ← Volver
            </a>
            <h2 class="font-semibold text-xl md:text-2xl text-gray-800 dark:text-gray-200 leading-tight">
                📋 Editar Procedimiento: {{ $procedimiento->legajo_fiscal }}
            </h2>
        </div>
    </x-slot>

    <div class="py-6 md:py-12">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 md:p-8">

                    <form action="{{ route('procedimientos.update', $procedimiento) }}" method="POST" class="space-y-6">
                        @csrf
                        @method('PUT')

                        {{-- Errores de validación --}}
                        @if ($errors->any())
                            <div class="bg-danger-50 dark:bg-danger-900/20 border-l-4 border-danger-500 dark:border-danger-700 p-4 rounded">
                                <p class="text-danger-700 dark:text-danger-100 font-medium mb-2">❌ Por favor corrige los siguientes errores:</p>
                                <ul class="list-disc list-inside text-sm text-danger-600 dark:text-danger-200 space-y-1">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div>
                            <label for="legajo_fiscal" class="block font-medium text-sm text-gray-700 dark:text-gray-300 mb-2">Legajo Fiscal *</label>
                            <input type="text" name="legajo_fiscal" id="legajo_fiscal" value="{{ old('legajo_fiscal', $procedimiento->legajo_fiscal) }}"
                                   class="w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-primary-500 dark:focus:border-primary-600 focus:ring-primary-500 dark:focus:ring-primary-600" required>
                            @error('legajo_fiscal')
                                <p class="mt-1 text-sm text-danger-600 dark:text-danger-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="caratula" class="block font-medium text-sm text-gray-700 dark:text-gray-300 mb-2">Carátula *</label>
                            <textarea name="caratula" id="caratula" rows="3"
                                      class="w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-primary-500 dark:focus:border-primary-600 focus:ring-primary-500 dark:focus:ring-primary-600" required>{{ old('caratula', $procedimiento->caratula) }}</textarea>
                            @error('caratula')
                                <p class="mt-1 text-sm text-danger-600 dark:text-danger-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="fecha_procedimiento" class="block font-medium text-sm text-gray-700 dark:text-gray-300 mb-2">Fecha Procedimiento *</label>
                                <input type="date" name="fecha_procedimiento" id="fecha_procedimiento" value="{{ old('fecha_procedimiento', $procedimiento->fecha_procedimiento->format('Y-m-d')) }}"
                                       class="w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-primary-500 dark:focus:border-primary-600 focus:ring-primary-500 dark:focus:ring-primary-600" required>
                            </div>
                            <div>
                                <label for="hora_procedimiento" class="block font-medium text-sm text-gray-700 dark:text-gray-300 mb-2">Hora (Opcional)</label>
                                <input type="time" name="hora_procedimiento" id="hora_procedimiento" value="{{ old('hora_procedimiento', $procedimiento->hora_procedimiento ? $procedimiento->hora_procedimiento->format('H:i') : '') }}"
                                       class="w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-primary-500 dark:focus:border-primary-600 focus:ring-primary-500 dark:focus:ring-primary-600">
                            </div>
                        </div>
                        
                        <div>
                            <label for="brigada_id" class="block font-medium text-sm text-gray-700 dark:text-gray-300 mb-2">Brigada Actuante *</label>
                            <select name="brigada_id" id="brigada_id" class="w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-primary-500 dark:focus:border-primary-600 focus:ring-primary-500 dark:focus:ring-primary-600" required>
                                <option value="">Seleccione una brigada...</option>
                                @foreach ($brigadas as $brigada)
                                    <option value="{{ $brigada->id }}" {{ old('brigada_id', $procedimiento->brigada_id) == $brigada->id ? 'selected' : '' }}>
                                        {{ $brigada->nombre }}
                                    </option>
                                @endforeach
                            </select>
                            @error('brigada_id')
                                <p class="mt-1 text-sm text-danger-600 dark:text-danger-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="ufi_id" class="block font-medium text-sm text-gray-700 dark:text-gray-300 mb-2">UFI Interviniente *</label>
                            <select name="ufi_id" id="ufi_id" class="w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-primary-500 dark:focus:border-primary-600 focus:ring-primary-500 dark:focus:ring-primary-600" required>
                                <option value="">Seleccione una UFI...</option>
                                @foreach ($ufis as $ufi)
                                    <option value="{{ $ufi->id }}" {{ old('ufi_id', $procedimiento->ufi_id) == $ufi->id ? 'selected' : '' }}>
                                        {{ $ufi->nombre }}
                                    </option>
                                @endforeach
                            </select>
                            @error('ufi_id')
                                <p class="mt-1 text-sm text-danger-600 dark:text-danger-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block font-medium text-sm text-gray-700 dark:text-gray-300 mb-2">Órdenes Otorgadas por el Juez</label>
                            <div class="space-y-2">
                                <label class="flex items-center dark:text-gray-300">
                                    <input type="checkbox" name="orden_allanamiento" value="1" class="rounded border-gray-300 dark:border-gray-700" checked disabled>
                                    <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">(Allanamiento siempre se otorga)</span>
                                </label>
                                <label class="flex items-center dark:text-gray-300">
                                    <input type="checkbox" name="orden_secuestro" value="1" class="rounded border-gray-300 dark:border-gray-700" {{ old('orden_secuestro', $procedimiento->orden_secuestro) ? 'checked' : '' }}>
                                    <span class="ml-2 text-sm">Orden de Secuestro</span>
                                </label>
                                <label class="flex items-center dark:text-gray-300">
                                    <input type="checkbox" name="orden_detencion" value="1" class="rounded border-gray-300 dark:border-gray-700" {{ old('orden_detencion', $procedimiento->orden_detencion) ? 'checked' : '' }}>
                                    <span class="ml-2 text-sm">Orden de Detención</span>
                                </label>
                            </div>
                        </div>

                        <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200 dark:border-gray-700">
                            <a href="{{ route('procedimientos.show', $procedimiento) }}" class="inline-flex items-center px-4 py-2 bg-gray-600 dark:bg-gray-700 text-white text-sm font-semibold rounded-md hover:bg-gray-700 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition duration-150">
                                Cancelar
                            </a>
                            <x-primary-button type="submit">
                                Actualizar Procedimiento
                            </x-primary-button>
                        </div>
                    </form>
                    </div>
            </div>
        </div>
    </div>
</x-app-layout> ```
