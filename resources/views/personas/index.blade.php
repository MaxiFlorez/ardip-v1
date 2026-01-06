<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4">
            <h2 class="font-semibold text-xl md:text-2xl text-gray-800 leading-tight">
                {{ __('Listado de Personas') }}
            </h2>
            @can('panel-carga')
                <a href="{{ route('personas.create') }}" class="bg-blue-500 hover:bg-blue-700 active:bg-blue-800 text-white font-bold py-2 px-4 rounded text-sm md:text-base transition duration-200 inline-block">
                    ➕ Agregar Persona
                </a>
            @endcan
        </div>
    </x-slot>

    <div class="py-6 md:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Formulario de búsqueda responsive -->
            <form method="GET" action="{{ route('personas.index') }}" class="mb-6">
                <div class="bg-white rounded-lg shadow-sm p-4 md:p-6 space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-3 md:gap-4">
                        <!-- Búsqueda general -->
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Búsqueda</label>
                            <input type="text" name="buscar"
                                   placeholder="Nombre, apellido o alias..."
                                   value="{{ request('buscar') }}"
                                   class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:ring-indigo-500 focus:border-indigo-500 transition duration-150">
                        </div>

                        <!-- Zona / Departamento -->
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Zona</label>
                            <select name="departamento" class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:ring-indigo-500 focus:border-indigo-500 transition duration-150">
                                <option value="">-- Todas --</option>
                                <option value="Capital" {{ request('departamento')==='Capital' ? 'selected' : '' }}>Capital</option>
                                <option value="Chimbas" {{ request('departamento')==='Chimbas' ? 'selected' : '' }}>Chimbas</option>
                                <option value="Rawson" {{ request('departamento')==='Rawson' ? 'selected' : '' }}>Rawson</option>
                            </select>
                        </div>

                        <!-- Edad mínima -->
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Edad Mín.</label>
                            <input type="number" name="edad_min" placeholder="18"
                                   value="{{ request('edad_min') }}" class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:ring-indigo-500 focus:border-indigo-500 transition duration-150">
                        </div>
                        
                        <!-- Edad máxima -->
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Edad Máx.</label>
                            <input type="number" name="edad_max" placeholder="100"
                                   value="{{ request('edad_max') }}" class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:ring-indigo-500 focus:border-indigo-500 transition duration-150">
                        </div>
                    </div>

                    <div class="flex flex-col md:flex-row gap-2">
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white font-semibold px-4 md:px-6 py-2 rounded text-sm transition duration-200 flex items-center justify-center gap-2">
                            🔍 Buscar
                        </button>
                        <a href="{{ route('personas.index') }}" class="text-center md:text-left text-gray-600 hover:text-gray-900 text-sm font-medium transition duration-150 px-4 py-2 border border-gray-300 rounded md:border-0 md:p-0">
                            Limpiar filtros
                        </a>
                    </div>
                </div>
            </form>
            
            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4 text-sm">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-4 md:p-6 text-gray-900">
                    @if($personas->count() > 0)
                        <div class="space-y-3 md:space-y-4">
                            @foreach($personas as $persona)
                                <div class="bg-gray-50 hover:bg-gray-100 transition duration-150 p-4 md:p-5 rounded-lg border border-gray-200 flex flex-col md:flex-row md:items-center gap-4">
                                    
                                    <!-- Foto miniatura -->
                                    <div class="flex-shrink-0">
                                        @if($persona->foto)
                                            <img src="{{ asset('storage/' . $persona->foto) }}" 
                                                alt="{{ $persona->nombre_completo }}" 
                                                class="w-14 h-14 md:w-16 md:h-16 rounded-full object-cover border-2 border-gray-400">
                                        @else
                                            <div class="w-14 h-14 md:w-16 md:h-16 rounded-full bg-gradient-to-br from-gray-300 to-gray-400 flex items-center justify-center text-xl md:text-2xl">
                                                👤
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Datos principales -->
                                    <div class="flex-grow min-w-0">
                                        <h3 class="text-base md:text-lg font-bold text-gray-900 truncate">{{ $persona->nombre_completo }}</h3>
                                        <div class="grid grid-cols-2 md:grid-cols-3 gap-2 mt-2 text-xs md:text-sm">
                                            <p class="text-gray-600"><span class="font-semibold">DNI:</span> {{ $persona->dni }}</p>
                                            <p class="text-gray-600"><span class="font-semibold">Edad:</span> {{ $persona->edad }} años</p>
                                            
                                            @php($dom = $persona->domicilioHabitual())
                                            @if($dom)
                                                <p class="text-gray-600"><span class="font-semibold">📍</span> {{ $dom->departamento }}</p>
                                            @endif
                                        </div>

                                        @if($persona->aliases->isNotEmpty())
                                            <p class="text-xs text-gray-500 italic mt-2 line-clamp-1">
                                                <span class="font-semibold">Alias:</span> {{ $persona->aliases->pluck('alias')->join(', ') }}
                                            </p>
                                        @endif
                                    </div>

                                    <!-- Acciones -->
                                    <div class="flex-shrink-0 w-full md:w-auto">
                                        <a href="{{ route('personas.show', $persona) }}" 
                                        class="w-full md:w-auto bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white px-4 md:px-6 py-2 rounded text-sm font-medium transition duration-200 inline-block text-center">
                                            Ver Detalle →
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-12">
                            <p class="text-gray-500 text-base">No hay personas registradas.</p>
                            @can('panel-carga')
                                <a href="{{ route('personas.create') }}" class="mt-4 inline-block bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded text-sm transition duration-200">
                                    ➕ Crear Primera Persona
                                </a>
                            @endcan
                        </div>
                    @endif
                </div>
            </div>
            
            <!-- Paginación -->
            @if($personas->hasPages())
                <div class="mt-6">
                    {{ $personas->withQueryString()->links() }}
                </div>
            @endif
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