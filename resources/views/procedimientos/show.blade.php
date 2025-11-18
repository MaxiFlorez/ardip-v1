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
                                <dd class="mt-1 text-sm text-gray-900">{{ $procedimiento->ufi }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Orden Judicial</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $procedimiento->orden_judicial ?? '—' }}</dd>
                            </div>
                        </div>
                    </div>

                    <div class="border rounded-lg p-4 mb-6">
                        <h3 class="text-lg font-semibold mb-4 border-b pb-2">Personas Vinculadas</h3>
                        @if($procedimiento->personas->isEmpty())
                            <p class="text-sm text-gray-500">No hay personas vinculadas a este procedimiento.</p>
                        @else
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">DNI</th>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre</th>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Apellido</th>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Situación Procesal</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($procedimiento->personas as $persona)
                                            <tr>
                                                <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-800">{{ $persona->dni }}</td>
                                                <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-800">{{ $persona->nombre ?? $persona->nombres }}</td>
                                                <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-800">{{ $persona->apellido ?? $persona->apellidos }}</td>
                                                <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-800">{{ $persona->pivot->situacion_procesal ?? '—' }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>

                    <!-- Vincular Nueva Persona -->
                    <div class="border rounded-lg p-4 mb-6">
                        <h3 class="text-lg font-semibold mb-4 border-b pb-2">Vincular Nueva Persona</h3>
                        <form method="POST" action="{{ route('procedimientos.vincularPersona', $procedimiento) }}" enctype="multipart/form-data" class="space-y-6">
                            @csrf
                            <!-- Datos de Persona -->
                            <div>
                                <h4 class="font-semibold text-gray-800 mb-3">Datos de Persona</h4>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <div>
                                        <label for="dni" class="block text-sm text-gray-700">DNI</label>
                                        <input id="dni" name="dni" type="text" inputmode="numeric" maxlength="8" pattern="[0-9]{8}" class="mt-1 w-full border rounded px-3 py-2" value="{{ old('dni') }}" required>
                                        @error('dni')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                                    </div>
                                    <div>
                                        <label for="nombres" class="block text-sm text-gray-700">Nombres</label>
                                        <input id="nombres" name="nombres" type="text" class="mt-1 w-full border rounded px-3 py-2" value="{{ old('nombres') }}" required>
                                        @error('nombres')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                                    </div>
                                    <div>
                                        <label for="apellidos" class="block text-sm text-gray-700">Apellidos</label>
                                        <input id="apellidos" name="apellidos" type="text" class="mt-1 w-full border rounded px-3 py-2" value="{{ old('apellidos') }}" required>
                                        @error('apellidos')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                                    </div>
                                    <div>
                                        <label for="fecha_nacimiento" class="block text-sm text-gray-700">Fecha de Nacimiento</label>
                                        <input id="fecha_nacimiento" name="fecha_nacimiento" type="date" class="mt-1 w-full border rounded px-3 py-2" value="{{ old('fecha_nacimiento') }}" max="{{ now()->toDateString() }}" required>
                                        @error('fecha_nacimiento')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                                    </div>
                                    <div>
                                        <label for="genero" class="block text-sm text-gray-700">Género</label>
                                        <select id="genero" name="genero" class="mt-1 w-full border rounded px-3 py-2">
                                            <option value="">—</option>
                                            <option value="masculino" @selected(old('genero')==='masculino')>Masculino</option>
                                            <option value="femenino" @selected(old('genero')==='femenino')>Femenino</option>
                                            <option value="otro" @selected(old('genero')==='otro')>Otro</option>
                                        </select>
                                        @error('genero')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                                    </div>
                                    <div>
                                        <label for="alias" class="block text-sm text-gray-700">Alias</label>
                                        <input id="alias" name="alias" type="text" class="mt-1 w-full border rounded px-3 py-2" value="{{ old('alias') }}">
                                        @error('alias')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                                    </div>
                                    <div>
                                        <label for="nacionalidad" class="block text-sm text-gray-700">Nacionalidad</label>
                                        <input id="nacionalidad" name="nacionalidad" type="text" class="mt-1 w-full border rounded px-3 py-2" value="{{ old('nacionalidad', 'Argentina') }}">
                                        @error('nacionalidad')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                                    </div>
                                    <div>
                                        <label for="estado_civil" class="block text-sm text-gray-700">Estado Civil</label>
                                        <select id="estado_civil" name="estado_civil" class="mt-1 w-full border rounded px-3 py-2">
                                            <option value="">—</option>
                                            <option value="soltero" @selected(old('estado_civil')==='soltero')>Soltero/a</option>
                                            <option value="casado" @selected(old('estado_civil')==='casado')>Casado/a</option>
                                            <option value="divorciado" @selected(old('estado_civil')==='divorciado')>Divorciado/a</option>
                                            <option value="viudo" @selected(old('estado_civil')==='viudo')>Viudo/a</option>
                                            <option value="concubinato" @selected(old('estado_civil')==='concubinato')>Concubinato</option>
                                        </select>
                                        @error('estado_civil')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                                    </div>
                                    <div>
                                        <label for="foto" class="block text-sm text-gray-700">Foto</label>
                                        <input id="foto" name="foto" type="file" accept="image/*" class="mt-1 w-full border rounded px-3 py-2">
                                        @error('foto')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                                    </div>
                                    <div class="md:col-span-3">
                                        <label for="observaciones" class="block text-sm text-gray-700">Observaciones</label>
                                        <textarea id="observaciones" name="observaciones" class="mt-1 w-full border rounded px-3 py-2" rows="3">{{ old('observaciones') }}</textarea>
                                        @error('observaciones')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Domicilio -->
                            <div>
                                <h4 class="font-semibold text-gray-800 mb-3">Domicilio</h4>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <div>
                                        <label for="domicilio_provincia_id" class="block text-sm text-gray-700">Provincia</label>
                                        <select id="domicilio_provincia_id" name="domicilio[provincia_id]" class="mt-1 w-full border rounded px-3 py-2" required>
                                            <option value="">Seleccione…</option>
                                            @isset($provincias)
                                                @foreach($provincias as $prov)
                                                    <option value="{{ $prov->id }}" @selected(old('domicilio.provincia_id', $sanJuanId ?? null)==$prov->id)>{{ strtoupper($prov->nombre) }}</option>
                                                @endforeach
                                            @endisset
                                        </select>
                                        @error('domicilio.provincia_id')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                                    </div>
                                    <div>
                                        <label for="domicilio_departamento_id" class="block text-sm text-gray-700">Departamento</label>
                                        <select id="domicilio_departamento_id" name="domicilio[departamento_id]" class="mt-1 w-full border rounded px-3 py-2">
                                            <option value="">—</option>
                                            @isset($departamentos)
                                                @foreach($departamentos as $dep)
                                                    <option value="{{ $dep->id }}" @selected(old('domicilio.departamento_id')==$dep->id)>{{ strtoupper($dep->nombre) }}</option>
                                                @endforeach
                                            @endisset
                                        </select>
                                        @error('domicilio.departamento_id')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                                    </div>
                                    <div>
                                        <label for="domicilio_calle" class="block text-sm text-gray-700">Calle</label>
                                        <input id="domicilio_calle" name="domicilio[calle]" type="text" class="mt-1 w-full border rounded px-3 py-2" value="{{ old('domicilio.calle') }}" required>
                                        @error('domicilio.calle')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                                    </div>
                                    <div>
                                        <label for="domicilio_numero" class="block text-sm text-gray-700">Número</label>
                                        <input id="domicilio_numero" name="domicilio[numero]" type="number" min="0" class="mt-1 w-full border rounded px-3 py-2" value="{{ old('domicilio.numero') }}">
                                        @error('domicilio.numero')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                                    </div>
                                    <div>
                                        <label for="domicilio_barrio" class="block text-sm text-gray-700">Barrio</label>
                                        <input id="domicilio_barrio" name="domicilio[barrio]" type="text" class="mt-1 w-full border rounded px-3 py-2" value="{{ old('domicilio.barrio') }}">
                                        @error('domicilio.barrio')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                                    </div>
                                    <div>
                                        <label for="domicilio_monoblock" class="block text-sm text-gray-700">Monoblock</label>
                                        <input id="domicilio_monoblock" name="domicilio[monoblock]" type="text" class="mt-1 w-full border rounded px-3 py-2" value="{{ old('domicilio.monoblock') }}">
                                        @error('domicilio.monoblock')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                                    </div>
                                    <div>
                                        <label for="domicilio_torre" class="block text-sm text-gray-700">Torre</label>
                                        <input id="domicilio_torre" name="domicilio[torre]" type="text" class="mt-1 w-full border rounded px-3 py-2" value="{{ old('domicilio.torre') }}">
                                        @error('domicilio.torre')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                                    </div>
                                    <div>
                                        <label for="domicilio_piso" class="block text-sm text-gray-700">Piso</label>
                                        <input id="domicilio_piso" name="domicilio[piso]" type="text" class="mt-1 w-full border rounded px-3 py-2" value="{{ old('domicilio.piso') }}">
                                        @error('domicilio.piso')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                                    </div>
                                    <div>
                                        <label for="domicilio_depto" class="block text-sm text-gray-700">Depto</label>
                                        <input id="domicilio_depto" name="domicilio[depto]" type="text" class="mt-1 w-full border rounded px-3 py-2" value="{{ old('domicilio.depto') }}">
                                        @error('domicilio.depto')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                                    </div>
                                    <div>
                                        <label for="domicilio_sector" class="block text-sm text-gray-700">Sector</label>
                                        <input id="domicilio_sector" name="domicilio[sector]" type="text" class="mt-1 w-full border rounded px-3 py-2" value="{{ old('domicilio.sector') }}">
                                        @error('domicilio.sector')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                                    </div>
                                    <div>
                                        <label for="domicilio_manzana" class="block text-sm text-gray-700">Manzana</label>
                                        <input id="domicilio_manzana" name="domicilio[manzana]" type="text" class="mt-1 w-full border rounded px-3 py-2" value="{{ old('domicilio.manzana') }}">
                                        @error('domicilio.manzana')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                                    </div>
                                    <div>
                                        <label for="domicilio_lote" class="block text-sm text-gray-700">Lote</label>
                                        <input id="domicilio_lote" name="domicilio[lote]" type="text" class="mt-1 w-full border rounded px-3 py-2" value="{{ old('domicilio.lote') }}">
                                        @error('domicilio.lote')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                                    </div>
                                    <div>
                                        <label for="domicilio_casa" class="block text-sm text-gray-700">Casa</label>
                                        <input id="domicilio_casa" name="domicilio[casa]" type="text" class="mt-1 w-full border rounded px-3 py-2" value="{{ old('domicilio.casa') }}">
                                        @error('domicilio.casa')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                                    </div>
                                    <div class="md:col-span-3">
                                        <label for="dom_coordenadas_gps" class="block text-sm text-gray-700">Coordenadas GPS (lat,long)</label>
                                        <input id="dom_coordenadas_gps" name="domicilio[coordenadas_gps]" type="text" class="mt-1 w-full border rounded px-3 py-2" value="{{ old('domicilio.coordenadas_gps') }}" placeholder="-31.5,-68.5">
                                        @error('domicilio.coordenadas_gps')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                                    </div>
                                    <div class="md:col-span-3">
                                        <div id="map_persona" class="w-full h-64 border rounded"></div>
                                    </div>
                                </div>
                            </div>

                            <!-- Datos de la Vinculación (Pivote) -->
                            <div>
                                <h4 class="font-semibold text-gray-800 mb-3">Datos de la Vinculación</h4>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
                                    <div>
                                        <label for="pivot_situacion" class="block text-sm text-gray-700">Situación Procesal</label>
                                        <select id="pivot_situacion" name="pivot[situacion_procesal]" class="mt-1 w-full border rounded px-3 py-2" required>
                                            <option value="NO HALLADO" @selected(old('pivot.situacion_procesal')==='NO HALLADO')>NO HALLADO</option>
                                            <option value="DETENIDO" @selected(old('pivot.situacion_procesal')==='DETENIDO')>DETENIDO</option>
                                            <option value="APREHENDIDO" @selected(old('pivot.situacion_procesal')==='APREHENDIDO')>APREHENDIDO</option>
                                            <option value="NOTIFICADO" @selected(old('pivot.situacion_procesal')==='NOTIFICADO')>NOTIFICADO</option>
                                        </select>
                                        @error('pivot.situacion_procesal')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                                    </div>
                                    <div class="flex items-center gap-2 mt-6 md:mt-0">
                                        <input id="pivot_pedido_captura" name="pivot[pedido_captura]" type="checkbox" value="1" class="h-4 w-4" @checked(old('pivot.pedido_captura'))>
                                        <label for="pivot_pedido_captura" class="text-sm text-gray-700">Pedido de Captura</label>
                                    </div>
                                    <div class="md:col-span-3">
                                        <label for="pivot_observaciones" class="block text-sm text-gray-700">Observaciones (Vinculación)</label>
                                        <textarea id="pivot_observaciones" name="pivot[observaciones]" class="mt-1 w-full border rounded px-3 py-2" rows="2">{{ old('pivot.observaciones') }}</textarea>
                                        @error('pivot.observaciones')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                                    </div>
                                </div>
                            </div>

                            <div>
                                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-semibold px-4 py-2 rounded">Vincular Persona</button>
                            </div>
                        </form>
                    </div>

                    <div class="border rounded-lg p-4">
                        <h3 class="text-lg font-semibold mb-4 border-b pb-2">Domicilios del Hecho Vinculados</h3>
                        @if($procedimiento->domicilios->isEmpty())
                            <p class="text-sm text-gray-500">No hay domicilios vinculados a este procedimiento.</p>
                        @else
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dirección Completa</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($procedimiento->domicilios as $domicilio)
                                            <tr>
                                                <td class="px-4 py-2 whitespace-normal text-sm text-gray-800 break-words">{{ $domicilio->direccion_completa ?? ($domicilio->direccion . (isset($domicilio->localidad) ? ', '.$domicilio->localidad : '') . (optional($domicilio->provincia)->nombre ? ', '.optional($domicilio->provincia)->nombre : '')) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>

                    <!-- Vincular Nuevo Domicilio del Hecho -->
                    <div class="border rounded-lg p-4 mt-6">
                        <h3 class="text-lg font-semibold mb-4 border-b pb-2">Vincular Nuevo Domicilio del Hecho</h3>
                        <form method="POST" action="{{ route('procedimientos.vincularDomicilio', $procedimiento) }}" class="space-y-6">
                            @csrf
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label for="hecho_provincia_id" class="block text-sm text-gray-700">Provincia</label>
                                    <select id="hecho_provincia_id" name="hecho[provincia_id]" class="mt-1 w-full border rounded px-3 py-2" required>
                                        <option value="">Seleccione…</option>
                                        @isset($provincias)
                                            @foreach($provincias as $prov)
                                                <option value="{{ $prov->id }}" @selected(old('hecho.provincia_id')==$prov->id)>{{ strtoupper($prov->nombre) }}</option>
                                            @endforeach
                                        @endisset
                                    </select>
                                    @error('hecho.provincia_id')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                                </div>
                                <div>
                                    <label for="hecho_departamento_id" class="block text-sm text-gray-700">Departamento</label>
                                    <select id="hecho_departamento_id" name="hecho[departamento_id]" class="mt-1 w-full border rounded px-3 py-2">
                                        <option value="">—</option>
                                        @isset($departamentos)
                                            @foreach($departamentos as $dep)
                                                <option value="{{ $dep->id }}" @selected(old('hecho.departamento_id')==$dep->id)>{{ strtoupper($dep->nombre) }}</option>
                                            @endforeach
                                        @endisset
                                    </select>
                                    @error('hecho.departamento_id')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                                </div>
                                <div>
                                    <label for="hecho_calle" class="block text-sm text-gray-700">Calle</label>
                                    <input id="hecho_calle" name="hecho[calle]" type="text" class="mt-1 w-full border rounded px-3 py-2" value="{{ old('hecho.calle') }}" required>
                                    @error('hecho.calle')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                                </div>
                                <div>
                                    <label for="hecho_numero" class="block text-sm text-gray-700">Número</label>
                                    <input id="hecho_numero" name="hecho[numero]" type="number" min="0" class="mt-1 w-full border rounded px-3 py-2" value="{{ old('hecho.numero') }}">
                                    @error('hecho.numero')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                                </div>
                                <div>
                                    <label for="hecho_barrio" class="block text-sm text-gray-700">Barrio</label>
                                    <input id="hecho_barrio" name="hecho[barrio]" type="text" class="mt-1 w-full border rounded px-3 py-2" value="{{ old('hecho.barrio') }}">
                                    @error('hecho.barrio')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                                </div>
                                <div>
                                    <label for="hecho_monoblock" class="block text-sm text-gray-700">Monoblock</label>
                                    <input id="hecho_monoblock" name="hecho[monoblock]" type="text" class="mt-1 w-full border rounded px-3 py-2" value="{{ old('hecho.monoblock') }}">
                                    @error('hecho.monoblock')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                                </div>
                                <div>
                                    <label for="hecho_torre" class="block text-sm text-gray-700">Torre</label>
                                    <input id="hecho_torre" name="hecho[torre]" type="text" class="mt-1 w-full border rounded px-3 py-2" value="{{ old('hecho.torre') }}">
                                    @error('hecho.torre')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                                </div>
                                <div>
                                    <label for="hecho_piso" class="block text-sm text-gray-700">Piso</label>
                                    <input id="hecho_piso" name="hecho[piso]" type="text" class="mt-1 w-full border rounded px-3 py-2" value="{{ old('hecho.piso') }}">
                                    @error('hecho.piso')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                                </div>
                                <div>
                                    <label for="hecho_depto" class="block text-sm text-gray-700">Depto</label>
                                    <input id="hecho_depto" name="hecho[depto]" type="text" class="mt-1 w-full border rounded px-3 py-2" value="{{ old('hecho.depto') }}">
                                    @error('hecho.depto')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                                </div>
                                <div>
                                    <label for="hecho_sector" class="block text-sm text-gray-700">Sector</label>
                                    <input id="hecho_sector" name="hecho[sector]" type="text" class="mt-1 w-full border rounded px-3 py-2" value="{{ old('hecho.sector') }}">
                                    @error('hecho.sector')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                                </div>
                                <div>
                                    <label for="hecho_manzana" class="block text-sm text-gray-700">Manzana</label>
                                    <input id="hecho_manzana" name="hecho[manzana]" type="text" class="mt-1 w-full border rounded px-3 py-2" value="{{ old('hecho.manzana') }}">
                                    @error('hecho.manzana')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                                </div>
                                <div>
                                    <label for="hecho_lote" class="block text-sm text-gray-700">Lote</label>
                                    <input id="hecho_lote" name="hecho[lote]" type="text" class="mt-1 w-full border rounded px-3 py-2" value="{{ old('hecho.lote') }}">
                                    @error('hecho.lote')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                                </div>
                                <div>
                                    <label for="hecho_casa" class="block text-sm text-gray-700">Casa</label>
                                    <input id="hecho_casa" name="hecho[casa]" type="text" class="mt-1 w-full border rounded px-3 py-2" value="{{ old('hecho.casa') }}">
                                    @error('hecho.casa')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                                </div>
                                <div class="md:col-span-3">
                                    <label for="hecho_coordenadas" class="block text-sm text-gray-700">Coordenadas GPS (lat,long)</label>
                                    <input id="hecho_coordenadas" name="hecho[coordenadas_gps]" type="text" class="mt-1 w-full border rounded px-3 py-2" value="{{ old('hecho.coordenadas_gps') }}" placeholder="-31.5,-68.5">
                                    @error('hecho.coordenadas_gps')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                                </div>
                                <div class="md:col-span-3">
                                    <div id="map_hecho" class="w-full h-64 border rounded"></div>
                                </div>
                            </div>
                            <div>
                                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold px-4 py-2 rounded">Vincular Domicilio</button>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
@push('scripts')
<script>
// DNI solo números
document.addEventListener('DOMContentLoaded', function(){
    const dni = document.querySelector('input[name="dni"]');
    if (dni){
        dni.addEventListener('input', ()=>{ dni.value = dni.value.replace(/[^\d]/g,'').slice(0,8); });
    }

    // San Juan por defecto en Provincia (si no hay selección)
    const provSel = document.getElementById('domicilio_provincia_id');
    if (provSel && !provSel.value){
        const opt = Array.from(provSel.options).find(o => (o.text || '').toUpperCase().includes('SAN JUAN'));
        if (opt){ provSel.value = opt.value; }
    }

    // Lógica condicional: NO HALLADO -> habilita Pedido de Captura; otros casos lo deshabilitan y desmarcan
    const situacion = document.getElementById('pivot_situacion');
    const pedido = document.getElementById('pivot_pedido_captura');
    function syncPedido(){
        if (!situacion || !pedido) return;
        if (situacion.value === 'NO HALLADO'){
            pedido.disabled = false;
        } else {
            pedido.checked = false;
            pedido.disabled = true;
        }
    }
    syncPedido();
    situacion?.addEventListener('change', syncPedido);
  
    // San Juan por defecto en Provincia del Hecho
    const provHecho = document.getElementById('hecho_provincia_id');
    if (provHecho && !provHecho.value){
        const opt2 = Array.from(provHecho.options).find(o => (o.text || '').toUpperCase().includes('SAN JUAN'));
        if (opt2){ provHecho.value = opt2.value; }
    }
  
    // Inicializar mapa Leaflet para seleccionar coordenadas del Hecho
    try {
        // Cargar Leaflet si no está presente
        if (typeof L === 'undefined') {
            const link = document.createElement('link');
            link.rel = 'stylesheet';
            link.href = 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.css';
            document.head.appendChild(link);

            const script = document.createElement('script');
            script.src = 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.js';
            script.onload = function(){ initMap(); initPersonaMap(); };
            document.body.appendChild(script);
        } else {
            initMap();
            initPersonaMap();
        }

        function initMap(){
            const mapDiv = document.getElementById('map_hecho');
            if (!mapDiv) return;
            const inputCoord = document.getElementById('hecho_coordenadas');
            const start = [-31.534, -68.525]; // centro aproximado San Juan
            const map = L.map(mapDiv).setView(start, 12);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; OpenStreetMap'
            }).addTo(map);

            let marker;
            function setMarker(latlng){
                if (marker){ marker.setLatLng(latlng); }
                else { marker = L.marker(latlng, {draggable:true}).addTo(map); marker.on('dragend', ()=>{ const ll = marker.getLatLng(); inputCoord.value = `${ll.lat.toFixed(6)},${ll.lng.toFixed(6)}`; }); }
                inputCoord.value = `${latlng.lat.toFixed(6)},${latlng.lng.toFixed(6)}`;
            }

            map.on('click', (e)=> setMarker(e.latlng));

            // Si ya hay coordenadas en old(), centrar y marcar
            if (inputCoord && inputCoord.value && inputCoord.value.includes(',')){
                const parts = inputCoord.value.split(',').map(s=>parseFloat(s.trim()));
                if (parts.length===2 && !isNaN(parts[0]) && !isNaN(parts[1])){
                    const ll = {lat: parts[0], lng: parts[1]};
                    map.setView(ll, 14);
                    setMarker(ll);
                }
            }
        }
    } catch (e) {
        console.warn('Mapa no inicializado:', e);
    }
});

