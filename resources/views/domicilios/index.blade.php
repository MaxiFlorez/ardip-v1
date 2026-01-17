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

                    @if ($domicilios->isEmpty())
                        <div class="text-center py-12">
                            <p class="text-center text-gray-500 dark:text-gray-400 text-sm">No hay domicilios registrados.</p>
                        </div>
                    @else
                        <x-tabla :headers="['Dirección', 'Barrio / Monoblock', 'Departamento', 'Acciones']">
                            @foreach ($domicilios as $domicilio)
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
                                            <a href="{{ route('domicilios.show', $domicilio) }}" class="text-primary-600 hover:text-primary-900 dark:text-primary-400 dark:hover:text-primary-300" title="Ver Detalles">
                                                👁️
                                            </a>

                                            @can('operativo-escritura')
                                                <a href="{{ route('domicilios.edit', $domicilio) }}" class="text-warning-600 hover:text-warning-900 dark:text-warning-400 dark:hover:text-warning-300" title="Editar">
                                                    ✏️
                                                </a>

                                                <form action="{{ route('domicilios.destroy', $domicilio) }}" method="POST" class="inline" onsubmit="return confirm('¿Confirma que desea eliminar este domicilio?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-danger-600 hover:text-danger-900 dark:text-danger-400 dark:hover:text-danger-300" title="Eliminar">
                                                        🗑️
                                                    </button>
                                                </form>
                                            @endcan
                                        </div>
                                </td>
                                </tr>
                            @endforeach

                            <x-slot name="mobileView">
                                @foreach ($domicilios as $domicilio)
                                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 border border-gray-200 dark:border-gray-600">
                                        <div class="space-y-2 mb-4">
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
                                        
                                        <div class="flex gap-2 flex-wrap">
                                            <a href="{{ route('domicilios.show', $domicilio) }}" class="flex-1 text-center px-3 py-2 bg-primary-600 text-white text-xs rounded hover:bg-primary-700 transition">
                                                👁️ Ver
                                            </a>

                                            @can('operativo-escritura')
                                                <a href="{{ route('domicilios.edit', $domicilio) }}" class="flex-1 text-center px-3 py-2 bg-warning-600 text-white text-xs rounded hover:bg-warning-700 transition">
                                                    ✏️ Editar
                                                </a>

                                                <form action="{{ route('domicilios.destroy', $domicilio) }}" method="POST" class="flex-1" onsubmit="return confirm('¿Confirma que desea eliminar este domicilio?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="w-full px-3 py-2 bg-danger-600 text-white text-xs rounded hover:bg-danger-700 transition">
                                                        🗑️ Eliminar
                                                    </button>
                                                </form>
                                            @endcan
                                        </div>
                                    </div>
                                @endforeach
                            </x-slot>
                        </x-tabla>
                    @endif
                    
                    @if ($domicilios->hasPages())
                        <div class="mt-6">
                            {{ $domicilios->links() }}
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>