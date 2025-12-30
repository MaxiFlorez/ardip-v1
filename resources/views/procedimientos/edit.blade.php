<x-app-layout>  <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Editar Procedimiento') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <form action="{{ route('procedimientos.update', $procedimiento) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label for="legajo_fiscal" class="block text-sm font-medium text-gray-700">Legajo Fiscal *</label>
                            <input type="text" name="legajo_fiscal" id="legajo_fiscal" value="{{ old('legajo_fiscal', $procedimiento->legajo_fiscal) }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                            @error('legajo_fiscal')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="caratula" class="block text-sm font-medium text-gray-700">Carátula *</label>
                            <textarea name="caratula" id="caratula" rows="3"
                                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>{{ old('caratula', $procedimiento->caratula) }}</textarea>
                            @error('caratula')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div>
                                <label for="fecha_procedimiento" class="block text-sm font-medium text-gray-700">Fecha Procedimiento *</label>
                                <input type="date" name="fecha_procedimiento" id="fecha_procedimiento" value="{{ old('fecha_procedimiento', $procedimiento->fecha_procedimiento->format('Y-m-d')) }}"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                            </div>
                            <div>
                                <label for="hora_procedimiento" class="block text-sm font-medium text-gray-700">Hora (Opcional)</label>
                                <input type="time" name="hora_procedimiento" id="hora_procedimiento" value="{{ old('hora_procedimiento', $procedimiento->hora_procedimiento ? $procedimiento->hora_procedimiento->format('H:i') : '') }}"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <label for="brigada_id" class="block text-sm font-medium text-gray-700">Brigada Actuante *</label>
                            <select name="brigada_id" id="brigada_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                                <option value="">Seleccione una brigada...</option>
                                @foreach ($brigadas as $brigada)
                                    <option value="{{ $brigada->id }}" {{ old('brigada_id', $procedimiento->brigada_id) == $brigada->id ? 'selected' : '' }}>
                                        {{ $brigada->nombre }}
                                    </option>
                                @endforeach
                            </select>
                            @error('brigada_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="ufi_id" class="block text-sm font-medium text-gray-700">UFI Interviniente *</label>
                            <select name="ufi_id" id="ufi_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                                <option value="">Seleccione una UFI...</option>
                                @foreach ($ufis as $ufi)
                                    <option value="{{ $ufi->id }}" {{ old('ufi_id', $procedimiento->ufi_id) == $ufi->id ? 'selected' : '' }}>
                                        {{ $ufi->nombre }}
                                    </option>
                                @endforeach
                            </select>
                            @error('ufi_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Órdenes Otorgadas por el Juez</label>
                            <div class="space-y-2">
                                <label class="flex items-center">
                                    <input type="checkbox" name="orden_allanamiento" value="1" class="rounded border-gray-300" checked disabled>
                                    <span class="ml-2 text-sm text-gray-600">(Allanamiento siempre se otorga)</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" name="orden_secuestro" value="1" class="rounded border-gray-300" {{ old('orden_secuestro', $procedimiento->orden_secuestro) ? 'checked' : '' }}>
                                    <span class="ml-2 text-sm text-gray-900">Orden de Secuestro</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" name="orden_detencion" value="1" class="rounded border-gray-300" {{ old('orden_detencion', $procedimiento->orden_detencion) ? 'checked' : '' }}>
                                    <span class="ml-2 text-sm text-gray-900">Orden de Detención</span>
                                </label>
                            </div>
                        </div>

                        <div class="flex justify-end space-x-3 mt-8 pt-6 border-t border-gray-200">
                            <a href="{{ route('procedimientos.show', $procedimiento) }}"
                               class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">
                                Cancelar
                            </a>
                            <button type="submit"
                                     class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Actualizar Procedimiento
                            </button>
                        </div>
                    </form>
                    </div>
            </div>
        </div>
    </div>
</x-app-layout> ```