function initPersonaMap(){
    try{
        if (typeof L === 'undefined') return;
        const mapDiv = document.getElementById('map_persona');
        if (!mapDiv) return;
        const inputCoord = document.getElementById('dom_coordenadas_gps');
        const start = [-31.534, -68.525];
        const map = L.map(mapDiv).setView(start, 12);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; OpenStreetMap'
        }).addTo(map);

        let marker;
        function setMarker(latlng){
            if (marker){ marker.setLatLng(latlng); }
            else { marker = L.marker(latlng, {draggable:true}).addTo(map); marker.on('dragend', ()=>{ const ll = marker.getLatLng(); inputCoord.value = `${ll.lat.toFixed(6)},${ll.lng.toFixed(6)}`; }); }
            inputCoord.value = `${latlng.lat.toFixed(6)},${latlng.lng.toFixed(6)}`;
        }

        map.on('click', (e)=> setMarker(e.latlng));

        if (inputCoord && inputCoord.value && inputCoord.value.includes(',')){
            const parts = inputCoord.value.split(',').map(s=>parseFloat(s.trim()));
            if (parts.length===2 && !isNaN(parts[0]) && !isNaN(parts[1])){
                const ll = {lat: parts[0], lng: parts[1]};
                map.setView(ll, 14);
                setMarker(ll);
            }
        }
    } catch(e){ console.warn('Mapa persona no inicializado:', e); }
}
</script>
@endpush