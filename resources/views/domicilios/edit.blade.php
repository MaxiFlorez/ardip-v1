<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="javascript:window.history.back()" class="text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100">
                ← Volver
            </a>
            <h2 class="font-semibold text-xl md:text-2xl text-gray-800 dark:text-gray-200 leading-tight">
                🏠 Editar Domicilio
            </h2>
        </div>
    </x-slot>

    <div class="py-6 md:py-12">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 md:p-8">

                    <form action="{{ route('domicilios.update', $domicilio) }}" method="POST" class="space-y-6">
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

                        <div class="border-b dark:border-gray-700 pb-4">
                            <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-gray-100">Ubicación Principal</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                                <div>
                                    <label for="departamento" class="block font-medium text-sm text-gray-700 dark:text-gray-300 mb-2">Departamento *</label>
                                    <input type="text" name="departamento" id="departamento" value="{{ old('departamento', $domicilio->departamento) }}"
                                           class="w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-primary-500 dark:focus:border-primary-600 focus:ring-primary-500 dark:focus:ring-primary-600" required>
                                    @error('departamento')
                                        <p class="mt-1 text-sm text-danger-600 dark:text-danger-400">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="provincia" class="block font-medium text-sm text-gray-700 dark:text-gray-300 mb-2">Provincia</label>
                                    <input type="text" name="provincia" id="provincia" value="{{ old('provincia', $domicilio->provincia) }}"
                                           class="w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-primary-500 dark:focus:border-primary-600 focus:ring-primary-500 dark:focus:ring-primary-600">
                                </div>
                            </div>
                        </div>

                        <div class="border-b dark:border-gray-700 pb-4">
                            <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-gray-100">Dirección (Calle o Barrio)</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Complete solo los campos que apliquen.</p>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                                <div>
                                    <label for="calle" class="block font-medium text-sm text-gray-700 dark:text-gray-300 mb-2">Calle</label>
                                    <input type="text" name="calle" id="calle" value="{{ old('calle', $domicilio->calle) }}"
                                           class="w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-primary-500 dark:focus:border-primary-600 focus:ring-primary-500 dark:focus:ring-primary-600">
                                </div>
                                <div>
                                    <label for="numero" class="block font-medium text-sm text-gray-700 dark:text-gray-300 mb-2">Número</label>
                                    <input type="text" name="numero" id="numero" value="{{ old('numero', $domicilio->numero) }}"
                                           class="w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-primary-500 dark:focus:border-primary-600 focus:ring-primary-500 dark:focus:ring-primary-600">
                                </div>
                                <div>
                                    <label for="barrio" class="block font-medium text-sm text-gray-700 dark:text-gray-300 mb-2">Barrio</label>
                                    <input type="text" name="barrio" id="barrio" value="{{ old('barrio', $domicilio->barrio) }}"
                                           class="w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-primary-500 dark:focus:border-primary-600 focus:ring-primary-500 dark:focus:ring-primary-600">
                                </div>
                                <div>
                                    <label for="sector" class="block font-medium text-sm text-gray-700 dark:text-gray-300 mb-2">Sector / Zona</label>
                                    <input type="text" name="sector" id="sector" value="{{ old('sector', $domicilio->sector) }}"
                                           class="w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-primary-500 dark:focus:border-primary-600 focus:ring-primary-500 dark:focus:ring-primary-600">
                                </div>
                            </div>
                        </div>

                        <div class="border-b dark:border-gray-700 pb-4">
                            <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-gray-100">Detalle (Manzana, Lote, Monoblock)</h3>
                             <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                                <div>
                                    <label for="manzana" class="block font-medium text-sm text-gray-700 dark:text-gray-300 mb-2">Manzana</label>
                                    <input type="text" name="manzana" id="manzana" value="{{ old('manzana', $domicilio->manzana) }}"
                                           class="w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-primary-500 dark:focus:border-primary-600 focus:ring-primary-500 dark:focus:ring-primary-600">
                                </div>
                                <div>
                                    <label for="lote" class="block font-medium text-sm text-gray-700 dark:text-gray-300 mb-2">Lote</label>
                                    <input type="text" name="lote" id="lote" value="{{ old('lote', $domicilio->lote) }}"
                                           class="w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-primary-500 dark:focus:border-primary-600 focus:ring-primary-500 dark:focus:ring-primary-600">
                                </div>
                                <div>
                                    <label for="casa" class="block font-medium text-sm text-gray-700 dark:text-gray-300 mb-2">Casa N°</label>
                                    <input type="text" name="casa" id="casa" value="{{ old('casa', $domicilio->casa) }}"
                                           class="w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-primary-500 dark:focus:border-primary-600 focus:ring-primary-500 dark:focus:ring-primary-600">
                                </div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                                <div>
                                    <label for="monoblock" class="block font-medium text-sm text-gray-700 dark:text-gray-300 mb-2">Monoblock / Edificio</label>
                                    <input type="text" name="monoblock" id="monoblock" value="{{ old('monoblock', $domicilio->monoblock) }}"
                                           class="w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-primary-500 dark:focus:border-primary-600 focus:ring-primary-500 dark:focus:ring-primary-600">
                                </div>
                                <div>
                                    <label for="torre" class="block font-medium text-sm text-gray-700 dark:text-gray-300 mb-2">Torre / Escalera</label>
                                    <input type="text" name="torre" id="torre" value="{{ old('torre', $domicilio->torre) }}"
                                           class="w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-primary-500 dark:focus:border-primary-600 focus:ring-primary-500 dark:focus:ring-primary-600">
                                </div>
                            </div>
                             <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                                <div>
                                    <label for="piso" class="block font-medium text-sm text-gray-700 dark:text-gray-300 mb-2">Piso</label>
                                    <input type="text" name="piso" id="piso" value="{{ old('piso', $domicilio->piso) }}"
                                           class="w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-primary-500 dark:focus:border-primary-600 focus:ring-primary-500 dark:focus:ring-primary-600">
                                </div>
                                <div>
                                    <label for="depto" class="block font-medium text-sm text-gray-700 dark:text-gray-300 mb-2">Depto</label>
                                    <input type="text" name="depto" id="depto" value="{{ old('depto', $domicilio->depto) }}"
                                           class="w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-primary-500 dark:focus:border-primary-600 focus:ring-primary-500 dark:focus:ring-primary-600">
                                </div>
                            </div>
                        </div>

                         <div>
                            <label for="coordenadas_gps" class="block font-medium text-sm text-gray-700 dark:text-gray-300 mb-2">Coordenadas GPS (Opcional)</label>
                            <input type="text" name="coordenadas_gps" id="coordenadas_gps" value="{{ old('coordenadas_gps', $domicilio->coordenadas_gps) }}"
                                   class="w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-primary-500 dark:focus:border-primary-600 focus:ring-primary-500 dark:focus:ring-primary-600">
                        </div>

                        <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200 dark:border-gray-700">
                            <a href="{{ route('domicilios.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 dark:bg-gray-700 text-white text-sm font-semibold rounded-md hover:bg-gray-700 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition duration-150">
                                Cancelar
                            </a>
                            <x-primary-button type="submit">
                                Actualizar Domicilio
                            </x-primary-button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>