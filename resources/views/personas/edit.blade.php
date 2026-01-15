<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('personas.index') }}" class="text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100">
                ← Volver
            </a>
            <h2 class="font-semibold text-xl md:text-2xl text-gray-800 dark:text-gray-200 leading-tight">
                👤 Editar Persona: {{ $persona->nombres }} {{ $persona->apellidos }}
            </h2>
        </div>
    </x-slot>

    <div class="py-6 md:py-12">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 md:p-8">

                    {{-- IMPORTANTE: enctype="multipart/form-data" --}}
                    <form action="{{ route('personas.update', $persona) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
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
                            <label for="dni" class="block font-medium text-sm text-gray-700 dark:text-gray-300 mb-2">DNI *</label>
                            <input type="text" name="dni" id="dni" value="{{ old('dni', $persona->dni) }}" maxlength="8"
                                    class="w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-primary-500 dark:focus:border-primary-600 focus:ring-primary-500 dark:focus:ring-primary-600" required>
                            @error('dni')
                                <p class="mt-1 text-sm text-danger-600 dark:text-danger-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="nombres" class="block font-medium text-sm text-gray-700 dark:text-gray-300 mb-2">Nombres *</label>
                            <input type="text" name="nombres" id="nombres" value="{{ old('nombres', $persona->nombres) }}"
                                    class="w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-primary-500 dark:focus:border-primary-600 focus:ring-primary-500 dark:focus:ring-primary-600" required>
                            @error('nombres')
                                <p class="mt-1 text-sm text-danger-600 dark:text-danger-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="apellidos" class="block font-medium text-sm text-gray-700 dark:text-gray-300 mb-2">Apellidos *</label>
                            <input type="text" name="apellidos" id="apellidos" value="{{ old('apellidos', $persona->apellidos) }}"
                                    class="w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-primary-500 dark:focus:border-primary-600 focus:ring-primary-500 dark:focus:ring-primary-600" required>
                            @error('apellidos')
                                <p class="mt-1 text-sm text-danger-600 dark:text-danger-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="fecha_nacimiento" class="block font-medium text-sm text-gray-700 dark:text-gray-300 mb-2">Fecha de Nacimiento *</label>
                            <input type="date" name="fecha_nacimiento" id="fecha_nacimiento"
                                    value="{{ old('fecha_nacimiento', $persona->fecha_nacimiento->format('Y-m-d')) }}"
                                    class="w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-primary-500 dark:focus:border-primary-600 focus:ring-primary-500 dark:focus:ring-primary-600" required>
                            @error('fecha_nacimiento')
                                <p class="mt-1 text-sm text-danger-600 dark:text-danger-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block font-medium text-sm text-gray-700 dark:text-gray-300 mb-2">Género *</label>
                            <div class="mt-2 space-x-4">
                                <label class="inline-flex items-center dark:text-gray-300">
                                    <input type="radio" name="genero" value="masculino"
                                            {{ old('genero', $persona->genero) == 'masculino' ? 'checked' : '' }} required>
                                    <span class="ml-2">Masculino</span>
                                </label>
                                <label class="inline-flex items-center dark:text-gray-300">
                                    <input type="radio" name="genero" value="femenino"
                                            {{ old('genero', $persona->genero) == 'femenino' ? 'checked' : '' }}>
                                    <span class="ml-2">Femenino</span>
                                </label>
                                <label class="inline-flex items-center dark:text-gray-300">
                                    <input type="radio" name="genero" value="otro"
                                            {{ old('genero', $persona->genero) == 'otro' ? 'checked' : '' }}>
                                    <span class="ml-2">Otro</span>
                                </label>
                            </div>
                            @error('genero')
                                <p class="mt-1 text-sm text-danger-600 dark:text-danger-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block font-medium text-sm text-gray-700 dark:text-gray-300 mb-2">Alias/Apodos</label>
                            <div id="alias-container" class="space-y-2">
                                @php($aliases = old('alias', $persona->aliases->pluck('alias')->toArray()))
                                @if(!empty($aliases))
                                    @foreach($aliases as $i => $al)
                                        <input type="text" name="alias[]" value="{{ $al }}" placeholder="Alias {{ $i+1 }}"
                                               class="block w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-primary-500 dark:focus:border-primary-600 focus:ring-primary-500 dark:focus:ring-primary-600">
                                    @endforeach
                                @else
                                    <input type="text" name="alias[]" placeholder="Alias 1"
                                           class="block w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-primary-500 dark:focus:border-primary-600 focus:ring-primary-500 dark:focus:ring-primary-600">
                                @endif
                            </div>
                            <button type="button" onclick="agregarAlias()" 
                                    class="mt-2 text-primary-600 dark:text-primary-400 hover:text-primary-700 dark:hover:text-primary-300 font-medium">+ Agregar otro alias</button>
                            @error('alias')
                                <p class="mt-1 text-sm text-danger-600 dark:text-danger-400">{{ $message }}</p>
                            @enderror
                            @error('alias.*')
                                <p class="mt-1 text-sm text-danger-600 dark:text-danger-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="nacionalidad" class="block font-medium text-sm text-gray-700 dark:text-gray-300 mb-2">Nacionalidad</label>
                            <input type="text" name="nacionalidad" id="nacionalidad" value="{{ old('nacionalidad', $persona->nacionalidad) }}"
                                    class="w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-primary-500 dark:focus:border-primary-600 focus:ring-primary-500 dark:focus:ring-primary-600">
                            @error('nacionalidad')
                                <p class="mt-1 text-sm text-danger-600 dark:text-danger-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="estado_civil" class="block font-medium text-sm text-gray-700 dark:text-gray-300 mb-2">Estado Civil</label>
                            <select name="estado_civil" id="estado_civil"
                                     class="w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-primary-500 dark:focus:border-primary-600 focus:ring-primary-500 dark:focus:ring-primary-600">
                                <option value="">Seleccionar...</option>
                                <option value="soltero" {{ old('estado_civil', $persona->estado_civil) == 'soltero' ? 'selected' : '' }}>Soltero/a</option>
                                <option value="casado" {{ old('estado_civil', $persona->estado_civil) == 'casado' ? 'selected' : '' }}>Casado/a</option>
                                <option value="divorciado" {{ old('estado_civil', $persona->estado_civil) == 'divorciado' ? 'selected' : '' }}>Divorciado/a</option>
                                <option value="viudo" {{ old('estado_civil', $persona->estado_civil) == 'viudo' ? 'selected' : '' }}>Viudo/a</option>
                                <option value="concubinato" {{ old('estado_civil', $persona->estado_civil) == 'concubinato' ? 'selected' : '' }}>Concubinato</option>
                            </select>
                            @error('estado_civil')
                                <p class="mt-1 text-sm text-danger-600 dark:text-danger-400">{{ $message }}</p>
                            @enderror
                        </div>

                        @if($persona->foto)
                            <div>
                                <label class="block font-medium text-sm text-gray-700 dark:text-gray-300 mb-2">Foto actual</label>
                                <img src="{{ asset('storage/' . $persona->foto) }}"
                                      alt="Foto actual"
                                      class="w-32 h-32 object-cover rounded border-2 border-gray-300 dark:border-gray-600">
                            </div>
                        @endif
                        <div>
                            <label for="foto" class="block font-medium text-sm text-gray-700 dark:text-gray-300 mb-2">
                                {{ $persona->foto ? 'Cambiar foto' : 'Agregar foto' }}
                            </label>
                            <input type="file" name="foto" id="foto" accept="image/*" onchange="previewImage(event)"
                                    class="w-full text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-semibold file:bg-primary-50 dark:file:bg-primary-900 file:text-primary-700 dark:file:text-primary-400 hover:file:bg-primary-100 dark:hover:file:bg-primary-800">
                            @error('foto')
                                <p class="mt-1 text-sm text-danger-600 dark:text-danger-400">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Dejar vacío para mantener la foto actual</p>

                            <!-- Preview nueva imagen -->
                            <div class="mt-2 mb-2">
                                <img id="preview-foto" src="#" alt="Preview" class="hidden w-32 h-32 object-cover rounded-lg border-2 border-success-500 dark:border-success-700">
                                <p id="preview-label" class="hidden text-sm text-success-600 dark:text-success-400 font-medium mt-1">✅ Nueva foto seleccionada</p>
                            </div>
                        </div>

                        <div>
                            <label for="observaciones" class="block font-medium text-sm text-gray-700 dark:text-gray-300 mb-2">Observaciones</label>
                            <textarea name="observaciones" id="observaciones" rows="3"
                                       class="w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-primary-500 dark:focus:border-primary-600 focus:ring-primary-500 dark:focus:ring-primary-600">{{ old('observaciones', $persona->observaciones) }}</textarea>
                            @error('observaciones')
                                <p class="mt-1 text-sm text-danger-600 dark:text-danger-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200 dark:border-gray-700">
                            <a href="{{ route('personas.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 dark:bg-gray-700 text-white text-sm font-semibold rounded-md hover:bg-gray-700 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition duration-150">
                                Cancelar
                            </a>
                            <x-primary-button type="submit">
                                Actualizar
                            </x-primary-button>
                        </div>
                    </form>

                    <script>
                    function agregarAlias() {
                        const container = document.getElementById('alias-container');
                        const input = document.createElement('input');
                        input.type = 'text';
                        input.name = 'alias[]';
                        input.placeholder = 'Alias';
                        input.className = 'block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-primary-500 dark:focus:border-primary-600 focus:ring-primary-500 dark:focus:ring-primary-600 mt-2';
                        container.appendChild(input);
                    }
                    function previewImage(event) {
                        const input = event.target;
                        const preview = document.getElementById('preview-foto');
                        const label = document.getElementById('preview-label');
                        if (input.files && input.files[0]) {
                            const reader = new FileReader();
                            reader.onload = function(e) {
                                preview.src = e.target.result;
                                preview.classList.remove('hidden');
                                label.classList.remove('hidden');
                            };
                            reader.readAsDataURL(input.files[0]);
                        } else {
                            preview.src = '#';
                            preview.classList.add('hidden');
                            label.classList.add('hidden');
                        }
                    }
                    </script>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>