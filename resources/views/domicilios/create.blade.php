<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Agregar Nuevo Domicilio') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <form action="{{ route('domicilios.store') }}" method="POST">
                        @csrf

                        <div class="border-b pb-4 mb-4">
                            <h3 class="text-lg font-medium leading-6 text-gray-900">Ubicación Principal</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                                <div>
                                    <label for="departamento" class="block text-sm font-medium text-gray-700">Departamento *</label>
                                    <input type="text" name="departamento" id="departamento" value="{{ old('departamento') }}"
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                                    @error('departamento')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="provincia" class="block text-sm font-medium text-gray-700">Provincia</label>
                                    <input type="text" name="provincia" id="provincia" value="{{ old('provincia', 'San Juan') }}"
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                </div>
                            </div>
                        </div>

                        <div class="border-b pb-4 mb-4">
                            <h3 class="text-lg font-medium leading-6 text-gray-900">Dirección (Calle o Barrio)</h3>
                            <p class="text-sm text-gray-500">Complete solo los campos que apliquen.</p>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                                <div>
                                    <label for="calle" class="block text-sm font-medium text-gray-700">Calle</label>
                                    <input type="text" name="calle" id="calle" value="{{ old('calle') }}"
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                </div>
                                <div>
                                    <label for="numero" class="block text-sm font-medium text-gray-700">Número</label>
                                    <input type="text" name="numero" id="numero" value="{{ old('numero') }}"
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                </div>
                                <div>
                                    <label for="barrio" class="block text-sm font-medium text-gray-700">Barrio</label>
                                    <input type="text" name="barrio" id="barrio" value="{{ old('barrio') }}"
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                </div>
                                <div>
                                    <label for="sector" class="block text-sm font-medium text-gray-700">Sector / Zona</label>
                                    <input type="text" name="sector" id="sector" value="{{ old('sector') }}"
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                </div>
                            </div>
                        </div>

                        <div class="border-b pb-4 mb-4">
                            <h3 class="text-lg font-medium leading-6 text-gray-900">Detalle (Manzana, Lote, Monoblock)</h3>
                             <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                                <div>
                                    <label for="manzana" class="block text-sm font-medium text-gray-700">Manzana</label>
                                    <input type="text" name="manzana" id="manzana" value="{{ old('manzana') }}"
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                </div>
                                <div>
                                    <label for="lote" class="block text-sm font-medium text-gray-700">Lote</label>
                                    <input type="text" name="lote" id="lote" value="{{ old('lote') }}"
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                </div>
                                <div>
                                    <label for="casa" class="block text-sm font-medium text-gray-700">Casa N°</label>
                                    <input type="text" name="casa" id="casa" value="{{ old('casa') }}"
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                </div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                                <div>
                                    <label for="monoblock" class="block text-sm font-medium text-gray-700">Monoblock / Edificio</label>
                                    <input type="text" name="monoblock" id="monoblock" value="{{ old('monoblock') }}"
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                </div>
                                <div>
                                    <label for="torre" class="block text-sm font-medium text-gray-700">Torre / Escalera</label>
                                    <input type="text" name="torre" id="torre" value="{{ old('torre') }}"
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                </div>
                            </div>
                             <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                                <div>
                                    <label for="piso" class="block text-sm font-medium text-gray-700">Piso</label>
                                    <input type="text" name="piso" id="piso" value="{{ old('piso') }}"
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                </div>
                                <div>
                                    <label for="depto" class="block text-sm font-medium text-gray-700">Depto</label>
                                    <input type="text" name="depto" id="depto" value="{{ old('depto') }}"
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                </div>
                            </div>
                        </div>

                         <div class="mb-4">
                            <label for="coordenadas_gps" class="block text-sm font-medium text-gray-700">Coordenadas GPS (Opcional)</label>
                            <input type="text" name="coordenadas_gps" id="coordenadas_gps" value="{{ old('coordenadas_gps') }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        </div>


                        <div class="flex justify-end space-x-3 mt-8 pt-6 border-t border-gray-200">
                            <a href="{{ route('domicilios.index') }}">
                                <x-secondary-button type="button">
                                    Cancelar
                                </x-secondary-button>
                            </a>
                            <x-primary-button type="submit">
                                Guardar Domicilio
                            </x-primary-button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>