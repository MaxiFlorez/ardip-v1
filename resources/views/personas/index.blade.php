<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <h2 class="font-semibold text-xl md:text-2xl text-gray-800 dark:text-gray-200 leading-tight">
                👤 Gestión de Personas
            </h2>

            @can('operativo-escritura')
                <a href="{{ route('personas.create') }}" class="inline-flex items-center px-4 py-2 bg-primary-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-primary-700 active:bg-primary-900 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    ➕ Nueva Persona
                </a>
            @endcan
        </div>
    </x-slot>

    <div class="py-6 md:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <form method="GET" action="{{ route('personas.index') }}" class="mb-6">
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-4 md:p-6 space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-3 md:gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Búsqueda</label>
                            <input type="text" name="buscar" placeholder="Nombre, apellido o alias..." value="{{ request('buscar') }}" class="w-full border border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 rounded px-3 py-2 text-sm focus:ring-indigo-500 focus:border-indigo-500 transition duration-150">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Zona</label>
                            <select name="departamento" class="w-full border border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 rounded px-3 py-2 text-sm focus:ring-indigo-500 focus:border-indigo-500 transition duration-150">
                                <option value="">-- Todas --</option>
                                <option value="Capital" {{ request('departamento')==='Capital' ? 'selected' : '' }}>Capital</option>
                                <option value="Chimbas" {{ request('departamento')==='Chimbas' ? 'selected' : '' }}>Chimbas</option>
                                <option value="Rawson" {{ request('departamento')==='Rawson' ? 'selected' : '' }}>Rawson</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Edad Mín.</label>
                            <input type="number" name="edad_min" placeholder="18" value="{{ request('edad_min') }}" class="w-full border border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 rounded px-3 py-2 text-sm focus:ring-indigo-500 focus:border-indigo-500 transition duration-150">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Edad Máx.</label>
                            <input type="number" name="edad_max" placeholder="100" value="{{ request('edad_max') }}" class="w-full border border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 rounded px-3 py-2 text-sm focus:ring-indigo-500 focus:border-indigo-500 transition duration-150">
                        </div>
                    </div>
                    <div class="flex flex-col md:flex-row gap-2">
                        <button type="submit" class="px-4 py-2 bg-primary-600 text-white rounded-md hover:bg-primary-700 transition duration-150 text-sm font-medium">
                            🔍 Buscar
                        </button>
                        <a href="{{ route('personas.index') }}" class="px-4 py-2 text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 transition duration-150 text-sm text-center">
                            Limpiar
                        </a>
                    </div>
                </div>
            </form>

            @if (session('success'))
                <div class="bg-success-50 border-l-4 border-success-500 p-4 rounded-md dark:bg-success-900/20 dark:border-success-700 mb-6" role="alert">
                    <p class="text-success-700 dark:text-success-300 text-sm md:text-base">✅ {{ session('success') }}</p>
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-4 md:p-6 text-gray-900 dark:text-gray-100">
                    @if($personas->count() > 0)
                        <x-tabla :headers="['Foto', 'Nombre Completo', 'DNI', 'Alias', 'Edad / Nac.', 'Acciones']">
                            @foreach($personas as $persona)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition duration-150">
                                    <td class="px-4 py-3">
                                        @if($persona->foto)
                                            <img src="{{ asset('storage/' . $persona->foto) }}" alt="{{ $persona->nombre_completo }}" class="w-12 h-12 rounded-full object-cover border border-gray-200 dark:border-gray-600">
                                        @else
                                            <div class="w-12 h-12 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center text-lg text-gray-500 dark:text-gray-400">👤</div>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 font-semibold text-gray-900 dark:text-gray-100">{{ $persona->apellido }}, {{ $persona->nombre }}</td>
                                    <td class="px-4 py-3 text-gray-900 dark:text-gray-100">{{ $persona->dni }}</td>
                                    <td class="px-4 py-3 text-gray-900 dark:text-gray-100">{{ $persona->aliases->pluck('alias')->join(', ') ?: '—' }}</td>
                                    <td class="px-4 py-3">
                                        @if(!empty($persona->fecha_nacimiento))
                                            <div class="text-gray-900 dark:text-gray-100">{{ $persona->edad }} años</div>
                                            <div class="text-xs text-gray-500 dark:text-gray-400">{{ 
                                                optional($persona->fecha_nacimiento)->format('d/m/Y') }}</div>
                                        @else
                                            <span class="text-gray-500 dark:text-gray-400">—</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-right">
                                        <div class="flex justify-end items-center space-x-3">
                                            <a href="{{ route('personas.show', $persona) }}" class="text-primary-600 hover:text-primary-900 dark:text-primary-400 dark:hover:text-primary-300 text-xl" title="Ver Detalles">
                                                👁️
                                            </a>
                                            @can('operativo-escritura')
                                                <a href="{{ route('personas.edit', $persona) }}" class="text-warning-600 hover:text-warning-900 dark:text-warning-400 dark:hover:text-warning-300 text-xl" title="Editar">
                                                    ✏️
                                                </a>
                                                <form action="{{ route('personas.destroy', $persona) }}" method="POST" class="inline" onsubmit="return confirm('¿Confirma que desea eliminar esta persona?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-danger-600 hover:text-danger-900 dark:text-danger-400 dark:hover:text-danger-300 text-xl" title="Eliminar">
                                                        🗑️
                                                    </button>
                                                </form>
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </x-tabla>
                    @else
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                <path vector-effect="non-scaling-stroke" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            <p class="mt-2 text-base font-medium text-gray-900 dark:text-gray-100">No se encontraron personas.</p>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Ajusta los filtros o carga un nuevo registro.</p>
                            @can('operativo-escritura')
                                <a href="{{ route('personas.create') }}">
                                    <x-primary-button class="mt-4">
                                        <svg class="w-4 h-4 mr-2 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                        </svg>
                                        Crear Persona
                                    </x-primary-button>
                                </a>
                            @endcan
                        </div>
                    @endif
                </div>
            </div>

            @if($personas->hasPages())
                <div class="mt-6">
                    {{ $personas->withQueryString()->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
