<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <h2 class="font-semibold text-xl md:text-2xl text-gray-800 dark:text-gray-200 leading-tight">
                Detalle del Procedimiento
            </h2>
        </div>
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

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-6">
                        <a href="{{ route('procedimientos.index') }}" class="inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-700 bg-gray-100 border border-gray-200 rounded-lg hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-400 dark:text-gray-200 dark:bg-gray-700 dark:border-gray-600 dark:hover:bg-gray-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                            </svg>
                            Volver
                        </a>
                        <div class="flex flex-wrap items-center gap-2">
                            @can('operativo-escritura')
                                <a href="{{ route('procedimientos.edit', $procedimiento) }}" class="inline-flex items-center px-4 py-2 text-sm font-semibold text-amber-800 bg-amber-50 border border-amber-200 rounded-lg hover:bg-amber-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500 dark:text-amber-100 dark:bg-amber-900/40 dark:border-amber-700">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5h2m2 0h2m-6 4h6m-6 4h6m-8 4h8m-8 0H7a2 2 0 01-2-2V7a2 2 0 012-2h4m-2 6h2" />
                                    </svg>
                                    Editar
                                </a>

                                <form action="{{ route('procedimientos.destroy', $procedimiento) }}" method="POST" class="inline-flex" onsubmit="return confirm('¿Confirma que desea eliminar este procedimiento? Esta acción no se puede deshacer.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex items-center px-4 py-2 text-sm font-semibold text-red-700 bg-red-50 border border-red-200 rounded-lg hover:bg-red-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 dark:text-red-100 dark:bg-red-900/50 dark:border-red-800">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m10 0H5" />
                                        </svg>
                                        Eliminar
                                    </button>
                                </form>
                            @endcan
                        </div>
                    </div>

                    <div class="border rounded-lg p-5 mb-6 bg-gray-50 dark:bg-gray-900/40">
                        <h3 class="text-lg font-semibold mb-4 border-b pb-2 border-gray-200 dark:border-gray-700">Datos Generales</h3>
                        <dl class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                            <div class="p-3 bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700">
                                <dt class="text-gray-500 dark:text-gray-400">Legajo Fiscal</dt>
                                <dd class="mt-1 text-gray-900 dark:text-gray-100 font-semibold">{{ $procedimiento->legajo_fiscal }}</dd>
                            </div>
                            <div class="p-3 bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700">
                                <dt class="text-gray-500 dark:text-gray-400">Fecha y Hora</dt>
                                <dd class="mt-1 text-gray-900 dark:text-gray-100">{{ $procedimiento->fecha_procedimiento->format('d/m/Y') }} {{ $procedimiento->hora_procedimiento ? $procedimiento->hora_procedimiento->format('H:i') : '' }}</dd>
                            </div>
                            <div class="p-3 bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700">
                                <dt class="text-gray-500 dark:text-gray-400">UFI Interviniente</dt>
                                <dd class="mt-1 text-gray-900 dark:text-gray-100">{{ $procedimiento->ufi->nombre ?? 'N/A' }}</dd>
                            </div>
                            <div class="p-3 bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700">
                                <dt class="text-gray-500 dark:text-gray-400">Brigada Actuante</dt>
                                <dd class="mt-1 text-gray-900 dark:text-gray-100">{{ $procedimiento->brigada->nombre ?? 'N/A' }}</dd>
                            </div>
                            <div class="md:col-span-3 p-3 bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700">
                                <dt class="text-gray-500 dark:text-gray-400">Carátula</dt>
                                <dd class="mt-1 text-gray-900 dark:text-gray-100">{{ $procedimiento->caratula }}</dd>
                            </div>
                            @if($procedimiento->relato ?? false)
                                <div class="md:col-span-3 p-3 bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700">
                                    <dt class="text-gray-500 dark:text-gray-400">Relato</dt>
                                    <dd class="mt-1 text-gray-900 dark:text-gray-100 whitespace-pre-line">{{ $procedimiento->relato }}</dd>
                                </div>
                            @endif
                        </dl>
                    </div>

                    <div class="border rounded-lg p-5 mb-6 bg-white dark:bg-gray-900/30">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold">Personas Involucradas</h3>
                            <span class="text-sm text-gray-500 dark:text-gray-400">{{ $procedimiento->personas->count() }} registradas</span>
                        </div>
                        <ul class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse ($procedimiento->personas as $persona)
                                <li class="py-3 flex justify-between items-center">
                                    <div>
                                        <p class="text-sm font-medium text-blue-600 dark:text-blue-300">{{ $persona->apellidos }}, {{ $persona->nombres }}</p>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">DNI: {{ $persona->dni }} - Alias: "{{ $persona->alias }}"</p>
                                    </div>
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $persona->pivot->situacion_procesal == 'detenido' ? 'bg-red-100 text-red-800 dark:bg-red-900/40 dark:text-red-200' : 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/40 dark:text-yellow-200' }}">
                                        {{ ucfirst($persona->pivot->situacion_procesal) }}
                                    </span>
                                </li>
                            @empty
                                <li class="py-3 text-sm text-gray-500 dark:text-gray-400">No hay personas vinculadas a este procedimiento.</li>
                            @endforelse
                        </ul>

                        @can('operativo-escritura')
                        <form action="{{ route('procedimientos.vincularPersona', $procedimiento) }}" method="POST" class="mt-4 border-t border-gray-200 dark:border-gray-700 pt-4">
                            @csrf
                            <h4 class="text-md font-semibold mb-3">Vincular Persona Existente</h4>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label for="persona_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Persona</label>
                                    <select name="persona_id" id="persona_id" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 shadow-sm" required>
                                        <option value="">Seleccione...</option>
                                        @foreach ($personasDisponibles as $persona)
                                            <option value="{{ $persona->id }}">
                                                {{ $persona->apellidos }}, {{ $persona->nombres }} (DNI: {{ $persona->dni }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label for="situacion_procesal" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Situación Procesal</label>
                                    <select name="situacion_procesal" id="situacion_procesal" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 shadow-sm" required>
                                        <option value="detenido">Detenido</option>
                                        <option value="notificado">Notificado</option>
                                        <option value="no_hallado">No Hallado</option>
                                        <option value="contravencion">Contravención</option>
                                    </select>
                                </div>

                                <div class="flex items-end">
                                    <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-2 text-sm font-semibold text-green-700 bg-green-50 border border-green-200 rounded-lg hover:bg-green-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 dark:text-green-100 dark:bg-green-900/50 dark:border-green-800">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                        </svg>
                                        Vincular Persona
                                    </button>
                                </div>

                                <div class="md:col-span-3">
                                    <label for="observaciones_vinculo" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Observaciones (Opcional)</label>
                                    <textarea name="observaciones" id="observaciones_vinculo" rows="2" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 shadow-sm"></textarea>
                                </div>
                            </div>
                        </form>
                        @endcan
                    </div>

                    <div class="border rounded-lg p-5 bg-white dark:bg-gray-900/30">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold">Domicilios Allanados</h3>
                            <span class="text-sm text-gray-500 dark:text-gray-400">{{ $procedimiento->domicilios->count() }} registrados</span>
                        </div>
                        <ul class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse ($procedimiento->domicilios as $domicilio)
                                <li class="py-3">
                                    <p class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                        {{ $domicilio->calle ?? '' }} {{ $domicilio->numero ?? '' }} - {{ $domicilio->departamento }}
                                    </p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        {{ $domicilio->barrio ?? '' }} {{ $domicilio->monoblock ?? '' }} {{ $domicilio->manzana ?? '' }} {{ $domicilio->lote ?? '' }}
                                    </p>
                                </li>
                            @empty
                                <li class="py-3 text-sm text-gray-500 dark:text-gray-400">No hay domicilios vinculados a este procedimiento.</li>
                            @endforelse
                        </ul>

                        @can('operativo-escritura')
                        <form action="{{ route('procedimientos.vincularDomicilio', $procedimiento) }}" method="POST" class="mt-4 border-t border-gray-200 dark:border-gray-700 pt-4">
                            @csrf
                            <h4 class="text-md font-semibold mb-3">Vincular Domicilio Existente</h4>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div class="md:col-span-2">
                                    <label for="domicilio_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Domicilio</label>
                                    <select name="domicilio_id" id="domicilio_id" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 shadow-sm" required>
                                        <option value="">Seleccione...</option>
                                        @foreach ($domiciliosDisponibles as $domicilio)
                                            <option value="{{ $domicilio->id }}">
                                                {{ $domicilio->calle ?? '' }} {{ $domicilio->numero ?? '' }} ({{ $domicilio->departamento }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="md:col-span-1 flex items-end">
                                    <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-2 text-sm font-semibold text-green-700 bg-green-50 border border-green-200 rounded-lg hover:bg-green-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 dark:text-green-100 dark:bg-green-900/50 dark:border-green-800">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                        </svg>
                                        Vincular Domicilio
                                    </button>
                                </div>
                            </div>
                        </form>
                        @endcan
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>