<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Detalle del Procedimiento
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif
            @if (session('error'))
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <div class="flex justify-between items-center mb-6">
                        <a href="{{ route('procedimientos.index') }}"
                           class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                            </svg>
                            Volver al Listado
                        </a>
                        <div class="flex space-x-2">
                            <a href="{{ route('procedimientos.edit', $procedimiento) }}" class="text-white bg-blue-600 hover:bg-blue-700 font-bold py-2 px-4 rounded">
                                Editar
                            </a>
                            <form action="{{ route('procedimientos.destroy', $procedimiento) }}" method="POST" onsubmit="return confirm('¿Estás seguro de que deseas eliminar este procedimiento?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-white bg-red-600 hover:bg-red-700 font-bold py-2 px-4 rounded">
                                    Eliminar
                                </button>
                            </form>
                        </div>
                    </div>

                    <div class="border rounded-lg p-4 mb-6">
                        <h3 class="text-lg font-semibold mb-4 border-b pb-2">Datos Generales</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Legajo Fiscal</dt>
                                <dd class="mt-1 text-sm text-gray-900 font-bold">{{ $procedimiento->legajo_fiscal }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Fecha y Hora</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $procedimiento->fecha_procedimiento->format('d/m/Y') }} {{ $procedimiento->hora_procedimiento ? $procedimiento->hora_procedimiento->format('H:i') : '' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Brigada Actuante</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $procedimiento->brigada->nombre ?? 'N/A' }}</dd>
                            </div>
                            <div class="md:col-span-3">
                                <dt class="text-sm font-medium text-gray-500">Carátula</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $procedimiento->caratula }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">UFI Interviniente</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $procedimiento->ufi->nombre ?? 'N/A' }}</dd>
                            </div>
                        </div>
                    </div>

                    <div class="border rounded-lg p-4 mb-6">
                        <h3 class="text-lg font-semibold mb-4 border-b pb-2">Personas Involucradas</h3>
                        <ul class="divide-y divide-gray-200">
                            @forelse ($procedimiento->personas as $persona)
                                <li class="py-3 flex justify-between items-center">
                                    <div>
                                        <p class="text-sm font-medium text-blue-600">{{ $persona->apellidos }}, {{ $persona->nombres }}</p>
                                        <p class="text-sm text-gray-500">DNI: {{ $persona->dni }} - Alias: "{{ $persona->alias }}"</p>
                                    </div>
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                   {{ $persona->pivot->situacion_procesal == 'detenido' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800' }}">
                                        {{ ucfirst($persona->pivot->situacion_procesal) }}
                                    </span>
                                </li>
                            @empty
                                <li class="py-3 text-sm text-gray-500">No hay personas vinculadas a este procedimiento.</li>
                            @endforelse
                        </ul>

                        <form action="{{ route('procedimientos.vincularPersona', $procedimiento) }}" method="POST" class="mt-4 border-t pt-4">
                            @csrf
                            <h4 class="text-md font-semibold mb-2">Vincular Persona Existente</h4>
                            
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                
                                <div class="md:col-span-1">
                                    <label for="persona_id" class="block text-sm font-medium text-gray-700">Persona</label>
                                    <select name="persona_id" id="persona_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                                        <option value="">Seleccione...</option>
                                        @foreach ($personasDisponibles as $persona)
                                            <option value="{{ $persona->id }}">
                                                {{ $persona->apellidos }}, {{ $persona->nombres }} (DNI: {{ $persona->dni }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="md:col-span-1">
                                    <label for="situacion_procesal" class="block text-sm font-medium text-gray-700">Situación Procesal</label>
                                    <select name="situacion_procesal" id="situacion_procesal" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                                        <option value="detenido">Detenido</option>
                                        <option value="notificado">Notificado</option>
                                        <option value="no_hallado">No Hallado</option>
                                        <option value="contravencion">Contravención</option>
                                    </select>
                                </div>

                                <div class="md:col-span-1 flex items-end">
                                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded w-full">
                                        Vincular Persona
                                    </button>
                                </div>

                                <div class="md:col-span-3">
                                     <label for="observaciones_vinculo" class="block text-sm font-medium text-gray-700">Observaciones (Opcional)</label>
                                     <textarea name="observaciones" id="observaciones_vinculo" rows="2" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"></textarea>
                                </div>

                            </div>
                        </form>
                        </div>

                    <div class="border rounded-lg p-4">
                        <h3 class="text-lg font-semibold mb-4 border-b pb-2">Domicilios Allanados</h3>
                        <ul class="divide-y divide-gray-200">
                            @forelse ($procedimiento->domicilios as $domicilio)
                                <li class="py-3">
                                    <p class="text-sm font-medium text-gray-900">
                                        {{ $domicilio->calle ?? '' }} {{ $domicilio->numero ?? '' }} - {{ $domicilio->departamento }}
                                    </p>
                                    <p class="text-sm text-gray-500">
                                        {{ $domicilio->barrio ?? '' }} {{ $domicilio->monoblock ?? '' }} {{ $domicilio->manzana ?? '' }} {{ $domicilio->lote ?? '' }}
                                    </p>
                                </li>
                            @empty
                                <li class="py-3 text-sm text-gray-500">No hay domicilios vinculados a este procedimiento.</li>
                            @endforelse

                            <form action="{{ route('procedimientos.vincularDomicilio', $procedimiento) }}" method="POST" class="mt-4 border-t pt-4">
                            @csrf
                            <h4 class="text-md font-semibold mb-2">Vincular Domicilio Existente</h4>
                            
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                
                                <div class="md:col-span-2">
                                    <label for="domicilio_id" class="block text-sm font-medium text-gray-700">Domicilio</label>
                                    <select name="domicilio_id" id="domicilio_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                                        <option value="">Seleccione...</option>
                            {{-- Iteramos sobre los domicilios que trajimos desde el controlador --}}
                            @foreach ($domiciliosDisponibles as $domicilio)
                                <option value="{{ $domicilio->id }}">
                                    {{-- (Simplificamos la dirección para el desplegable) --}}
                                    {{ $domicilio->calle ?? '' }} {{ $domicilio->numero ?? '' }} ({{ $domicilio->departamento }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                                <div class="md:col-span-1 flex items-end">
                                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded w-full">
                                        Vincular Domicilio
                                    </button>
                                </div>

                            </div>
                        </form>
                        </ul>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>