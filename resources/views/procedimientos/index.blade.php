<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl md:text-2xl text-gray-900 dark:text-gray-200 leading-tight">
                {{ __('Búsqueda de Procedimientos') }}
            </h2>

            @can('operativo-escritura')
                <a href="{{ route('procedimientos.create') }}">
                    <x-primary-button>
                        <svg class="w-4 h-4 mr-2 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        {{ __('Nuevo Procedimiento') }}
                    </x-primary-button>
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

                        <div class="flex items-center justify-end mt-6 space-x-4">
                            <a href="{{ route('procedimientos.index') }}">
                                <x-secondary-button type="button">
                                    Limpiar
                                </x-secondary-button>
                            </a>
                            <x-primary-button>
                                {{ __('Buscar') }}
                            </x-primary-button>
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
                            <div class="overflow-x-auto relative shadow-md sm:rounded-lg">
                                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                        <tr>
                                            <th scope="col" class="py-3 px-6">Fecha</th>
                                            <th scope="col" class="py-3 px-6">UFI</th>
                                            <th scope="col" class="py-3 px-6">Brigada</th>
                                            <th scope="col" class="py-3 px-6">Legajo</th>
                                            <th scope="col" class="py-3 px-6">Carátula</th>
                                            <th scope="col" class="py-3 px-6 text-center">Resultado</th>
                                            <th scope="col" class="py-3 px-6 text-right">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($procedimientos as $procedimiento)
                                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                            <td class="py-4 px-6 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                                {{ $procedimiento->fecha_procedimiento->format('d/m/Y') }}
                                            </td>
                                            <td class="py-4 px-6">{{ $procedimiento->ufi->nombre ?? 'N/A' }}</td>
                                            <td class="py-4 px-6">{{ $procedimiento->brigada->nombre ?? 'N/A' }}</td>
                                            <td class="py-4 px-6">{{ $procedimiento->legajo_fiscal }}</td>
                                            <td class="py-4 px-6">{{ Str::limit($procedimiento->caratula, 45) }}</td>
                                            <td class="py-4 px-6 text-center">
                                                @if ($procedimiento->es_positivo)
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                                        Positivo
                                                    </span>
                                                @else
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                                        Negativo
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="py-4 px-6 text-right">
                                                <div class="flex justify-end items-center space-x-2">
                                                    <a href="{{ route('procedimientos.show', $procedimiento) }}" class="inline-flex items-center px-3 py-2 text-sm font-semibold text-blue-700 bg-blue-50 border border-blue-200 rounded-lg hover:bg-blue-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:text-blue-100 dark:bg-blue-900/50 dark:border-blue-700">
                                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                        </svg>
                                                        Ver
                                                    </a>

                                                    @can('operativo-escritura')
                                                        <a href="{{ route('procedimientos.edit', $procedimiento) }}" class="inline-flex items-center px-3 py-2 text-sm font-semibold text-amber-800 bg-amber-50 border border-amber-200 rounded-lg hover:bg-amber-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500 dark:text-amber-100 dark:bg-amber-900/40 dark:border-amber-700">
                                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5h2m2 0h2m-6 4h6m-6 4h6m-8 4h8m-8 0H7a2 2 0 01-2-2V7a2 2 0 012-2h4m-2 6h2" />
                                                            </svg>
                                                            Editar
                                                        </a>

                                                        <form method="POST" action="{{ route('procedimientos.destroy', $procedimiento) }}" onsubmit="return confirm('¿Confirma que desea eliminar este procedimiento? Esta acción no se puede deshacer.');">
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
