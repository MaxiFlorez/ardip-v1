<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-primary-600 text-white">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
            </div>
            <h2 class="font-semibold text-xl md:text-2xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Agregar Nueva Persona') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-visible shadow-sm sm:rounded-lg">
                <div class="p-6">
                    
                    {{-- IMPORTANTE: Agregar enctype="multipart/form-data" para archivos --}}
                    <form action="{{ route('personas.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        {{-- Input hidden para procedimiento_id si viene del hub --}}
                        @if(isset($procedimientoId) && $procedimientoId)
                            <input type="hidden" name="procedimiento_id" value="{{ $procedimientoId }}">
                            
                            {{-- Campos adicionales para vinculación --}}
                            <div class="mb-6 bg-primary-50 dark:bg-primary-900/20 border-l-4 border-primary-600 p-4 rounded-r-lg">
                                <div class="flex items-center mb-3">
                                    <svg class="w-5 h-5 text-primary-600 dark:text-primary-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path>
                                    </svg>
                                    <span class="font-semibold text-primary-800 dark:text-primary-200">
                                        Se vinculará automáticamente al Procedimiento
                                    </span>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label for="situacion_procesal" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                            Situación Procesal *
                                        </label>
                                        <select name="situacion_procesal" id="situacion_procesal" 
                                                class="block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-primary-600 focus:ring-primary-600" required>
                                            <option value="detenido">Detenido</option>
                                            <option value="notificado" selected>Notificado</option>
                                            <option value="no_hallado">No Hallado</option>
                                            <option value="contravencion">Contravención</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label for="observaciones_vinculo" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                            Observaciones del Vínculo
                                        </label>
                                        <input type="text" name="observaciones_vinculo" id="observaciones_vinculo" 
                                               class="block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-primary-600 focus:ring-primary-600" 
                                               placeholder="Opcional">
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Sección: Información Personal Básica -->
                        <div class="mb-8">
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4 pb-2 border-b border-gray-200 dark:border-gray-700">
                                📝 Información Personal Básica
                            </h3>
                        
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <!-- DNI -->
                                <div>
                                    <label for="dni" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        DNI *
                                    </label>
                                    <input type="text" name="dni" id="dni" value="{{ old('dni') }}" required maxlength="8"
                                           class="block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-primary-600 focus:ring-primary-600">
                                    @error('dni')
                                        <p class="mt-1 text-sm text-danger-600 dark:text-danger-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Nombres -->
                                <div>
                                    <label for="nombres" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Nombres *
                                    </label>
                                    <input type="text" name="nombres" id="nombres" value="{{ old('nombres') }}" required
                                           class="block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-primary-600 focus:ring-primary-600">
                                    @error('nombres')
                                        <p class="mt-1 text-sm text-danger-600 dark:text-danger-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Apellidos -->
                                <div>
                                    <label for="apellidos" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Apellidos *
                                    </label>
                                    <input type="text" name="apellidos" id="apellidos" value="{{ old('apellidos') }}" required
                                           class="block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-primary-600 focus:ring-primary-600">
                                    @error('apellidos')
                                        <p class="mt-1 text-sm text-danger-600 dark:text-danger-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Fecha de Nacimiento -->
                                <div>
                                    <label for="fecha_nacimiento" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Fecha de Nacimiento *
                                    </label>
                                    <input type="date" name="fecha_nacimiento" id="fecha_nacimiento" value="{{ old('fecha_nacimiento') }}" required
                                           class="block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-primary-600 focus:ring-primary-600">
                                    @error('fecha_nacimiento')
                                        <p class="mt-1 text-sm text-danger-600 dark:text-danger-400">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Género -->
                            <div class="mt-4">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Género *
                                </label>
                                <div class="flex flex-wrap gap-4">
                                    <label class="inline-flex items-center px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer transition">
                                        <input type="radio" name="genero" value="masculino" 
                                               {{ old('genero') === 'masculino' ? 'checked' : '' }}
                                               class="form-radio text-primary-600 focus:ring-primary-600" required>
                                        <span class="ml-2 text-gray-700 dark:text-gray-300">Masculino</span>
                                    </label>
                                    <label class="inline-flex items-center px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer transition">
                                        <input type="radio" name="genero" value="femenino" 
                                               {{ old('genero') === 'femenino' ? 'checked' : '' }}
                                               class="form-radio text-primary-600 focus:ring-primary-600">
                                        <span class="ml-2 text-gray-700 dark:text-gray-300">Femenino</span>
                                    </label>
                                    <label class="inline-flex items-center px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer transition">
                                        <input type="radio" name="genero" value="otro" 
                                               {{ old('genero') === 'otro' ? 'checked' : '' }}
                                               class="form-radio text-primary-600 focus:ring-primary-600">
                                        <span class="ml-2 text-gray-700 dark:text-gray-300">Otro</span>
                                    </label>
                                </div>
                                @error('genero')
                                    <p class="mt-1 text-sm text-danger-600 dark:text-danger-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Sección: Información Adicional -->
                        <div class="mb-8">
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4 pb-2 border-b border-gray-200 dark:border-gray-700">
                                📌 Información Adicional
                            </h3>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <!-- Nacionalidad -->
                                <div>
                                    <label for="nacionalidad" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Nacionalidad
                                    </label>
                                    <input type="text" name="nacionalidad" id="nacionalidad" value="{{ old('nacionalidad', 'Argentina') }}" 
                                           class="block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-primary-600 focus:ring-primary-600">
                                    @error('nacionalidad')
                                        <p class="mt-1 text-sm text-danger-600 dark:text-danger-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Estado Civil -->
                                <div>
                                    <label for="estado_civil" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Estado Civil
                                    </label>
                                    <select name="estado_civil" id="estado_civil" 
                                            class="block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-primary-600 focus:ring-primary-600">
                                        <option value="">Seleccionar...</option>
                                        <option value="soltero" {{ old('estado_civil') == 'soltero' ? 'selected' : '' }}>Soltero/a</option>
                                        <option value="casado" {{ old('estado_civil') == 'casado' ? 'selected' : '' }}>Casado/a</option>
                                        <option value="divorciado" {{ old('estado_civil') == 'divorciado' ? 'selected' : '' }}>Divorciado/a</option>
                                        <option value="viudo" {{ old('estado_civil') == 'viudo' ? 'selected' : '' }}>Viudo/a</option>
                                        <option value="concubinato" {{ old('estado_civil') == 'concubinato' ? 'selected' : '' }}>Concubinato</option>
                                    </select>
                                    @error('estado_civil')
                                        <p class="mt-1 text-sm text-danger-600 dark:text-danger-400">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Alias múltiples -->
                            <div class="mt-4">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Alias/Apodos
                                </label>
                                <div id="alias-container" class="space-y-2">
                                    <input type="text" name="alias[]" placeholder="Ingrese un alias o apodo" 
                                           class="block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-primary-600 focus:ring-primary-600">
                                </div>
                                <button type="button" onclick="agregarAlias()" 
                                        class="mt-2 inline-flex items-center text-sm text-primary-600 dark:text-primary-400 hover:text-primary-700 dark:hover:text-primary-300">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                    </svg>
                                    Agregar otro alias
                                </button>
                                @error('alias')
                                    <p class="mt-1 text-sm text-danger-600 dark:text-danger-400">{{ $message }}</p>
                                @enderror
                                @error('alias.*')
                                    <p class="mt-1 text-sm text-danger-600 dark:text-danger-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Sección: Fotografía -->
                        <div class="mb-8">
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4 pb-2 border-b border-gray-200 dark:border-gray-700">
                                📸 Fotografía
                            </h3>

                            <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4">
                                <div class="mb-4">
                                    <img id="preview-foto" src="#" alt="Preview" 
                                         class="hidden w-32 h-32 object-cover rounded-lg border-2 border-gray-300 dark:border-gray-600">
                                </div>

                                <input type="file" name="foto" id="foto" accept="image/jpeg,image/png,image/jpg"
                                       onchange="previewImage(event)"
                                       class="block w-full text-sm text-gray-500 dark:text-gray-400
                                              file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0
                                              file:text-sm file:font-semibold file:bg-primary-50 file:text-primary-700
                                              hover:file:bg-primary-100 dark:file:bg-primary-900/20 dark:file:text-primary-400
                                              dark:hover:file:bg-primary-900/40">

                                <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                                    Formatos permitidos: JPG, PNG | Tamaño máximo: 2MB
                                </p>
                                @error('foto')
                                    <p class="mt-1 text-sm text-danger-600 dark:text-danger-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Sección: Observaciones -->
                        <div class="mb-8">
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4 pb-2 border-b border-gray-200 dark:border-gray-700">
                                💬 Observaciones
                            </h3>

                            <div>
                                <label for="observaciones" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Información adicional relevante
                                </label>
                                <textarea name="observaciones" id="observaciones" rows="4" 
                                          class="block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-primary-600 focus:ring-primary-600"
                                          placeholder="Puede ingresar cualquier información adicional relevante sobre la persona...">{{ old('observaciones') }}</textarea>
                                @error('observaciones')
                                    <p class="mt-1 text-sm text-danger-600 dark:text-danger-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Botones de Acción -->
                        <div class="flex items-center justify-end gap-3 pt-6 border-t border-gray-200 dark:border-gray-700">
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
                                Guardar Persona
                            </button>
                        </div>
                    </form>

                    <script>
                    function agregarAlias() {
                        const container = document.getElementById('alias-container');
                        const input = document.createElement('input');
                        input.type = 'text';
                        input.name = 'alias[]';
                        input.placeholder = 'Ingrese un alias o apodo';
                        input.className = 'block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-primary-600 focus:ring-primary-600';
                        container.appendChild(input);
                    }

                    function previewImage(event) {
                        const input = event.target;
                        const preview = document.getElementById('preview-foto');
                        if (input.files && input.files[0]) {
                            const reader = new FileReader();
                            reader.onload = function(e) {
                                preview.src = e.target.result;
                                preview.classList.remove('hidden');
                            };
                            reader.readAsDataURL(input.files[0]);
                        } else {
                            preview.src = '#';
                            preview.classList.add('hidden');
                        }
                    }
                    </script>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
