<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl md:text-2xl text-gray-800 leading-tight">
            {{ __('Gestión de Domicilios') }}
        </h2>
    </x-slot>

    <div class="py-6 md:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">

                {{-- Mensaje de éxito --}}
                @if (session('success'))
                    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mx-4 md:mx-6 mt-4 md:mt-6 rounded-md" role="alert">
                        <p class="font-bold text-sm md:text-base">Éxito</p>
                        <p class="text-sm md:text-base">{{ session('success') }}</p>
                    </div>
                @endif

                <div class="p-4 md:p-6 text-gray-900">

                    <div class="flex justify-end mb-4">
                        @can('panel-carga')
                            <a href="{{ route('domicilios.create') }}"
                               class="bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white font-bold py-2 px-4 rounded text-sm md:text-base transition duration-200">
                                ➕ Agregar Domicilio
                            </a>
                        @endcan
                    </div>

                    {{-- Tabla Responsive Desktop --}}
                    <div class="hidden md:block overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Dirección
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Barrio / Monoblock
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Departamento
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Acciones
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($domicilios as $domicilio)
                                    <tr class="hover:bg-gray-50 transition duration-150">
                                        <td class="px-6 py-4 text-sm font-medium text-gray-900">
                                            @php
                                                $direccion = trim(($domicilio->calle ?? '') . ' ' . ($domicilio->numero ?? ''));
                                                echo $direccion ?: 'Sin calle especificada';
                                            @endphp
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-500">
                                            @if($domicilio->barrio)
                                                B° {{ $domicilio->barrio }}
                                            @elseif($domicilio->monoblock)
                                                {{ $domicilio->monoblock }}
                                            @else
                                                N/A
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-500">
                                            {{ $domicilio->departamento }}
                                        </td>
                                        <td class="px-6 py-4 text-sm font-medium space-x-4">
                                            <a href="{{ route('domicilios.show', $domicilio) }}" class="text-indigo-600 hover:text-indigo-900 transition duration-150">Ver</a>

                                            @can('panel-carga')
                                                <a href="{{ route('domicilios.edit', $domicilio) }}" class="text-blue-600 hover:text-blue-900 transition duration-150">Editar</a>
                                            @endcan

                                            @can('panel-carga')
                                                <form action="{{ route('domicilios.destroy', $domicilio) }}" method="POST" class="inline" onsubmit="return confirm('¿Estás seguro?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900 transition duration-150">
                                                        Eliminar
                                                    </button>
                                                </form>
                                            @endcan
                                    </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-4 text-sm text-gray-500 text-center">
                                            No hay domicilios registrados.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Cards Responsive Mobile --}}
                    <div class="md:hidden space-y-3">
                        @forelse ($domicilios as $domicilio)
                            <div class="bg-gray-50 rounded-lg p-4 border border-gray-200 hover:border-gray-300 transition duration-150">
                                <div class="space-y-2">
                                    <div>
                                        <p class="text-xs font-semibold text-gray-500 uppercase">Dirección</p>
                                        <p class="text-sm font-bold text-gray-900">
                                            @php
                                                $direccion = trim(($domicilio->calle ?? '') . ' ' . ($domicilio->numero ?? ''));
                                                echo $direccion ?: 'Sin calle especificada';
                                            @endphp
                                        </p>
                                    </div>
                                    
                                    <div class="grid grid-cols-2 gap-3">
                                        <div>
                                            <p class="text-xs font-semibold text-gray-500 uppercase">Barrio/Monoblock</p>
                                            <p class="text-sm text-gray-700">
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
                                            <p class="text-xs font-semibold text-gray-500 uppercase">Departamento</p>
                                            <p class="text-sm text-gray-700">{{ $domicilio->departamento ?? 'N/A' }}</p>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="mt-4 pt-4 border-t border-gray-200 flex gap-2 flex-wrap">
                                    <a href="{{ route('domicilios.show', $domicilio) }}" class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white text-center py-2 rounded text-sm transition duration-150">
                                        Ver
                                    </a>

                                    @can('panel-carga')
                                        <a href="{{ route('domicilios.edit', $domicilio) }}" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white text-center py-2 rounded text-sm transition duration-150">
                                            Editar
                                        </a>
                                    @endcan

                                    @can('panel-carga')
                                        <form action="{{ route('domicilios.destroy', $domicilio) }}" method="POST" class="flex-1" onsubmit="return confirm('¿Estás seguro?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white py-2 rounded text-sm transition duration-150">
                                                Eliminar
                                            </button>
                                        </form>
                                    @endcan
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-12">
                                <p class="text-gray-500 text-sm">No hay domicilios registrados.</p>
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