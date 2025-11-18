<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detalle del Domicilio') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <div class="flex justify-between items-center mb-6">
                        <a href="{{ route('domicilios.index') }}"
                           class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">
                            Volver al Listado
                        </a>
                        <div class="flex space-x-2">
                            <a href="{{ route('domicilios.edit', $domicilio) }}" class="text-white bg-blue-600 hover:bg-blue-700 font-bold py-2 px-4 rounded">
                                Editar
                            </a>
                            <form action="{{ route('domicilios.destroy', $domicilio) }}" method="POST" onsubmit="return confirm('¿Estás seguro de que deseas eliminar este domicilio?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-white bg-red-600 hover:bg-red-700 font-bold py-2 px-4 rounded">
                                    Eliminar
                                </button>
                            </form>
                        </div>
                    </div>

                    <div class="border rounded-lg p-4">
                        <h3 class="text-lg font-semibold mb-4 border-b pb-2">Información del Domicilio</h3>
                        <dl class="grid grid-cols-1 md:grid-cols-3 gap-x-4 gap-y-6">
                            <div class="sm:col-span-3">
                                <dt class="text-sm font-medium text-gray-500">Dirección Normalizada</dt>
                                <dd class="mt-1 text-sm text-gray-900 font-bold">{{ $domicilio->direccion_completa }}</dd>
                            </div>
                            
                            <div class="sm:col-span-1">
                                <dt class="text-sm font-medium text-gray-500">Departamento</dt>
                                <dd class="mt-1 text-sm text-gray-900 font-bold">{{ $domicilio->departamento->nombre ?? 'N/A' }}</dd>
                            </div>
                            <div class="sm:col-span-1">
                                <dt class="text-sm font-medium text-gray-500">Provincia</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $domicilio->provincia->nombre ?? 'N/A' }}</dd>
                            </div>
                            <div class="sm:col-span-1">
                                <dt class="text-sm font-medium text-gray-500">Calle y N°</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $domicilio->calle ?? '' }} {{ $domicilio->numero ?? '' }}</dd>
                            </div>

                            <div class="sm:col-span-1">
                                <dt class="text-sm font-medium text-gray-500">Barrio</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $domicilio->barrio ?? 'N/A' }}</dd>
                            </div>
                            <div class="sm:col-span-1">
                                <dt class="text-sm font-medium text-gray-500">Sector / Zona</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $domicilio->sector ?? 'N/A' }}</dd>
                            </div>
                             <div class="sm:col-span-1">
                                <dt class="text-sm font-medium text-gray-500">Monoblock / Edificio</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $domicilio->monoblock ?? 'N/A' }}</dd>
                            </div>

                            <div class="sm:col-span-1">
                                <dt class="text-sm font-medium text-gray-500">Manzana</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $domicilio->manzana ?? 'N/A' }}</dd>
                            </div>
                            <div class="sm:col-span-1">
                                <dt class="text-sm font-medium text-gray-500">Lote / Casa</dt>
                                <dd class="mt-1 text-sm text-gray-900">
                                    {{ $domicilio->lote ? 'Lote: '.$domicilio->lote : '' }} {{ $domicilio->casa ? 'Casa: '.$domicilio->casa : '' }}
                                </dd>
                            </div>
                            <div class="sm:col-span-1">
                                <dt class="text-sm font-medium text-gray-500">Piso / Depto</dt>
                                <dd class="mt-1 text-sm text-gray-900">
                                    {{ $domicilio->piso ? 'Piso: '.$domicilio->piso : '' }} {{ $domicilio->depto ? 'Depto: '.$domicilio->depto : '' }}
                                </dd>
                            </div>

                        </dl>
                    </div>

                    <!-- Procedimientos vinculados -->
                    <div class="border rounded-lg p-4 mt-6">
                        <h3 class="text-lg font-semibold mb-4 border-b pb-2">
                            Procedimientos Vinculados ({{ $domicilio->procedimientos->count() }})
                        </h3>
                        @if($domicilio->procedimientos->count() > 0)
                            <div class="space-y-3">
                                @foreach($domicilio->procedimientos as $proc)
                                    <div class="bg-gray-50 p-4 rounded-lg">
                                        <div class="flex items-start justify-between">
                                            <div>
                                                <a href="{{ route('procedimientos.show', $proc) }}" class="font-semibold text-blue-700 hover:underline">
                                                    {{ $proc->legajo_fiscal }}
                                                </a>
                                                <p class="text-sm text-gray-600">{{ $proc->caratula }}</p>
                                                <p class="text-sm text-gray-500 mt-1">Fecha: {{ optional($proc->fecha_procedimiento)->format('d/m/Y') }}</p>
                                            </div>
                                            <div class="text-right">
                                                <span class="inline-block px-3 py-1 text-xs rounded-full bg-gray-100 text-gray-800">
                                                    {{ $proc->orden_judicial }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-sm text-gray-500">No hay procedimientos vinculados a este domicilio.</p>
                        @endif
                    </div>

                    <!-- Personas vinculadas (domicilio principal) -->
                    <div class="border rounded-lg p-4 mt-6">
                        <h3 class="text-lg font-semibold mb-4 border-b pb-2">
                            Personas Vinculadas (Domicilio principal) ({{ $domicilio->personas->count() }})
                        </h3>
                        @if($domicilio->personas->count() > 0)
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">DNI</th>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Apellidos</th>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombres</th>
                                            <th class="px-4 py-2"></th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($domicilio->personas as $pers)
                                            <tr>
                                                <td class="px-4 py-2 text-sm text-gray-900">{{ $pers->dni }}</td>
                                                <td class="px-4 py-2 text-sm text-gray-900">{{ $pers->apellidos }}</td>
                                                <td class="px-4 py-2 text-sm text-gray-900">{{ $pers->nombres }}</td>
                                                <td class="px-4 py-2 text-right">
                                                    <a href="{{ route('personas.show', $pers) }}" class="text-blue-600 hover:underline">Ver</a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-sm text-gray-500">No hay personas con este domicilio como principal.</p>
                        @endif
                    </div>

                    </div>
            </div>
        </div>
    </div>
</x-app-layout>