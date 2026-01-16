<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            📍 {{ __('Agregar Nuevo Domicilio') }}
        </h2>
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

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <form action="{{ route('domicilios.store') }}" method="POST">
                        @csrf

                        <!-- Sección: Mapa Interactivo -->
                        <div class="border-b border-gray-200 dark:border-gray-700 pb-6 mb-6">
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
                                    <input type="text" name="latitud" id="latitud" value="{{ old('latitud') }}" readonly
                                           class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm bg-gray-50">
                                    @error('latitud')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="longitud" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Longitud</label>
                                    <input type="text" name="longitud" id="longitud" value="{{ old('longitud') }}" readonly
                                           class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm bg-gray-50">
                                    @error('longitud')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Sección: Dirección Textual -->
                        <div class="border-b border-gray-200 dark:border-gray-700 pb-6 mb-6">
                            <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-gray-100 mb-2">
                                📝 Dirección (Opcional)
                            </h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                                Complete los campos que conozca. No es necesario llenar todos.
                            </p>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="calle" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Calle</label>
                                    <input type="text" name="calle" id="calle" value="{{ old('calle') }}"
                                           class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm">
                                </div>
                                <div>
                                    <label for="altura" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Altura/Número</label>
                                    <input type="text" name="altura" id="altura" value="{{ old('altura') }}"
                                           class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm">
                                </div>
                                <div>
                                    <label for="barrio" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Barrio</label>
                                    <input type="text" name="barrio" id="barrio" value="{{ old('barrio') }}"
                                           class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm">
                                </div>
                                <div>
                                    <label for="localidad" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Localidad</label>
                                    <input type="text" name="localidad" id="localidad" value="{{ old('localidad') }}"
                                           class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm">
                                </div>
                                <div>
                                    <label for="provincia" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Provincia</label>
                                    <input type="text" name="provincia" id="provincia" value="{{ old('provincia', 'San Juan') }}"
                                           class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm">
                                </div>
                                <div>
                                    <label for="sector" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Sector/Zona</label>
                                    <input type="text" name="sector" id="sector" value="{{ old('sector') }}"
                                           class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm">
                                </div>
                            </div>
                        </div>

                        <!-- Sección: Detalles Adicionales -->
                        <div class="mb-6">
                            <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-gray-100 mb-2">
                                🏢 Detalles Adicionales (Opcional)
                            </h3>
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                                <div>
                                    <label for="piso" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Piso</label>
                                    <input type="text" name="piso" id="piso" value="{{ old('piso') }}"
                                           class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm">
                                </div>
                                <div>
                                    <label for="depto" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Depto</label>
                                    <input type="text" name="depto" id="depto" value="{{ old('depto') }}"
                                           class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm">
                                </div>
                                <div>
                                    <label for="torre" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Torre</label>
                                    <input type="text" name="torre" id="torre" value="{{ old('torre') }}"
                                           class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm">
                                </div>
                                <div>
                                    <label for="monoblock" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Monoblock</label>
                                    <input type="text" name="monoblock" id="monoblock" value="{{ old('monoblock') }}"
                                           class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm">
                                </div>
                                <div>
                                    <label for="manzana" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Manzana</label>
                                    <input type="text" name="manzana" id="manzana" value="{{ old('manzana') }}"
                                           class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm">
                                </div>
                                <div>
                                    <label for="lote" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Lote</label>
                                    <input type="text" name="lote" id="lote" value="{{ old('lote') }}"
                                           class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm">
                                </div>
                                <div>
                                    <label for="casa" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Casa N°</label>
                                    <input type="text" name="casa" id="casa" value="{{ old('casa') }}"
                                           class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm">
                                </div>
                            </div>
                        </div>

                        <!-- Botones -->
                        <div class="flex justify-end space-x-3 mt-8 pt-6 border-t border-gray-200 dark:border-gray-700">
                            <a href="{{ route('domicilios.index') }}">
                                <x-secondary-button type="button">
                                    Cancelar
                                </x-secondary-button>
                            </a>
                            <x-primary-button type="submit">
                                💾 Guardar Domicilio
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

        // Si hay coordenadas previas (old values), cargar marcador
        const oldLat = "{{ old('latitud') }}";
        const oldLng = "{{ old('longitud') }}";
        if (oldLat && oldLng) {
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