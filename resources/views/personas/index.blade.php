<x-app-layout>
    <div class="py-6 md:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <x-section-header title="Gestión de Personas">
                <x-slot name="actions">
                    @can('operativo-escritura')
                        <a href="{{ route('personas.create') }}">
                            <x-primary-button>
                                <svg class="w-4 h-4 mr-2 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                Nueva Persona
                            </x-primary-button>
                        </a>
                    @endcan
                </x-slot>
            </x-section-header>

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
                        <x-primary-button type="submit">
                            <svg class="w-4 h-4 mr-2 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            Buscar
                        </x-primary-button>
                        <a href="{{ route('personas.index') }}">
                            <x-secondary-button type="button">Limpiar filtros</x-secondary-button>
                        </a>
                    </div>
                </div>
            </form>

            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4 text-sm">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-4 md:p-6 text-gray-900 dark:text-gray-100">
                    @if($personas->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full table-auto text-sm text-left text-gray-600 dark:text-gray-300">
                                <thead class="text-xs uppercase bg-gray-50 dark:bg-gray-700 text-gray-700 dark:text-gray-300">
                                    <tr>
                                        <th class="px-4 py-3">Foto</th>
                                        <th class="px-4 py-3">Nombre Completo</th>
                                        <th class="px-4 py-3">DNI</th>
                                        <th class="px-4 py-3">Alias</th>
                                        <th class="px-4 py-3">Edad / Nac.</th>
                                        <th class="px-4 py-3 text-right">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach($personas as $persona)
                                        <tr class="bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700/60 transition">
                                            <td class="px-4 py-3">
                                                @if($persona->foto)
                                                    <img src="{{ asset('storage/' . $persona->foto) }}" alt="{{ $persona->nombre_completo }}" class="w-12 h-12 rounded-full object-cover border border-gray-200 dark:border-gray-600">
                                                @else
                                                    <div class="w-12 h-12 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center text-lg text-gray-500">👤</div>
                                                @endif
                                            </td>
                                            <td class="px-4 py-3 font-semibold text-gray-900 dark:text-gray-100">{{ $persona->apellido }}, {{ $persona->nombre }}</td>
                                            <td class="px-4 py-3">{{ $persona->dni }}</td>
                                            <td class="px-4 py-3">{{ $persona->aliases->pluck('alias')->join(', ') ?: '—' }}</td>
                                            <td class="px-4 py-3">
                                                @if(!empty($persona->fecha_nacimiento))
                                                    <div class="text-gray-900 dark:text-gray-100">{{ $persona->edad }} años</div>
                                                    <div class="text-xs text-gray-500 dark:text-gray-400">{{ 
                                                        optional($persona->fecha_nacimiento)->format('d/m/Y') }}</div>
                                                @else
                                                    <span class="text-gray-500">—</span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-3 text-right">
                                                <div class="flex justify-end items-center space-x-2">
                                                    <a href="{{ route('personas.show', $persona) }}" class="inline-flex items-center px-3 py-2 text-sm font-semibold text-blue-700 bg-blue-50 border border-blue-200 rounded-lg hover:bg-blue-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:text-blue-100 dark:bg-blue-900/50 dark:border-blue-700">
                                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                        </svg>
                                                        Ver
                                                    </a>
                                                    @can('operativo-escritura')
                                                        <a href="{{ route('personas.edit', $persona) }}" class="inline-flex items-center px-3 py-2 text-sm font-semibold text-amber-800 bg-amber-50 border border-amber-200 rounded-lg hover:bg-amber-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500 dark:text-amber-100 dark:bg-amber-900/40 dark:border-amber-700">
                                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5h2m2 0h2m-6 4h6m-6 4h6m-8 4h8m-8 0H7a2 2 0 01-2-2V7a2 2 0 012-2h4m-2 6h2" />
                                                            </svg>
                                                            Editar
                                                        </a>
                                                        <form action="{{ route('personas.destroy', $persona) }}" method="POST" onsubmit="return confirm('¿Confirma que desea eliminar esta persona?');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="inline-flex items-center px-3 py-2 text-sm font-semibold text-red-700 bg-red-50 border border-red-200 rounded-lg hover:bg-red-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 dark:text-red-100 dark:bg-red-900/50 dark:border-red-800">
                                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m10 0H5" />
                                                                </svg>
                                                                Eliminar
                                                            </button>
                                                        </form>
                                                    @endcan
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
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
