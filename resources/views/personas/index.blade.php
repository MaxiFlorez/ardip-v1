<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Listado de Personas') }}
            </h2>
            <a href="{{ route('personas.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Agregar Persona
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Formulario de búsqueda -->
            <form method="GET" action="{{ route('personas.index') }}" class="mb-6 space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <!-- Búsqueda general -->
                    <input type="text" name="buscar"
                           placeholder="Nombre, apellido o alias..."
                           value="{{ request('buscar') }}"
                           class="border rounded px-3 py-2">

                    <!-- Zona / Departamento -->
                    <select name="departamento" class="border rounded px-3 py-2">
                        <option value="">-- Zona --</option>
                        <option value="Capital" {{ request('departamento')==='Capital' ? 'selected' : '' }}>Capital</option>
                        <option value="Chimbas" {{ request('departamento')==='Chimbas' ? 'selected' : '' }}>Chimbas</option>
                        <option value="Rawson" {{ request('departamento')==='Rawson' ? 'selected' : '' }}>Rawson</option>
                        <!-- más opciones -->
                    </select>

                    <!-- Edad mínima -->
                    <input type="number" name="edad_min" placeholder="Edad mín"
                           value="{{ request('edad_min') }}" class="border rounded px-3 py-2">
                    <!-- Edad máxima -->
                    <input type="number" name="edad_max" placeholder="Edad máx"
                           value="{{ request('edad_max') }}" class="border rounded px-3 py-2">
                </div>

                <div class="flex items-center gap-2">
                    <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded">
                        🔍 Buscar
                    </button>
                    <a href="{{ route('personas.index') }}" class="ml-2 text-gray-600">Limpiar</a>
                </div>
            </form>
            
            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if($personas->count() > 0)
                        <div class="grid grid-cols-1 gap-4">
                            @foreach($personas as $persona)
                                <div class="bg-white p-4 rounded-lg shadow flex items-center space-x-4 hover:bg-gray-50">
                                    <!-- Foto miniatura -->
                                    <div class="flex-shrink-0">
                                        @if($persona->foto)
                                            <img src="{{ asset('storage/' . $persona->foto) }}" 
                                                alt="{{ $persona->nombre_completo }}" 
                                                class="w-16 h-16 rounded-full object-cover border-2 border-gray-300">
                                        @else
                                            <div class="w-16 h-16 rounded-full bg-gray-300 flex items-center justify-center">
                                                <span class="text-2xl text-gray-600">👤</span>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Datos principales -->
                                    <div class="flex-grow">
                                        <h3 class="text-lg font-bold">{{ $persona->nombre_completo }}</h3>
                                        <p class="text-sm text-gray-600">DNI: {{ $persona->dni }} • Edad: {{ $persona->edad }} años</p>

                                        @if($persona->alias->isNotEmpty())
                                            <p class="text-xs text-gray-500 italic">
                                                Alias: {{ $persona->alias->pluck('alias')->join(', ') }}
                                            </p>
                                        @endif

                                        @php($dom = $persona->domicilioHabitual())
                                        @if($dom)
                                            <p class="text-xs text-gray-500">
                                                📍 {{ $dom->departamento }}
                                            </p>
                                        @endif
                                    </div>

                                    <!-- Acciones -->
                                    <div class="flex-shrink-0">
                                        <a href="{{ route('personas.show', $persona) }}" 
                                        class="bg-blue-600 text-white px-4 py-2 rounded text-sm">
                                            Ver Detalle
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500">No hay personas registradas.</p>
                    @endif
                </div>
            </div>
            <!-- Paginación -->
            <div class="mt-6">
                {{ $personas->withQueryString()->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
```

---

## ✅ Guarda el archivo

Después de crear este archivo:

1. Guarda el archivo como `index.blade.php`
2. Verifica que esté en `resources/views/personas/`

---

## 🧪 Probar la vista

Abre tu navegador y ve a:
```
http://localhost:8000/personas