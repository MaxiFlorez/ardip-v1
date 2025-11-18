<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-lg md:text-xl lg:text-2xl text-gray-800 leading-tight">
            Carga Unificada (Actuación + Persona + Domicilios)
        </h2>
    </x-slot>

    @php
        $errorsPaso1 = $errors->hasAny(['legajo_fiscal','caratula','fecha_procedimiento','hora_procedimiento','orden_judicial']);
        $errorsPaso2 = $errors->hasAny([
            'dni','nombres','apellidos','fecha_nacimiento','foto',
            'persona.genero','persona.alias','persona.nacionalidad','persona.estado_civil','persona.observaciones',
            'genero','alias','nacionalidad','estado_civil','observaciones',
            'domicilio.calle','domicilio.numero','domicilio.barrio','domicilio.provincia_id','domicilio.departamento_id'
        ]);
        $errorsPaso3 = $errors->hasAny(['domicilios_allanados.*.calle_allanada','domicilios_allanados.*.latitud','domicilios_allanados.*.longitud']);
        $initialTab = request('tab', ($errorsPaso2 ? 'vinculados' : ($errorsPaso3 ? 'ubicacion' : 'legales')));
    @endphp

    <div class="py-8 md:py-12">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-4 md:p-6 lg:p-8 text-gray-900">

                    @if ($errors->any())
                        <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded">
                            <ul class="list-disc list-inside text-sm text-red-700">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- Referencia antigua deshabilitada: route('carga.store') --}}
                    <form action="#" method="POST" enctype="multipart/form-data">
                        @csrf
                        @if(isset($procedimiento) && $procedimiento)
                            <input type="hidden" name="procedimiento_id" value="{{ $procedimiento->id }}">
                        @endif

                        <!-- Tabs Header -->
                        <div class="border-b mb-6">
                            <nav class="-mb-px flex space-x-6" aria-label="Tabs">
                                <button id="tabbtn-legales" type="button" onclick="showTab('legales')" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium">Datos Legales</button>
                                <button id="tabbtn-vinculados" type="button" onclick="nextTab('legales','vinculados')" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium {{ !$hasProcedimiento ? 'opacity-50 pointer-events-none cursor-not-allowed' : '' }}" {{ !$hasProcedimiento ? 'disabled' : '' }}>Vinculados</button>
                                <button id="tabbtn-ubicacion" type="button" onclick="nextTab('vinculados','ubicacion')" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium {{ !$hasProcedimiento ? 'opacity-50 pointer-events-none cursor-not-allowed' : '' }}" {{ !$hasProcedimiento ? 'disabled' : '' }}>Domicilios del Hecho y Mapa</button>
                            </nav>
                        </div>

                        <!-- Tab 1: Datos Legales (Actuación) -->
                        <div id="tab_legales" {{ $initialTab==='legales' ? '' : 'hidden' }}>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="legajo_fiscal" class="block text-sm">Legajo Fiscal *</label>
                                    <input id="legajo_fiscal" type="text" name="legajo_fiscal" value="{{ old('legajo_fiscal') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                                    @error('legajo_fiscal')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                                </div>
                                <div class="md:col-span-2">
                                    <label for="caratula" class="block text-sm">Carátula *</label>
                                    <textarea id="caratula" name="caratula" rows="2" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>{{ old('caratula') }}</textarea>
                                    @error('caratula')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                                </div>
                                <div>
                                    <label for="fecha_procedimiento" class="block text-sm">Fecha Procedimiento *</label>
                                    <input id="fecha_procedimiento" type="date" name="fecha_procedimiento" value="{{ old('fecha_procedimiento') }}" max="{{ now()->toDateString() }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                                    @error('fecha_procedimiento')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                                </div>
                                <div>
                                    <label for="hora_procedimiento" class="block text-sm">Hora Procedimiento</label>
                                    <input id="hora_procedimiento" type="time" name="hora_procedimiento" value="{{ old('hora_procedimiento') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                    @error('hora_procedimiento')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                                </div>
                                <div>
                                    <label for="orden_judicial" class="block text-sm">Orden Judicial *</label>
                                    <select id="orden_judicial" name="orden_judicial" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                                        <option value="">Seleccione…</option>
                                        <option value="Detención en caso de secuestro positivo" @selected(old('orden_judicial')==='Detención en caso de secuestro positivo')>Detención en caso de secuestro positivo</option>
                                        <option value="Detención directa" @selected(old('orden_judicial')==='Detención directa')>Detención directa</option>
                                        <option value="Notificación al acusado" @selected(old('orden_judicial')==='Notificación al acusado')>Notificación al acusado</option>
                                        <option value="Secuestro y notificación" @selected(old('orden_judicial')==='Secuestro y notificación')>Secuestro y notificación</option>
                                    </select>
                                    @error('orden_judicial')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                                </div>
                            </div>

                            <div class="flex justify-end mt-6">
                                <button type="button" onclick="nextTab('legales','vinculados')" class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 px-4 rounded w-full md:w-auto">Siguiente</button>
                            </div>
                        </div>

                        <!-- Tab 2: Vinculados (Persona + Domicilio Legal) -->
                        <div id="tab_vinculados" {{ $initialTab==='vinculados' ? '' : 'hidden' }}>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="dni" class="block text-sm">DNI *</label>
                                    <input id="dni" type="text" name="dni" maxlength="8" inputmode="numeric" pattern="[0-9]{8}" autocomplete="off" value="{{ old('dni') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                    @error('dni')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                                </div>
                                <div>
                                    <label for="nombres" class="block text-sm">Nombres *</label>
                                    <input id="nombres" type="text" name="nombres" value="{{ old('nombres') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                    @error('nombres')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                                </div>
                                <div>
                                    <label for="apellidos" class="block text-sm">Apellidos *</label>
                                    <input id="apellidos" type="text" name="apellidos" value="{{ old('apellidos') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                    @error('apellidos')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                                </div>
                                <div>
                                    <label for="fecha_nacimiento" class="block text-sm">Fecha Nacimiento *</label>
                                    <input id="fecha_nacimiento" type="date" name="fecha_nacimiento" value="{{ old('fecha_nacimiento') }}" max="{{ now()->toDateString() }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                    @error('fecha_nacimiento')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                                </div>
                                <div>
                                    <label for="genero" class="block text-sm">Género</label>
                                    <select id="genero" name="genero" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                        <option value="">-- Seleccione --</option>
                                        <option value="masculino" @selected(old('genero')==='masculino')>Masculino</option>
                                        <option value="femenino" @selected(old('genero')==='femenino')>Femenino</option>
                                        <option value="otro" @selected(old('genero')==='otro')>Otro</option>
                                    </select>
                                    @error('genero')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                                </div>
                                <div>
                                    <label for="foto" class="block text-sm">Foto (opcional)</label>
                                    <input id="foto" type="file" name="foto" accept="image/*" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                    @error('foto')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                                </div>
                                <div>
                                    <label for="alias" class="block text-sm">Alias</label>
                                    <input id="alias" type="text" name="alias" value="{{ old('alias') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                    @error('alias')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                                </div>
                                <div>
                                    <label for="nacionalidad" class="block text-sm">Nacionalidad</label>
                                    <input id="nacionalidad" type="text" name="nacionalidad" value="{{ old('nacionalidad', 'Argentina') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                    @error('nacionalidad')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                                </div>
                                <div>
                                    <label for="estado_civil" class="block text-sm">Estado civil</label>
                                    <select id="estado_civil" name="estado_civil" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                        <option value="">-- Seleccione --</option>
                                        <option value="soltero" @selected(old('estado_civil')==='soltero')>Soltero</option>
                                        <option value="casado" @selected(old('estado_civil')==='casado')>Casado</option>
                                        <option value="divorciado" @selected(old('estado_civil')==='divorciado')>Divorciado</option>
                                        <option value="viudo" @selected(old('estado_civil')==='viudo')>Viudo</option>
                                        <option value="concubinato" @selected(old('estado_civil')==='concubinato')>Concubinato</option>
                                    </select>
                                    @error('estado_civil')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                                </div>
                                <div class="md:col-span-3">
                                    <label for="observaciones" class="block text-sm">Observaciones (Persona)</label>
                                    <textarea id="observaciones" name="observaciones" rows="2" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">{{ old('observaciones') }}</textarea>
                                    @error('observaciones')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                                </div>
                            </div>

                            <hr class="my-6">
                            <h4 class="text-lg font-bold text-gray-800">Domicilio Principal</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-2">
                                <div>
                                    <label for="domicilio_provincia_id" class="block text-sm">Provincia *</label>
                                    <select id="domicilio_provincia_id" name="domicilio[provincia_id]" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                        <option value="">Seleccione…</option>
                                        @foreach($provincias as $p)
                                            <option value="{{ $p->id }}" @selected(old('domicilio.provincia_id', $sanJuanId)==$p->id)>{{ $p->nombre }}</option>
                                        @endforeach
                                    </select>
                                    @error('domicilio.provincia_id')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                                </div>
                                <div>
                                    <label for="domicilio_departamento_id" class="block text-sm">Departamento</label>
                                    <select id="domicilio_departamento_id" name="domicilio[departamento_id]" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                        <option value="">Seleccione…</option>
                                        @foreach($departamentos as $d)
                                            <option value="{{ $d->id }}" @selected(old('domicilio.departamento_id')==$d->id)>{{ $d->nombre }}</option>
                                        @endforeach
                                    </select>
                                    @error('domicilio.departamento_id')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                                </div>
                                <div>
                                    <label for="dom_calle" class="block text-sm">Calle *</label>
                                    <input id="dom_calle" type="text" name="domicilio[calle]" value="{{ old('domicilio.calle') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                    @error('domicilio.calle')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                                </div>
                                <div>
                                    <label for="dom_numero" class="block text-sm">Número</label>
                                    <input id="dom_numero" type="number" name="domicilio[numero]" value="{{ old('domicilio.numero') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                    @error('domicilio.numero')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                                </div>
                                <div>
                                    <label for="dom_piso" class="block text-sm">Piso</label>
                                    <input id="dom_piso" type="text" name="domicilio[piso]" value="{{ old('domicilio.piso') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                </div>
                                <div>
                                    <label for="dom_depto" class="block text-sm">Depto</label>
                                    <input id="dom_depto" type="text" name="domicilio[depto]" value="{{ old('domicilio.depto') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                </div>
                                <div>
                                    <label for="dom_torre" class="block text-sm">Torre</label>
                                    <input id="dom_torre" type="text" name="domicilio[torre]" value="{{ old('domicilio.torre') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                </div>
                                <div>
                                    <label for="dom_monoblock" class="block text-sm">Monoblock</label>
                                    <input id="dom_monoblock" type="text" name="domicilio[monoblock]" value="{{ old('domicilio.monoblock') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                </div>
                                <div>
                                    <label for="dom_manzana" class="block text-sm">Manzana</label>
                                    <input id="dom_manzana" type="text" name="domicilio[manzana]" value="{{ old('domicilio.manzana') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                </div>
                                <div>
                                    <label for="dom_lote" class="block text-sm">Lote</label>
                                    <input id="dom_lote" type="text" name="domicilio[lote]" value="{{ old('domicilio.lote') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                </div>
                                <div>
                                    <label for="dom_casa" class="block text-sm">Casa</label>
                                    <input id="dom_casa" type="text" name="domicilio[casa]" value="{{ old('domicilio.casa') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                </div>
                                <div>
                                    <label for="dom_barrio" class="block text-sm">Barrio</label>
                                    <input id="dom_barrio" type="text" name="domicilio[barrio]" value="{{ old('domicilio.barrio') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                    @error('domicilio.barrio')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                                </div>
                                <div>
                                    <label for="dom_sector" class="block text-sm">Sector</label>
                                    <input id="dom_sector" type="text" name="domicilio[sector]" value="{{ old('domicilio.sector') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                </div>
                                <div class="md:col-span-2">
                                    <label for="dom_coordenadas_gps" class="block text-sm">Coordenadas GPS (lat,long)</label>
                                    <input id="dom_coordenadas_gps" type="text" name="domicilio[coordenadas_gps]" value="{{ old('domicilio.coordenadas_gps') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" placeholder="-31.5,-68.5">
                                    <p class="text-xs text-gray-500 mt-1">Haz clic en el mapa para completar este campo automáticamente.</p>
                                </div>
                                <div class="md:col-span-2">
                                    <div id="map_persona" class="w-full h-64 rounded border relative z-0"></div>
                                </div>
                            </div>

                            <hr class="my-6">
                            <h4 class="text-lg font-bold text-gray-800">Datos de Vinculación</h4>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-2">
                                <div>
                                    <label for="pivot_situacion_procesal" class="block text-sm">Situación procesal</label>
                                    <select id="pivot_situacion_procesal" name="pivot[situacion_procesal]" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                        <option value="">-- Seleccione --</option>
                                        <option value="NO HALLADO">NO HALLADO</option>
                                        <option value="DETENIDO">DETENIDO</option>
                                        <option value="APREHENDIDO">APREHENDIDO</option>
                                        <option value="NOTIFICADO">NOTIFICADO</option>
                                    </select>
                                </div>
                                <div id="contenedor_pedido_captura" class="flex items-center mt-6">
                                    <input id="pivot_pedido_captura" type="checkbox" name="pivot[pedido_captura]" class="rounded border-gray-300">
                                    <label for="pivot_pedido_captura" class="ml-2 text-sm">Pedido de captura</label>
                                </div>
                                <div class="md:col-span-1 md:col-start-1 md:col-end-4">
                                    <label for="pivot_observaciones" class="block text-sm">Observaciones (Vinculación)</label>
                                    <textarea id="pivot_observaciones" name="pivot[observaciones]" rows="2" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">{{ old('pivot.observaciones') }}</textarea>
                                </div>
                            </div>

                                <div class="flex flex-col md:flex-row md:justify-between gap-2 mt-6">
                                <button type="button" onclick="showTab('legales')" class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-2 px-4 rounded w-full md:w-auto">Anterior</button>
                                <div class="flex flex-col md:flex-row gap-2 w-full md:w-auto">
                                    {{-- Referencia antigua deshabilitada: route('carga.vincular') --}}
                                    <button type="button" formaction="#" disabled class="bg-blue-400 text-white font-semibold py-2 px-4 rounded w-full md:w-auto opacity-60 cursor-not-allowed" title="Función deshabilitada">Cargar Vinculado</button>
                                    <button type="button" onclick="nextTab('vinculados','ubicacion')" class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 px-4 rounded w-full md:w-auto">Siguiente</button>
                                </div>
                            </div>

                            @if(isset($procedimiento) && $procedimiento)
                                <div class="mt-6">
                                    <h4 class="text-lg font-bold text-gray-800 mb-2">Vinculados cargados</h4>
                                    @if(($vinculados ?? collect())->isEmpty())
                                        <p class="text-sm text-gray-500">Todavía no hay vinculados para este procedimiento.</p>
                                    @else
                                        <div class="overflow-x-auto">
                                            <table class="min-w-full divide-y divide-gray-200 text-sm">
                                                <thead class="bg-gray-50">
                                                    <tr>
                                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">DNI</th>
                                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Apellidos</th>
                                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombres</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="bg-white divide-y divide-gray-200">
                                                    @foreach($vinculados as $v)
                                                        <tr>
                                                            <td class="px-4 py-2">{{ $v->dni }}</td>
                                                            <td class="px-4 py-2">{{ $v->apellidos }}</td>
                                                            <td class="px-4 py-2">{{ $v->nombres }}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @endif
                                </div>
                            @endif
                        </div>

                        <!-- Tab 3: Domicilios del Hecho y Mapa -->
                        <div id="tab_ubicacion" {{ $initialTab==='ubicacion' ? '' : 'hidden' }}>
                            <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="hecho_provincia_id" class="block text-sm">Provincia *</label>
                                    <select id="hecho_provincia_id" name="hecho[provincia_id]" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                        <option value="">Seleccione…</option>
                                        @foreach($provincias as $p)
                                            <option value="{{ $p->id }}" @selected(old('hecho.provincia_id', $sanJuanId)==$p->id)>{{ $p->nombre }}</option>
                                        @endforeach
                                    </select>
                                    @error('hecho.provincia_id')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                                </div>
                                <div>
                                    <label for="hecho_departamento_id" class="block text-sm">Departamento</label>
                                    <select id="hecho_departamento_id" name="hecho[departamento_id]" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                        <option value="">Seleccione…</option>
                                        @foreach($departamentos as $d)
                                            <option value="{{ $d->id }}" @selected(old('hecho.departamento_id')==$d->id)>{{ $d->nombre }}</option>
                                        @endforeach
                                    </select>
                                    @error('hecho.departamento_id')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                                </div>
                                <div>
                                    <label for="hecho_calle" class="block text-sm">Calle *</label>
                                    <input id="hecho_calle" type="text" name="hecho[calle]" value="{{ old('hecho.calle') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                    @error('hecho.calle')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                                </div>
                                <div>
                                    <label for="hecho_numero" class="block text-sm">Número</label>
                                    <input id="hecho_numero" type="number" name="hecho[numero]" value="{{ old('hecho.numero') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                    @error('hecho.numero')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                                </div>
                                <div>
                                    <label for="hecho_piso" class="block text-sm">Piso</label>
                                    <input id="hecho_piso" type="text" name="hecho[piso]" value="{{ old('hecho.piso') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                </div>
                                <div>
                                    <label for="hecho_depto" class="block text-sm">Depto</label>
                                    <input id="hecho_depto" type="text" name="hecho[depto]" value="{{ old('hecho.depto') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                </div>
                                <div>
                                    <label for="hecho_torre" class="block text-sm">Torre</label>
                                    <input id="hecho_torre" type="text" name="hecho[torre]" value="{{ old('hecho.torre') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                </div>
                                <div>
                                    <label for="hecho_monoblock" class="block text-sm">Monoblock</label>
                                    <input id="hecho_monoblock" type="text" name="hecho[monoblock]" value="{{ old('hecho.monoblock') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                </div>
                                <div>
                                    <label for="hecho_manzana" class="block text-sm">Manzana</label>
                                    <input id="hecho_manzana" type="text" name="hecho[manzana]" value="{{ old('hecho.manzana') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                </div>
                                <div>
                                    <label for="hecho_lote" class="block text-sm">Lote</label>
                                    <input id="hecho_lote" type="text" name="hecho[lote]" value="{{ old('hecho.lote') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                </div>
                                <div>
                                    <label for="hecho_casa" class="block text-sm">Casa</label>
                                    <input id="hecho_casa" type="text" name="hecho[casa]" value="{{ old('hecho.casa') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                </div>
                                <div>
                                    <label for="hecho_barrio" class="block text-sm">Barrio</label>
                                    <input id="hecho_barrio" type="text" name="hecho[barrio]" value="{{ old('hecho.barrio') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                    @error('hecho.barrio')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                                </div>
                                <div>
                                    <label for="hecho_sector" class="block text-sm">Sector</label>
                                    <input id="hecho_sector" type="text" name="hecho[sector]" value="{{ old('hecho.sector') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                </div>
                                <div class="md:col-span-2">
                                    <label for="hecho_coordenadas_gps" class="block text-sm">Coordenadas GPS (lat,long)</label>
                                    <input id="hecho_coordenadas_gps" type="text" name="hecho[coordenadas_gps]" value="{{ old('hecho.coordenadas_gps') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                    <p class="text-xs text-gray-500 mt-1">Haz clic en el mapa para completar este campo automáticamente.</p>
                                </div>
                            </div>

                            <div class="mt-6">
                                <h4 class="text-lg font-bold text-gray-800 mb-2">Mapa</h4>
                                <div id="map" class="w-full h-80 rounded border relative z-0"></div>
                            </div>

                            <div class="flex flex-col md:flex-row md:justify-between gap-2 mt-6">
                                <button type="button" onclick="showTab('vinculados')" class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-2 px-4 rounded w-full md:w-auto">Anterior</button>
                                <div class="flex flex-col md:flex-row gap-2 w-full md:w-auto">
                                    {{-- Referencia antigua deshabilitada: route('carga.vincularDomicilio') --}}
                                    <button type="button" formaction="#" disabled class="bg-blue-400 text-white font-semibold py-2 px-4 rounded w-full md:w-auto opacity-60 cursor-not-allowed" title="Función deshabilitada">Cargar Domicilio</button>
                                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded w-full md:w-auto">Finalizar Carga</button>
                                </div>
                            </div>

                            @if(isset($procedimiento) && $procedimiento)
                                <div class="mt-6">
                                    <h4 class="text-lg font-bold text-gray-800 mb-2">Domicilios del hecho cargados</h4>
                                    @php($list = ($domiciliosHecho ?? ($procedimiento->domicilios ?? collect())))
                                    @if($list->isEmpty())
                                        <p class="text-sm text-gray-500">Todavía no hay domicilios cargados.</p>
                                    @else
                                        <div class="space-y-2">
                                            @foreach($list as $d)
                                                <div class="border rounded p-3 text-sm">
                                                    <div><span class="font-medium">Dirección:</span> {{ $d->calle }} {{ $d->numero }} {{ $d->barrio ? ' - '.$d->barrio : '' }}</div>
                                                    <div class="text-gray-600">{{ $d->provincia->nombre ?? '' }} {{ $d->departamento->nombre ? ' - '.$d->departamento->nombre : '' }}</div>
                                                    @if($d->coordenadas_gps)
                                                        <div class="text-gray-600">GPS: {{ $d->coordenadas_gps }}</div>
                                                    @endif
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            @endif
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Leaflet CSS/JS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

    <script>
        // Estado simple de pestañas y funciones utilitarias (Vanilla JS)
        let currentTab = '{{ $initialTab }}' || 'legales';
        let map = null;
        let marker = null;
        let personaMap = null;
        let personaMarker = null;

        function setActiveTabButton(tab) {
            const activeCls = 'border-indigo-500 text-indigo-600';
            const inactiveCls = 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300';
            const btns = {
                legales: document.getElementById('tabbtn-legales'),
                vinculados: document.getElementById('tabbtn-vinculados'),
                ubicacion: document.getElementById('tabbtn-ubicacion'),
            };
            Object.entries(btns).forEach(([key, el]) => {
                if (!el) return;
                el.classList.remove('border-indigo-500','text-indigo-600','border-transparent','text-gray-500','hover:text-gray-700','hover:border-gray-300');
                if (key === tab) {
                    el.classList.add('border-indigo-500','text-indigo-600');
                } else {
                    el.classList.add('border-transparent','text-gray-500','hover:text-gray-700','hover:border-gray-300');
                }
            });
        }

        function showTab(tab) {
            const sections = {
                legales: document.getElementById('tab_legales'),
                vinculados: document.getElementById('tab_vinculados'),
                ubicacion: document.getElementById('tab_ubicacion'),
            };
            Object.entries(sections).forEach(([key, el]) => {
                if (!el) return;
                el.hidden = (key !== tab);
            });
            currentTab = tab;
            setActiveTabButton(tab);
            if (tab === 'ubicacion') {
                ensureMap();
            }
        }

        function validateSection(tabId) {
            const container = document.getElementById(tabId);
            if (!container) return true;
            const requiredFields = container.querySelectorAll('[required]');
            for (const field of requiredFields) {
                // Solo validar campos visibles y habilitados
                if (field.offsetParent !== null && !field.disabled) {
                   if (!field.checkValidity()) {
                       field.reportValidity(); // Muestra el mensaje de error del navegador
                       return false;
                   }
                }
            }
            return true;
        }

        function nextTab(current, next) {
            if (validateSection('tab_' + current)) { // Validar la pestaña actual antes de avanzar
                showTab(next);
            }
        }

        function ensureMap() {
            if (!window.L) return;
            if (!map) {
                initMap();
            } else {
                setTimeout(() => { try { map.invalidateSize(); } catch(e){} }, 0);
            }
        }

        function initMap() {
            if (!window.L || map) return;
            map = L.map('map').setView([-34.6037, -58.3816], 12);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; OpenStreetMap'
            }).addTo(map);
            map.on('click', (e) => {
                const { lat, lng } = e.latlng;
                setLatLng(lat, lng);
            });
        }

        function setLatLng(lat, lng) {
            if (marker) {
                marker.setLatLng([lat, lng]);
            } else if (map) {
                marker = L.marker([lat, lng]).addTo(map);
            }
            const el = document.getElementById('hecho_coordenadas_gps');
            if (el) {
                el.value = `${lat.toFixed(6)},${lng.toFixed(6)}`;
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Inicializar pestaña activa y header
            showTab(currentTab || 'legales');
            // Inicializar mapa de persona cuando se muestra pestaña vinculados
            if (currentTab === 'vinculados') { ensurePersonaMap(); }
        });
    </script>

    @push('scripts')
    <script>
        // --- Restricción de solo números para DNI ---
        document.addEventListener('DOMContentLoaded', function () {
            const dniEl = document.getElementById('dni');
            if (!dniEl) return;
            const filterDigits = () => {
                dniEl.value = (dniEl.value || '').replace(/\D/g, '').slice(0, 8);
            };
            dniEl.addEventListener('input', filterDigits);
            dniEl.addEventListener('paste', () => setTimeout(filterDigits, 0));
        });

        // Valor global reutilizable para ambas pestañas
        const sanJuanId = "{{ $sanJuanId ?? '' }}";

        // --- Lógica para Pestaña 2: Domicilio Principal ---
        document.addEventListener('DOMContentLoaded', function () {
            const provinciaSelect = document.getElementById('domicilio_provincia_id');
            const departamentoSelect = document.getElementById('domicilio_departamento_id');

            function toggleDepartamento() {
                if (!provinciaSelect || !departamentoSelect) return;
                if (provinciaSelect.value === sanJuanId) {
                    departamentoSelect.disabled = false;
                    departamentoSelect.classList.remove('bg-gray-200');
                } else {
                    departamentoSelect.disabled = true;
                    departamentoSelect.value = '';
                    departamentoSelect.classList.add('bg-gray-200');
                }
            }

            toggleDepartamento();
            provinciaSelect?.addEventListener('change', toggleDepartamento);
        });

        // --- Mapa para Domicilio Principal (Pestaña 2) ---
        function ensurePersonaMap(){
            if (!window.L) return;
            if (!personaMap) initPersonaMap();
            else setTimeout(()=>{ try { personaMap.invalidateSize(); } catch(e){} }, 0);
        }
        function initPersonaMap(){
            const div = document.getElementById('map_persona');
            if (!div || personaMap) return;
            personaMap = L.map(div).setView([-31.534, -68.525], 12);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; OpenStreetMap'
            }).addTo(personaMap);
            const inputCoord = document.getElementById('dom_coordenadas_gps');
            personaMap.on('click', (e)=> {
                const { lat, lng } = e.latlng;
                if (personaMarker) personaMarker.setLatLng([lat,lng]);
                else personaMarker = L.marker([lat,lng]).addTo(personaMap);
                if (inputCoord) inputCoord.value = `${lat.toFixed(6)},${lng.toFixed(6)}`;
            });
            // Si ya hay old() coords, centrar
            if (inputCoord && inputCoord.value && inputCoord.value.includes(',')){
                const parts = inputCoord.value.split(',').map(s=>parseFloat(s.trim()));
                if (parts.length===2 && !isNaN(parts[0]) && !isNaN(parts[1])){
                    personaMap.setView({lat: parts[0], lng: parts[1]}, 14);
                    personaMarker = L.marker([parts[0], parts[1]]).addTo(personaMap);
                }
            }
        }

        // --- Lógica para Pestaña 3: Domicilios del Hecho ---
        document.addEventListener('DOMContentLoaded', function () {
            const hechoProvinciaSelect = document.getElementById('hecho_provincia_id');
            const hechoDepartamentoSelect = document.getElementById('hecho_departamento_id');

            if (hechoProvinciaSelect && hechoDepartamentoSelect) {
                function toggleHechoDepartamento() {
                    if (hechoProvinciaSelect.value === sanJuanId) {
                        hechoDepartamentoSelect.disabled = false;
                        hechoDepartamentoSelect.classList.remove('bg-gray-200');
                    } else {
                        hechoDepartamentoSelect.disabled = true;
                        hechoDepartamentoSelect.value = '';
                        hechoDepartamentoSelect.classList.add('bg-gray-200');
                    }
                }

                toggleHechoDepartamento();
                hechoProvinciaSelect.addEventListener('change', toggleHechoDepartamento);
            }
        });

        // --- Lógica para Pestaña 2: Lógica Condicional de Captura ---
        document.addEventListener('DOMContentLoaded', function () {
            const situacionSelect = document.getElementById('pivot_situacion_procesal');
            const capturaContenedor = document.getElementById('contenedor_pedido_captura');

            if (situacionSelect && capturaContenedor) {
                function togglePedidoCaptura() {
                    if (situacionSelect.value === 'NO HALLADO') {
                        capturaContenedor.style.display = 'flex';
                    } else {
                        capturaContenedor.style.display = 'none';
                        const capturaCheckbox = capturaContenedor.querySelector('input[type="checkbox"]');
                        if (capturaCheckbox) {
                            capturaCheckbox.checked = false;
                        }
                    }
                }
                togglePedidoCaptura();
                situacionSelect.addEventListener('change', togglePedidoCaptura);
            }
        });

        // Reaccionar al cambio de pestañas para inicializar mapas correctamente
        document.addEventListener('click', function(e){
            if (e.target && e.target.id === 'tabbtn-vinculados'){
                setTimeout(ensurePersonaMap, 50);
            }
            if (e.target && e.target.id === 'tabbtn-ubicacion'){
                setTimeout(ensureMap, 50);
            }
        });
    </script>
    @endpush
</x-app-layout>
