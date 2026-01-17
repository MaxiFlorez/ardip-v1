<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <h2 class="font-semibold text-xl md:text-2xl text-gray-800 dark:text-gray-200 leading-tight">
                📋 Búsqueda de Procedimientos
            </h2>

            @can('operativo-escritura')
                <a href="{{ route('procedimientos.create') }}" class="inline-flex items-center px-4 py-2 bg-primary-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-primary-700 active:bg-primary-900 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    ➕ Nuevo Procedimiento
                </a>
            @endcan
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-6">Filtrar Procedimientos</h3>

                    {{-- Formulario de filtros --}}
                    <form method="GET" action="{{ route('procedimientos.index') }}">
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">

                            <div>
                                <x-input-label for="legajo_fiscal" :value="__('Legajo Fiscal')" />
                                <x-text-input id="legajo_fiscal" class="block mt-1 w-full" type="text" name="legajo_fiscal" :value="request('legajo_fiscal')" autofocus />
                            </div>

                            <div>
                                <x-input-label for="caratula" :value="__('Carátula')" />
                                <x-text-input id="caratula" class="block mt-1 w-full" type="text" name="caratula" :value="request('caratula')" />
                            </div>

                            <div>
                                <x-input-label for="es_positivo" :value="__('Resultado')" />
                                <select name="es_positivo" id="es_positivo" class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm block mt-1 w-full">
                                    <option value="">Todos</option>
                                    <option value="1" @selected(request('es_positivo') === '1')>Positivo</option>
                                    <option value="0" @selected(request('es_positivo') === '0')>Negativo</option>
                                </select>
                            </div>

                            <div>
                                <x-input-label for="ufi_id" :value="__('UFI')" />
                                <select name="ufi_id" id="ufi_id" class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm block mt-1 w-full">
                                    <option value="">Todas</option>
                                    @foreach($ufis as $ufi)
                                        <option value="{{ $ufi->id }}" @selected(request('ufi_id') == $ufi->id)>{{ $ufi->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <x-input-label for="brigada_id" :value="__('Brigada')" />
                                <select name="brigada_id" id="brigada_id" class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm block mt-1 w-full">
                                    <option value="">Todas</option>
                                    @foreach($brigadas as $brigada)
                                        <option value="{{ $brigada->id }}" @selected(request('brigada_id') == $brigada->id)>{{ $brigada->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <x-input-label for="fecha_desde" :value="__('Fecha Desde')" />
                                <x-text-input id="fecha_desde" class="block mt-1 w-full" type="date" name="fecha_desde" :value="request('fecha_desde')" />
                            </div>

                            <div>
                                <x-input-label for="fecha_hasta" :value="__('Fecha Hasta')" />
                                <x-text-input id="fecha_hasta" class="block mt-1 w-full" type="date" name="fecha_hasta" :value="request('fecha_hasta')" />
                            </div>
                        </div>

                        <div class="flex items-center gap-2 flex-wrap mt-6">
                            <button type="submit" class="px-4 py-2 bg-primary-600 text-white rounded-md hover:bg-primary-700 transition duration-150 text-sm font-medium">
                                🔍 Buscar
                            </button>
                            <a href="{{ route('procedimientos.index') }}" class="px-4 py-2 text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 transition duration-150 text-sm">
                                Limpiar
                            </a>
                        </div>
                    </form>

                    <div class="mt-6 border-t border-gray-200 dark:border-gray-700"></div>

                    <div class="mt-8">
                        @if (is_null($procedimientos))
                            <div class="text-center py-10 px-4">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                    <path vector-effect="non-scaling-stroke" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-200">Sin búsqueda activa</h3>
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Utilice los filtros de arriba para buscar expedientes.</p>
                            </div>
                        @elseif ($procedimientos->isEmpty())
                             <div class="text-center py-10 px-4">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-200">No se encontraron resultados</h3>
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Pruebe con otros criterios de búsqueda.</p>
                            </div>
                        @else
                            <x-tabla :headers="['Fecha', 'UFI', 'Brigada', 'Legajo', 'Carátula', 'Resultado', 'Acciones']">
                                @foreach ($procedimientos as $procedimiento)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition duration-150">
                                    <td class="py-4 px-6 font-medium text-gray-900 whitespace-nowrap dark:text-gray-100">
                                        {{ $procedimiento->fecha_procedimiento->format('d/m/Y') }}
                                    </td>
                                    <td class="py-4 px-6 text-gray-900 dark:text-gray-100">{{ $procedimiento->ufi->nombre ?? 'N/A' }}</td>
                                    <td class="py-4 px-6 text-gray-900 dark:text-gray-100">{{ $procedimiento->brigada->nombre ?? 'N/A' }}</td>
                                    <td class="py-4 px-6 text-gray-900 dark:text-gray-100">{{ $procedimiento->legajo_fiscal }}</td>
                                    <td class="py-4 px-6 text-gray-900 dark:text-gray-100">{{ Str::limit($procedimiento->caratula, 45) }}</td>
                                    <td class="py-4 px-6 text-center">
                                        @if ($procedimiento->es_positivo)
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-success-100 text-success-800 dark:bg-success-900 dark:text-success-200">
                                                Positivo
                                            </span>
                                        @else
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-danger-100 text-danger-800 dark:bg-danger-900 dark:text-danger-200">
                                                Negativo
                                            </span>
                                        @endif
                                    </td>
                                    <td class="py-4 px-6 text-right">
                                        <div class="flex justify-end items-center space-x-3">
                                            <a href="{{ route('procedimientos.show', $procedimiento) }}" class="text-primary-600 hover:text-primary-900 dark:text-primary-400 dark:hover:text-primary-300 text-xl" title="Ver Detalles">
                                                👁️
                                            </a>

                                            @can('operativo-escritura')
                                                <a href="{{ route('procedimientos.edit', $procedimiento) }}" class="text-warning-600 hover:text-warning-900 dark:text-warning-400 dark:hover:text-warning-300 text-xl" title="Editar">
                                                    ✏️
                                                </a>

                                                <form method="POST" action="{{ route('procedimientos.destroy', $procedimiento) }}" class="inline" onsubmit="return confirm('¿Confirma que desea eliminar este procedimiento? Esta acción no se puede deshacer.');">
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

                            @if ($procedimientos->hasPages())
                                <div class="mt-6">
                                    {{ $procedimientos->links() }}
                                </div>
                            @endif
                        @endif
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
