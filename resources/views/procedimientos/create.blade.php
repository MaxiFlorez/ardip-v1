<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Cargar Nuevo Procedimiento') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <form action="{{ route('procedimientos.store') }}" method="POST">
                        @csrf

                        <div class="mb-4">
                            <label for="legajo_fiscal" class="block text-sm font-medium text-gray-700">Legajo Fiscal *</label>
                            <input type="text" name="legajo_fiscal" id="legajo_fiscal" value="{{ old('legajo_fiscal') }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                            @error('legajo_fiscal')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="caratula" class="block text-sm font-medium text-gray-700">Carátula *</label>
                            <textarea name="caratula" id="caratula" rows="3"
                                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>{{ old('caratula') }}</textarea>
                            @error('caratula')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="fecha_procedimiento" class="block text-sm font-medium text-gray-700">Fecha Procedimiento *</label>
                            <input type="date" name="fecha_procedimiento" id="fecha_procedimiento" value="{{ old('fecha_procedimiento', date('Y-m-d')) }}" max="{{ now()->toDateString() }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                            @error('fecha_procedimiento')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>


                        <div class="mb-6">
                            <label for="orden_judicial" class="block text-sm font-medium text-gray-700 mb-2">Orden Judicial</label>
                            <select id="orden_judicial" name="orden_judicial" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                <option value="">Seleccione…</option>
                                <option value="Detención en caso de secuestro positivo" {{ old('orden_judicial')==='Detención en caso de secuestro positivo' ? 'selected' : '' }}>Detención en caso de secuestro positivo</option>
                                <option value="Detención directa" {{ old('orden_judicial')==='Detención directa' ? 'selected' : '' }}>Detención directa</option>
                                <option value="Notificación al acusado" {{ old('orden_judicial')==='Notificación al acusado' ? 'selected' : '' }}>Notificación al acusado</option>
                                <option value="Secuestro y notificación" {{ old('orden_judicial')==='Secuestro y notificación' ? 'selected' : '' }}>Secuestro y notificación</option>
                            </select>
                            @error('orden_judicial')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex justify-end space-x-3 mt-8 pt-6 border-t border-gray-200">
                            <a href="{{ route('procedimientos.index') }}"
                               class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">
                                Cancelar
                            </a>
                            <button type="submit"
                                     class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Guardar y Continuar
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>