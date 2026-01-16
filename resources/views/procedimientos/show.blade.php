<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <a href="{{ route('procedimientos.index') }}" class="text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100">
                    ← Volver
                </a>
                <h2 class="font-semibold text-xl md:text-2xl text-gray-800 dark:text-gray-200 leading-tight">
                    📁 Tablero del Procedimiento
                </h2>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Mensajes Flash --}}
            @if (session('success'))
                <div class="mb-6 bg-green-50 dark:bg-green-900/30 border-l-4 border-green-500 p-4 rounded-lg shadow-sm">
                    <div class="flex items-center">
                        <span class="text-2xl mr-3">✅</span>
                        <span class="text-green-800 dark:text-green-200 font-medium">{{ session('success') }}</span>
                    </div>
                </div>
            @endif
            @if (session('error'))
                <div class="mb-6 bg-red-50 dark:bg-red-900/30 border-l-4 border-red-500 p-4 rounded-lg shadow-sm">
                    <div class="flex items-center">
                        <span class="text-2xl mr-3">❌</span>
                        <span class="text-red-800 dark:text-red-200 font-medium">{{ session('error') }}</span>
                    </div>
                </div>
            @endif

            {{-- ========== ENCABEZADO: DATOS PRINCIPALES DEL PROCEDIMIENTO ========== --}}
            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 rounded-xl shadow-lg border border-blue-200 dark:border-blue-800 p-6 mb-8">
                <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-6">
                    
                    {{-- Datos Principales --}}
                    <div class="flex-1">
                        <div class="flex items-center gap-3 mb-4">
                            <span class="text-4xl">📋</span>
                            <div>
                                <h3 class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                                    {{ $procedimiento->legajo_fiscal }}
                                </h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Legajo Fiscal</p>
                            </div>
                        </div>

                        <div class="bg-white dark:bg-gray-800 rounded-lg p-4 space-y-3 shadow-sm">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">📅 Fecha y Hora</p>
                                    <p class="text-sm font-semibold text-gray-900 dark:text-gray-100">
                                        {{ $procedimiento->fecha_procedimiento->format('d/m/Y') }} 
                                        {{ $procedimiento->hora_procedimiento ? $procedimiento->hora_procedimiento->format('H:i') : '' }}
                                    </p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">🏛️ UFI Interviniente</p>
                                    <p class="text-sm font-semibold text-gray-900 dark:text-gray-100">
                                        {{ $procedimiento->ufi->nombre ?? 'N/A' }}
                                    </p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">👮 Brigada Actuante</p>
                                    <p class="text-sm font-semibold text-gray-900 dark:text-gray-100">
                                        {{ $procedimiento->brigada->nombre ?? 'N/A' }}
                                    </p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">👤 Cargado por</p>
                                    <p class="text-sm font-semibold text-gray-900 dark:text-gray-100">
                                        {{ $procedimiento->user->name ?? 'N/A' }}
                                    </p>
                                </div>
                            </div>
                            
                            <div class="pt-3 border-t border-gray-200 dark:border-gray-700">
                                <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">📄 Carátula</p>
                                <p class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                    {{ $procedimiento->caratula }}
                                </p>
                            </div>

                            @if($procedimiento->relato)
                                <div class="pt-3 border-t border-gray-200 dark:border-gray-700">
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">📝 Relato</p>
                                    <p class="text-sm text-gray-700 dark:text-gray-300 whitespace-pre-line">
                                        {{ $procedimiento->relato }}
                                    </p>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Botones de Acción Principal --}}
                    <div class="flex flex-col gap-3 lg:w-48">
                        @can('operativo-escritura')
                            <a href="{{ route('procedimientos.edit', $procedimiento) }}" 
                               class="inline-flex items-center justify-center px-4 py-3 text-sm font-semibold text-amber-800 bg-amber-100 dark:bg-amber-900/40 dark:text-amber-200 rounded-lg hover:bg-amber-200 dark:hover:bg-amber-900/60 transition duration-150 shadow-sm border border-amber-300 dark:border-amber-700">
                                ✏️ Editar Datos
                            </a>

                            <form action="{{ route('procedimientos.destroy', $procedimiento) }}" method="POST" 
                                  onsubmit="return confirm('⚠️ ¿Confirma que desea eliminar este procedimiento? Esta acción no se puede deshacer.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="w-full inline-flex items-center justify-center px-4 py-3 text-sm font-semibold text-red-800 bg-red-100 dark:bg-red-900/40 dark:text-red-200 rounded-lg hover:bg-red-200 dark:hover:bg-red-900/60 transition duration-150 shadow-sm border border-red-300 dark:border-red-700">
                                    🗑️ Eliminar
                                </button>
                            </form>
                        @endcan
                    </div>
                </div>
            </div>

            {{-- ========== SECCIÓN CENTRAL: PANELES DE SATÉLITES ========== --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                {{-- ========== PANEL A: PERSONAS VINCULADAS ========== --}}
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div class="bg-gradient-to-r from-purple-500 to-pink-500 px-6 py-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <span class="text-3xl">👥</span>
                                <div>
                                    <h3 class="text-xl font-bold text-white">Personas</h3>
                                    <p class="text-sm text-purple-100">{{ $procedimiento->personas->count() }} vinculada(s)</p>
                                </div>
                            </div>
                            @can('operativo-escritura')
                                <a href="{{ route('personas.create', ['procedimiento_id' => $procedimiento->id]) }}" 
                                   class="inline-flex items-center px-4 py-2 bg-white text-purple-700 font-semibold text-sm rounded-lg hover:bg-purple-50 transition duration-150 shadow-md">
                                    <span class="text-xl mr-2">➕</span>
                                    Nueva Persona
                                </a>
                            @endcan
                        </div>
                    </div>

                    <div class="p-6">
                        @if($procedimiento->personas->count() > 0)
                            <div class="space-y-3">
                                @foreach ($procedimiento->personas as $persona)
                                    <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-900/40 rounded-lg border border-gray-200 dark:border-gray-700 hover:shadow-md transition duration-150">
                                        <div class="flex-1">
                                            <div class="flex items-center gap-2 mb-1">
                                                <a href="{{ route('personas.show', $persona) }}" 
                                                   class="text-sm font-bold text-blue-600 dark:text-blue-400 hover:underline">
                                                    {{ $persona->apellidos }}, {{ $persona->nombres }}
                                                </a>
                                                <span class="px-2 py-0.5 text-xs font-semibold rounded-full 
                                                    {{ $persona->pivot->situacion_procesal == 'detenido' 
                                                        ? 'bg-red-100 text-red-800 dark:bg-red-900/40 dark:text-red-200' 
                                                        : 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/40 dark:text-yellow-200' }}">
                                                    {{ ucfirst($persona->pivot->situacion_procesal) }}
                                                </span>
                                            </div>
                                            <p class="text-xs text-gray-600 dark:text-gray-400">
                                                DNI: {{ $persona->dni }} | Alias: "{{ $persona->alias }}"
                                            </p>
                                            @if($persona->pivot->observaciones)
                                                <p class="text-xs text-gray-500 dark:text-gray-500 mt-1 italic">
                                                    💬 {{ $persona->pivot->observaciones }}
                                                </p>
                                            @endif
                                        </div>
                                        <a href="{{ route('personas.show', $persona) }}" 
                                           class="ml-4 text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300">
                                            👁️
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-12">
                                <span class="text-6xl opacity-20">👥</span>
                                <p class="mt-4 text-sm text-gray-500 dark:text-gray-400">
                                    No hay personas vinculadas aún
                                </p>
                            </div>
                        @endif

                        @can('operativo-escritura')
                            {{-- Formulario de Vinculación Rápida --}}
                            <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                                <h4 class="text-sm font-bold text-gray-700 dark:text-gray-300 mb-3">
                                    🔗 Vincular Persona Existente
                                </h4>
                                <form action="{{ route('procedimientos.vincularPersona', $procedimiento) }}" method="POST" class="space-y-3">
                                    @csrf
                                    <div>
                                        <select name="persona_id" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 text-sm shadow-sm" required>
                                            <option value="">Seleccione una persona...</option>
                                            @foreach ($personasDisponibles as $persona)
                                                <option value="{{ $persona->id }}">
                                                    {{ $persona->apellidos }}, {{ $persona->nombres }} (DNI: {{ $persona->dni }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="grid grid-cols-2 gap-3">
                                        <select name="situacion_procesal" class="rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 text-sm shadow-sm" required>
                                            <option value="detenido">Detenido</option>
                                            <option value="notificado">Notificado</option>
                                            <option value="no_hallado">No Hallado</option>
                                            <option value="contravencion">Contravención</option>
                                        </select>
                                        <button type="submit" class="inline-flex items-center justify-center px-4 py-2 bg-purple-600 dark:bg-purple-700 text-white font-semibold text-sm rounded-lg hover:bg-purple-700 dark:hover:bg-purple-600 transition duration-150">
                                            ➕ Vincular
                                        </button>
                                    </div>
                                    <textarea name="observaciones" rows="2" placeholder="Observaciones (opcional)" 
                                              class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 text-sm shadow-sm"></textarea>
                                </form>
                            </div>
                        @endcan
                    </div>
                </div>

                {{-- ========== PANEL B: DOMICILIOS / LUGARES ========== --}}
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div class="bg-gradient-to-r from-green-500 to-teal-500 px-6 py-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <span class="text-3xl">📍</span>
                                <div>
                                    <h3 class="text-xl font-bold text-white">Domicilios</h3>
                                    <p class="text-sm text-green-100">{{ $procedimiento->domicilios->count() }} vinculado(s)</p>
                                </div>
                            </div>
                            @can('operativo-escritura')
                                <a href="{{ route('domicilios.create', ['procedimiento_id' => $procedimiento->id]) }}" 
                                   class="inline-flex items-center px-4 py-2 bg-white text-green-700 font-semibold text-sm rounded-lg hover:bg-green-50 transition duration-150 shadow-md">
                                    <span class="text-xl mr-2">➕</span>
                                    Nuevo Domicilio
                                </a>
                            @endcan
                        </div>
                    </div>

                    <div class="p-6">
                        @if($procedimiento->domicilios->count() > 0)
                            <div class="space-y-3">
                                @foreach ($procedimiento->domicilios as $domicilio)
                                    <div class="p-4 bg-gray-50 dark:bg-gray-900/40 rounded-lg border border-gray-200 dark:border-gray-700 hover:shadow-md transition duration-150">
                                        <div class="flex items-start justify-between">
                                            <div class="flex-1">
                                                <p class="text-sm font-bold text-gray-900 dark:text-gray-100">
                                                    {{ $domicilio->calle ?? 'Sin calle' }} {{ $domicilio->altura ?? '' }}
                                                </p>
                                                <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">
                                                    @if($domicilio->barrio)
                                                        Barrio: {{ $domicilio->barrio }}
                                                    @endif
                                                    @if($domicilio->localidad)
                                                        | {{ $domicilio->localidad }}
                                                    @endif
                                                    @if($domicilio->provincia)
                                                        | {{ $domicilio->provincia }}
                                                    @endif
                                                </p>
                                                @if($domicilio->monoblock || $domicilio->manzana || $domicilio->lote)
                                                    <p class="text-xs text-gray-500 dark:text-gray-500 mt-1">
                                                        {{ $domicilio->monoblock ? "Monoblock: {$domicilio->monoblock}" : '' }}
                                                        {{ $domicilio->manzana ? "Mz: {$domicilio->manzana}" : '' }}
                                                        {{ $domicilio->lote ? "Lt: {$domicilio->lote}" : '' }}
                                                    </p>
                                                @endif
                                                @if($domicilio->latitud && $domicilio->longitud)
                                                    <p class="text-xs text-green-600 dark:text-green-400 mt-1">
                                                        📍 Coordenadas: {{ number_format($domicilio->latitud, 6) }}, {{ number_format($domicilio->longitud, 6) }}
                                                    </p>
                                                @endif
                                            </div>
                                            <a href="{{ route('domicilios.show', $domicilio) }}" 
                                               class="ml-4 text-green-600 dark:text-green-400 hover:text-green-800 dark:hover:text-green-300">
                                                👁️
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-12">
                                <span class="text-6xl opacity-20">📍</span>
                                <p class="mt-4 text-sm text-gray-500 dark:text-gray-400">
                                    No hay domicilios vinculados aún
                                </p>
                            </div>
                        @endif

                        @can('operativo-escritura')
                            {{-- Formulario de Vinculación Rápida --}}
                            <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                                <h4 class="text-sm font-bold text-gray-700 dark:text-gray-300 mb-3">
                                    🔗 Vincular Domicilio Existente
                                </h4>
                                <form action="{{ route('procedimientos.vincularDomicilio', $procedimiento) }}" method="POST" class="space-y-3">
                                    @csrf
                                    <div>
                                        <select name="domicilio_id" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 text-sm shadow-sm" required>
                                            <option value="">Seleccione un domicilio...</option>
                                            @foreach ($domiciliosDisponibles as $domicilio)
                                                <option value="{{ $domicilio->id }}">
                                                    {{ $domicilio->calle ?? 'Sin calle' }} {{ $domicilio->altura ?? '' }} 
                                                    @if($domicilio->barrio)
                                                        ({{ $domicilio->barrio }})
                                                    @endif
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-2 bg-green-600 dark:bg-green-700 text-white font-semibold text-sm rounded-lg hover:bg-green-700 dark:hover:bg-green-600 transition duration-150">
                                        ➕ Vincular Domicilio
                                    </button>
                                </form>
                            </div>
                        @endcan
                    </div>
                </div>

            </div>

            {{-- ========== PANEL C: DOCUMENTOS / ARCHIVOS (FUTURO) ========== --}}
            <div class="mt-6 bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="bg-gradient-to-r from-orange-500 to-red-500 px-6 py-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <span class="text-3xl">📎</span>
                            <div>
                                <h3 class="text-xl font-bold text-white">Documentos Adjuntos</h3>
                                <p class="text-sm text-orange-100">Biblioteca digital del procedimiento</p>
                            </div>
                        </div>
                        @can('panel-carga')
                            <a href="{{ route('documentos.create', ['procedimiento_id' => $procedimiento->id]) }}" 
                               class="inline-flex items-center px-4 py-2 bg-white text-orange-700 font-semibold text-sm rounded-lg hover:bg-orange-50 transition duration-150 shadow-md">
                                <span class="text-xl mr-2">➕</span>
                                Subir Documento
                            </a>
                        @endcan
                    </div>
                </div>

                <div class="p-6">
                    <div class="text-center py-8">
                        <span class="text-6xl opacity-20">📂</span>
                        <p class="mt-4 text-sm text-gray-500 dark:text-gray-400">
                            Sección de documentos en desarrollo
                        </p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>