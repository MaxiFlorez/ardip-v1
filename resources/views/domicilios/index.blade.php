<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <h2 class="font-semibold text-xl md:text-2xl text-gray-800 dark:text-gray-200 leading-tight">
                🏠 Gestión de Domicilios
            </h2>

            @can('operativo-escritura')
                <a href="{{ route('domicilios.create') }}" class="inline-flex items-center px-4 py-2 bg-primary-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-primary-700 active:bg-primary-900 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    ➕ Nuevo Domicilio
                </a>
            @endcan
        </div>
    </x-slot>

    <div class="py-6 md:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">

                {{-- Mensaje de éxito --}}
                @if (session('success'))
                    <div class="bg-success-50 border-l-4 border-success-500 p-4 mx-4 md:mx-6 mt-4 md:mt-6 rounded-md dark:bg-success-900/20 dark:border-success-700" role="alert">
                        <p class="text-success-700 dark:text-success-300 text-sm md:text-base">✅ {{ session('success') }}</p>
                    </div>
                @endif

                <div class="p-4 md:p-6 text-gray-900 dark:text-gray-100">

                    {{-- Tabla Responsive Desktop --}}
                    <div class="hidden md:block">
                        <x-tabla :headers="['Dirección', 'Barrio / Monoblock', 'Departamento', 'Acciones']">
                            @forelse ($domicilios as $domicilio)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition duration-150">
                                    <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-gray-100">
                                        {{ $domicilio->direccion_formateada }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                                        @if($domicilio->barrio)
                                            B° {{ $domicilio->barrio }}
                                        @elseif($domicilio->monoblock)
                                            {{ $domicilio->monoblock }}
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                                        {{ $domicilio->departamento }}
                                    </td>
                                    <td class="px-6 py-4 text-sm font-medium">
                                        <div class="flex justify-end items-center space-x-3">
                                            <a href="{{ route('domicilios.show', $domicilio) }}" class="text-primary-600 hover:text-primary-900 dark:text-primary-400 dark:hover:text-primary-300 text-xl" title="Ver Detalles">
                                                👁️
                                            </a>

                                            @can('operativo-escritura')
                                                <a href="{{ route('domicilios.edit', $domicilio) }}" class="text-warning-600 hover:text-warning-900 dark:text-warning-400 dark:hover:text-warning-300 text-xl" title="Editar">
                                                    ✏️
                                                </a>

                                                <form action="{{ route('domicilios.destroy', $domicilio) }}" method="POST" class="inline" onsubmit="return confirm('¿Confirma que desea eliminar este domicilio?');">
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
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400 text-center">
                                        No hay domicilios registrados.
                                    </td>
                                </tr>
                            @endforelse
                        </x-tabla>
                    </div>

                    {{-- Cards Responsive Mobile --}}
                    <div class="md:hidden space-y-3">
                        @forelse ($domicilios as $domicilio)
                            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 border border-gray-200 dark:border-gray-600 hover:border-gray-300 dark:hover:border-gray-500 transition duration-150">
                                <div class="space-y-2">
                                    <div>
                                        <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase">Dirección</p>
                                        <p class="text-sm font-bold text-gray-900 dark:text-gray-100">
                                            {{ $domicilio->direccion_formateada }}
                                        </p>
                                    </div>
                                    
                                    <div class="grid grid-cols-2 gap-3">
                                        <div>
                                            <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase">Barrio/Monoblock</p>
                                            <p class="text-sm text-gray-700 dark:text-gray-300">
                                                @if($domicilio->barrio)
                                                    B° {{ $domicilio->barrio }}
                                                @elseif($domicilio->monoblock)
                                                    {{ $domicilio->monoblock }}
                                                @else
                                                    N/A
                                                @endif
                                            </p>
                                        </div>
                                        <div>
                                            <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase">Departamento</p>
                                            <p class="text-sm text-gray-700 dark:text-gray-300">{{ $domicilio->departamento ?? 'N/A' }}</p>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-600 flex gap-2 flex-wrap">
                                    <a href="{{ route('domicilios.show', $domicilio) }}" class="flex-1 inline-flex items-center justify-center px-3 py-2 text-sm font-semibold text-blue-700 bg-blue-50 dark:bg-blue-900/20 dark:text-blue-300 border border-blue-200 dark:border-blue-700 rounded-lg hover:bg-blue-100 dark:hover:bg-blue-900/30">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        Ver
                                    </a>

                                    @can('operativo-escritura')
                                        <a href="{{ route('domicilios.edit', $domicilio) }}" class="flex-1 inline-flex items-center justify-center px-3 py-2 text-sm font-semibold text-amber-800 bg-amber-50 dark:bg-amber-900/20 dark:text-amber-300 border border-amber-200 dark:border-amber-700 rounded-lg hover:bg-amber-100 dark:hover:bg-amber-900/30">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                            Editar
                                        </a>

                                        <form action="{{ route('domicilios.destroy', $domicilio) }}" method="POST" class="flex-1" onsubmit="return confirm('¿Confirma que desea eliminar este domicilio?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="w-full inline-flex items-center justify-center px-3 py-2 text-sm font-semibold text-red-700 bg-red-50 dark:bg-red-900/20 dark:text-red-300 border border-red-200 dark:border-red-700 rounded-lg hover:bg-red-100 dark:hover:bg-red-900/30">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m10 0H5" />
                                                </svg>
                                                Eliminar
                                            </button>
                                        </form>
                                    @endcan
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-12">
                                <p class="text-gray-500 dark:text-gray-400 text-sm">No hay domicilios registrados.</p>
                            </div>
                        @endforelse
                    </div>
                    
                    <div class="mt-6">
                        {{ $domicilios->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>