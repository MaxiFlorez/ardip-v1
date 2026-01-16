<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="javascript:window.history.back()" class="text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100">
                ← Volver
            </a>
            <h2 class="font-semibold text-xl md:text-2xl text-gray-800 dark:text-gray-200 leading-tight">
                📍 Editar Domicilio
            </h2>
        </div>
    </x-slot>

    <!-- Estilos de Leaflet -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
        crossorigin=""/>
    
    <style>
        #map {
            height: 400px;
            width: 100%;
            border-radius: 0.5rem;
            z-index: 1;
        }
        .leaflet-container {
            font-family: inherit;
        }
    </style>

    <div class="py-6 md:py-12">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
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

                        <!-- Sección: Mapa Interactivo -->
                        <div class="border-b border-gray-200 dark:border-gray-700 pb-6">
                            <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-gray-100 mb-2">
                                🗺️ Ubicar en el Mapa
                            </h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                                Haz clic en el mapa para marcar la ubicación. Las coordenadas se guardarán automáticamente.
                            </p>
                            <div id="map" class="shadow-md"></div>
                            
                            <div class="grid grid-cols-2 gap-4 mt-4">
                                <div>
                                    <label for="latitud" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Latitud</label>
                                    <input type="text" name="latitud" id="latitud" value="{{ old('latitud', $domicilio->latitud) }}" readonly
                                           class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm bg-gray-50">
                                    @error('latitud')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="longitud" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Longitud</label>
                                    <input type="text" name="longitud" id="longitud" value="{{ old('longitud', $domicilio->longitud) }}" readonly
                                           class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm bg-gray-50">
                                    @error('longitud')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Sección: Dirección Textual -->
                        <div class="border-b border-gray-200 dark:border-gray-700 pb-6">
                            <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-gray-100 mb-2">
                                📝 Dirección (Opcional)
                            </h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                                Complete los campos que conozca. No es necesario llenar todos.
                            </p>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="calle" class="block font-medium text-sm text-gray-700 dark:text-gray-300 mb-2">Calle</label>
                                    <input type="text" name="calle" id="calle" value="{{ old('calle', $domicilio->calle) }}"
                                           class="w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-primary-500 dark:focus:border-primary-600 focus:ring-primary-500 dark:focus:ring-primary-600">
                                </div>
                                <div>
                                    <label for="altura" class="block font-medium text-sm text-gray-700 dark:text-gray-300 mb-2">Altura/Número</label>
                                    <input type="text" name="altura" id="altura" value="{{ old('altura', $domicilio->altura) }}"
                                           class="w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-primary-500 dark:focus:border-primary-600 focus:ring-primary-500 dark:focus:ring-primary-600">
                                </div>
                                <div>
                                    <label for="barrio" class="block font-medium text-sm text-gray-700 dark:text-gray-300 mb-2">Barrio</label>
                                    <input type="text" name="barrio" id="barrio" value="{{ old('barrio', $domicilio->barrio) }}"
                                           class="w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-primary-500 dark:focus:border-primary-600 focus:ring-primary-500 dark:focus:ring-primary-600">
                                </div>
                                <div>
                                    <label for="localidad" class="block font-medium text-sm text-gray-700 dark:text-gray-300 mb-2">Localidad</label>
                                    <input type="text" name="localidad" id="localidad" value="{{ old('localidad', $domicilio->localidad) }}"
                                           class="w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-primary-500 dark:focus:border-primary-600 focus:ring-primary-500 dark:focus:ring-primary-600">
                                </div>
                                <div>
                                    <label for="provincia" class="block font-medium text-sm text-gray-700 dark:text-gray-300 mb-2">Provincia</label>
                                    <input type="text" name="provincia" id="provincia" value="{{ old('provincia', $domicilio->provincia) }}"
                                           class="w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-primary-500 dark:focus:border-primary-600 focus:ring-primary-500 dark:focus:ring-primary-600">
                                </div>
                                <div>
                                    <label for="sector" class="block font-medium text-sm text-gray-700 dark:text-gray-300 mb-2">Sector/Zona</label>
                                    <input type="text" name="sector" id="sector" value="{{ old('sector', $domicilio->sector) }}"
                                           class="w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-primary-500 dark:focus:border-primary-600 focus:ring-primary-500 dark:focus:ring-primary-600">
                                </div>
                            </div>
                        </div>

                        <!-- Sección: Detalles Adicionales -->
                        <div class="border-b border-gray-200 dark:border-gray-700 pb-6">
                            <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-gray-100 mb-2">
                                🏢 Detalles Adicionales (Opcional)
                            </h3>
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
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
                                <div>
                                    <label for="torre" class="block font-medium text-sm text-gray-700 dark:text-gray-300 mb-2">Torre</label>
                                    <input type="text" name="torre" id="torre" value="{{ old('torre', $domicilio->torre) }}"
                                           class="w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-primary-500 dark:focus:border-primary-600 focus:ring-primary-500 dark:focus:ring-primary-600">
                                </div>
                                <div>
                                    <label for="monoblock" class="block font-medium text-sm text-gray-700 dark:text-gray-300 mb-2">Monoblock</label>
                                    <input type="text" name="monoblock" id="monoblock" value="{{ old('monoblock', $domicilio->monoblock) }}"
                                           class="w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-primary-500 dark:focus:border-primary-600 focus:ring-primary-500 dark:focus:ring-primary-600">
                                </div>
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
                        </div>

                        <div class="flex justify-end space-x-3 pt-6">
                            <a href="{{ route('domicilios.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 dark:bg-gray-700 text-white text-sm font-semibold rounded-md hover:bg-gray-700 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition duration-150">
                                Cancelar
                            </a>
                            <x-primary-button type="submit">
                                💾 Actualizar Domicilio
                            </x-primary-button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript de Leaflet -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
        crossorigin=""></script>
    
    <script>
        // Inicializar mapa centrado en San Juan, Argentina
        const map = L.map('map').setView([-31.5375, -68.5364], 13);

        // Añadir capa de OpenStreetMap
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
            maxZoom: 19
        }).addTo(map);

        // Variable para el marcador
        let marker = null;

        // Función para actualizar coordenadas
        function updateCoordinates(lat, lng) {
            document.getElementById('latitud').value = lat.toFixed(7);
            document.getElementById('longitud').value = lng.toFixed(7);
        }

        // Evento: clic en el mapa
        map.on('click', function(e) {
            const { lat, lng } = e.latlng;
            
            // Si ya existe un marcador, moverlo
            if (marker) {
                marker.setLatLng([lat, lng]);
            } else {
                // Crear nuevo marcador draggable
                marker = L.marker([lat, lng], { draggable: true }).addTo(map);
                
                // Evento: cuando se mueve el marcador
                marker.on('dragend', function(event) {
                    const position = marker.getLatLng();
                    updateCoordinates(position.lat, position.lng);
                });
            }
            
            // Actualizar inputs
            updateCoordinates(lat, lng);
        });

        // Cargar coordenadas existentes del domicilio
        const existingLat = {{ $domicilio->latitud ?? 'null' }};
        const existingLng = {{ $domicilio->longitud ?? 'null' }};
        
        if (existingLat !== null && existingLng !== null) {
            marker = L.marker([existingLat, existingLng], { draggable: true }).addTo(map);
            map.setView([existingLat, existingLng], 15);
            
            marker.on('dragend', function(event) {
                const position = marker.getLatLng();
                updateCoordinates(position.lat, position.lng);
            });
        }

        // Si hay coordenadas previas en old() tras error de validación
        const oldLat = "{{ old('latitud') }}";
        const oldLng = "{{ old('longitud') }}";
        if (oldLat && oldLng && marker === null) {
            const lat = parseFloat(oldLat);
            const lng = parseFloat(oldLng);
            if (!isNaN(lat) && !isNaN(lng)) {
                marker = L.marker([lat, lng], { draggable: true }).addTo(map);
                map.setView([lat, lng], 15);
                
                marker.on('dragend', function(event) {
                    const position = marker.getLatLng();
                    updateCoordinates(position.lat, position.lng);
                });
            }
        }
    </script>
</x-app-layout>