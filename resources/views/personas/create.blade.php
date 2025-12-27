<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Nueva Persona') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-visible shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    <form action="{{ route('personas.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <!-- DNI -->
                        <div class="mb-4">
                            <label for="dni" class="block text-sm font-medium text-gray-700">DNI *</label>
                            <input type="text" name="dni" id="dni" value="{{ old('dni') }}" maxlength="8" 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                            @error('dni')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Nombres -->
                        <div class="mb-4">
                            <label for="nombres" class="block text-sm font-medium text-gray-700">Nombres *</label>
                            <input type="text" name="nombres" id="nombres" value="{{ old('nombres') }}" 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                            @error('nombres')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Apellidos -->
                        <div class="mb-4">
                            <label for="apellidos" class="block text-sm font-medium text-gray-700">Apellidos *</label>
                            <input type="text" name="apellidos" id="apellidos" value="{{ old('apellidos') }}" 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                            @error('apellidos')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div><!-- Fecha de Nacimiento -->
                        <div class="mb-4">
                            <label for="fecha_nacimiento" class="block text-sm font-medium text-gray-700">Fecha de Nacimiento *</label>
                            <input type="date" name="fecha_nacimiento" id="fecha_nacimiento" value="{{ old('fecha_nacimiento') }}" 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                            @error('fecha_nacimiento')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Género -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Género *</label>
                            <div class="mt-2 space-x-4">
                                <label class="inline-flex items-center">
                                    <input type="radio" name="genero" value="masculino" {{ old('genero') == 'masculino' ? 'checked' : '' }} required>
                                    <span class="ml-2">Masculino</span>
                                </label>
                                <label class="inline-flex items-center">
                                    <input type="radio" name="genero" value="femenino" {{ old('genero') == 'femenino' ? 'checked' : '' }}>
                                    <span class="ml-2">Femenino</span>
                                </label>
                                <label class="inline-flex items-center">
                                    <input type="radio" name="genero" value="otro" {{ old('genero') == 'otro' ? 'checked' : '' }}>
                                    <span class="ml-2">Otro</span>
                                </label>
                            </div>
                            @error('genero')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Alias múltiples -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Alias/Apodos</label>
                            <div id="alias-container">
                                <input type="text" name="alias[]" placeholder="Alias 1" 
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                            <button type="button" onclick="agregarAlias()" 
                                    class="mt-2 text-blue-600">+ Agregar otro alias</button>
                            @error('alias')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            @error('alias.*')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Nacionalidad -->
                        <div class="mb-4">
                            <label for="nacionalidad" class="block text-sm font-medium text-gray-700">Nacionalidad</label>
                            <input type="text" name="nacionalidad" id="nacionalidad" value="{{ old('nacionalidad', 'Argentina') }}" 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @error('nacionalidad')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Estado Civil -->
                        <div class="mb-4">
                            <label for="estado_civil" class="block text-sm font-medium text-gray-700">Estado Civil</label>
                            <select name="estado_civil" id="estado_civil" 
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">Seleccionar...</option>
                                <option value="soltero" {{ old('estado_civil') == 'soltero' ? 'selected' : '' }}>Soltero/a</option>
                                <option value="casado" {{ old('estado_civil') == 'casado' ? 'selected' : '' }}>Casado/a</option>
                                <option value="divorciado" {{ old('estado_civil') == 'divorciado' ? 'selected' : '' }}>Divorciado/a</option>
                                <option value="viudo" {{ old('estado_civil') == 'viudo' ? 'selected' : '' }}>Viudo/a</option>
                                <option value="concubinato" {{ old('estado_civil') == 'concubinato' ? 'selected' : '' }}>Concubinato</option>
                            </select>
                            @error('estado_civil')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Foto -->
                        <div class="mb-4">
                            <label for="foto" class="block text-sm font-medium text-gray-700">Foto</label>
                            <input type="file" name="foto" id="foto" accept="image/*" 
                                   class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                            @error('foto')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Observaciones -->
                        <div class="mb-6">
                            <label for="observaciones" class="block text-sm font-medium text-gray-700">Observaciones</label>
                            <textarea name="observaciones" id="observaciones" rows="3" 
                                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('observaciones') }}</textarea>
                            @error('observaciones')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Botones -->
                        <div class="flex justify-end space-x-3 mt-8 pt-6 border-t border-gray-200">
                            <a href="{{ route('personas.index') }}" 
                               class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">
                                Cancelar
                            </a>
                            <button type="submit" 
                                    class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Guardar
                            </button>
                        </div>
                    </form>

                    <script>
                    function agregarAlias() {
                        const container = document.getElementById('alias-container');
                        const input = document.createElement('input');
                        input.type = 'text';
                        input.name = 'alias[]';
                        input.placeholder = 'Alias';
                        input.className = 'mt-2 block w-full rounded-md border-gray-300';
                        container.appendChild(input);
                    }
                    </script>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
