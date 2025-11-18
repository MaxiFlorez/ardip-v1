@php
    $errorsPaso1 = $errors->hasAny(['legajo_fiscal','caratula','fecha_procedimiento','hora_procedimiento','brigada_id']);
    $errorsPaso2 = $errors->hasAny(['dni','nombres','apellidos','fecha_nacimiento','genero','foto','observaciones_persona','domicilio_legal.calle','domicilio_legal.numero','domicilio_legal.provincia_id','domicilio_legal.departamento_id']);
    $errorsPaso3 = $errors->hasAny(['domicilios_allanados.*.calle_allanada','domicilios_allanados.*.latitud','domicilios_allanados.*.longitud']);
    $initialTab = $errorsPaso2 ? 'vinculados' : ($errorsPaso3 ? 'ubicacion' : 'legales');
@endphp

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Editar Procedimiento (Asistente)
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900" x-data="cargaAsistente()" x-init="init('{{ $initialTab }}')">

                    @if ($errors->any())
                        <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded">
                            <ul class="list-disc list-inside text-sm text-red-700">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('procedimientos.update', $procedimiento) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- Tabs Header -->
                        <div class="border-b mb-6">
                            <nav class="-mb-px flex space-x-6" aria-label="Tabs">
                                <button type="button" @click="tab='legales'" :class="tab==='legales' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium">Datos Legales</button>
                                <button type="button" @click="tab='vinculados'" :class="tab==='vinculados' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium">Vinculados</button>
                                <button type="button" @click="tab='ubicacion'" :class="tab==='ubicacion' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium">Fecha, Hora y Ubicación</button>
                            </nav>
                        </div>

                        <!-- Tab 1: Datos Legales (Actuación) -->
                        <div x-show="tab==='legales'" x-cloak>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label for="legajo_fiscal" class="block text-sm">Legajo Fiscal *</label>
                                    <input id="legajo_fiscal" type="text" name="legajo_fiscal" value="{{ old('legajo_fiscal', $procedimiento->legajo_fiscal) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                                    @error('legajo_fiscal')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                                </div>
                                <div class="md:col-span-2">
                                    <label for="caratula" class="block text-sm">Carátula *</label>
                                    <textarea id="caratula" name="caratula" rows="2" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>{{ old('caratula', $procedimiento->caratula) }}</textarea>
                                    @error('caratula')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                                </div>
                                <div>
                                    <label for="fecha_procedimiento" class="block text-sm">Fecha Procedimiento *</label>
                                    <input id="fecha_procedimiento" type="date" name="fecha_procedimiento" value="{{ old('fecha_procedimiento', optional($procedimiento->fecha_procedimiento)->format('Y-m-d')) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                                    @error('fecha_procedimiento')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                                </div>
                                <div>
                                    <label for="hora_procedimiento" class="block text-sm">Hora Procedimiento</label>
                                    <input id="hora_procedimiento" type="time" name="hora_procedimiento" value="{{ old('hora_procedimiento', $procedimiento->hora_procedimiento ? (is_string($procedimiento->hora_procedimiento) ? substr($procedimiento->hora_procedimiento,0,5) : $procedimiento->hora_procedimiento->format('H:i')) : '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                    @error('hora_procedimiento')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                                </div>
                                <div>
                                    <label for="brigada_id" class="block text-sm">Brigada *</label>
                                    <select id="brigada_id" name="brigada_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                                        <option value="">Seleccione…</option>
                                        @foreach($brigadas as $b)
                                            <option value="{{ $b->id }}" @selected(old('brigada_id', $procedimiento->brigada_id)==$b->id)>{{ $b->nombre }}</option>
                                        @endforeach
                                    </select>
                                    @error('brigada_id')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                                </div>
                                <div>
                                    <label for="orden_judicial" class="block text-sm">Orden Judicial</label>
                                    <select id="orden_judicial" name="orden_judicial" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                        <option value="">Seleccione…</option>
                                        <option value="Detención en caso de secuestro positivo" @selected(old('orden_judicial', $procedimiento->orden_judicial)==='Detención en caso de secuestro positivo')>Detención en caso de secuestro positivo</option>
                                        <option value="Detención directa" @selected(old('orden_judicial', $procedimiento->orden_judicial)==='Detención directa')>Detención directa</option>
                                        <option value="Notificación al acusado" @selected(old('orden_judicial', $procedimiento->orden_judicial)==='Notificación al acusado')>Notificación al acusado</option>
                                        <option value="Secuestro y notificación" @selected(old('orden_judicial', $procedimiento->orden_judicial)==='Secuestro y notificación')>Secuestro y notificación</option>
                                    </select>
                                    @error('orden_judicial')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                                </div>
                            </div>

                            <div class="flex justify-end mt-6">
                                <button type="button" @click="tab='vinculados'" class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 px-4 rounded">Siguiente</button>
                            </div>
                        </div>

                        <!-- Tab 2: Vinculados (Persona + Domicilio Legal) -->
                        <div x-show="tab==='vinculados'" x-cloak
                             x-init="
                                (() => {
                                    const hasOld = {{ old('dni') || old('persona_id') ? 'true' : 'false' }};
                                    if (hasOld) return;
                                    @if($procedimiento->personas->count() > 0)
                                        cargarPersona({
                                            id: {{ $procedimiento->personas->first()->id }},
                                            dni: '{{ $procedimiento->personas->first()->dni }}',
                                            nombres: @js($procedimiento->personas->first()->nombres),
                                            apellidos: @js($procedimiento->personas->first()->apellidos),
                                            fecha_nacimiento: '{{ optional($procedimiento->personas->first()->fecha_nacimiento)->format('Y-m-d') }}',
                                            genero: '{{ $procedimiento->personas->first()->genero }}',
                                            alias: @js($procedimiento->personas->first()->alias),
                                            nacionalidad: @js($procedimiento->personas->first()->nacionalidad),
                                            estado_civil: '{{ $procedimiento->personas->first()->estado_civil }}',
                                            observaciones: @js($procedimiento->personas->first()->observaciones),
                                            domicilio: {
                                                provincia_id: {{ optional($procedimiento->personas->first()->domicilio)->provincia_id ?? 'null' }},
                                                departamento_id: {{ optional($procedimiento->personas->first()->domicilio)->departamento_id ?? 'null' }},
                                                calle: @js(optional($procedimiento->personas->first()->domicilio)->calle),
                                                numero: @js(optional($procedimiento->personas->first()->domicilio)->numero),
                                                barrio: @js(optional($procedimiento->personas->first()->domicilio)->barrio),
                                            }
                                        });
                                    @endif
                                })()
                             ">
                            <!-- Selector de personas ya vinculadas -->
                            <div class="mb-4">
                                <div class="flex items-center justify-between">
                                    <h4 class="text-md font-semibold">Personas vinculadas</h4>
                                    <button type="button" @click="limpiarPersona()" class="text-sm bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-1 px-3 rounded">+ Agregar Persona Nueva</button>
                                </div>
                                <div class="mt-2 flex gap-2 flex-wrap">
                                    @foreach($procedimiento->personas as $p)
                                        <button type="button"
                                            :class="personaSeleccionadaId === {{ $p->id }} ? 'text-xs border rounded px-2 py-1 bg-blue-100 ring-2 ring-blue-300' : 'text-xs border rounded px-2 py-1 hover:bg-gray-50'"
                                            @click="cargarPersona({
                                                id: {{ $p->id }},
                                                dni: '{{ $p->dni }}',
                                                nombres: @js($p->nombres),
                                                apellidos: @js($p->apellidos),
                                                fecha_nacimiento: '{{ optional($p->fecha_nacimiento)->format('Y-m-d') }}',
                                                genero: '{{ $p->genero }}',
                                                alias: @js($p->alias),
                                                nacionalidad: @js($p->nacionalidad),
                                                estado_civil: '{{ $p->estado_civil }}',
                                                observaciones: @js($p->observaciones),
                                                domicilio: {
                                                    provincia_id: {{ $p->domicilio->provincia_id ?? 'null' }},
                                                    departamento_id: {{ $p->domicilio->departamento_id ?? 'null' }},
                                                    calle: @js(optional($p->domicilio)->calle),
                                                    numero: @js(optional($p->domicilio)->numero),
                                                    barrio: @js(optional($p->domicilio)->barrio),
                                                }
                                            })">
                                            {{ $p->apellidos }}, {{ $p->nombres }} ({{ $p->dni }})
                                        </button>
                                    @endforeach
                                </div>
                            </div>

                            <input type="hidden" name="persona_id" x-ref="persona_id">

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label for="dni" class="block text-sm">DNI *</label>
                                    <input id="dni" type="text" name="dni" maxlength="8" x-ref="dni" value="{{ old('dni') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                    @error('dni')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                                </div>
                                <div>
                                    <label for="nombres" class="block text-sm">Nombres *</label>
                                    <input id="nombres" type="text" name="nombres" x-ref="nombres" value="{{ old('nombres') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                    @error('nombres')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                                </div>
                                <div>
                                    <label for="apellidos" class="block text-sm">Apellidos *</label>
                                    <input id="apellidos" type="text" name="apellidos" x-ref="apellidos" value="{{ old('apellidos') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                    @error('apellidos')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                                </div>
                                <div>
                                    <label for="fecha_nacimiento" class="block text-sm">Fecha Nacimiento *</label>
                                    <input id="fecha_nacimiento" type="date" name="fecha_nacimiento" x-ref="fecha_nacimiento" value="{{ old('fecha_nacimiento') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                    @error('fecha_nacimiento')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                                </div>
                                <div>
                                    <label for="genero" class="block text-sm">Género</label>
                                    <select id="genero" name="genero" x-ref="genero" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
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
                                <div class="md:col-span-3">
                                    <label for="observaciones_persona" class="block text-sm">Observaciones (Persona)</label>
                                    <textarea id="observaciones_persona" name="observaciones_persona" rows="2" x-ref="observaciones_persona" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">{{ old('observaciones_persona') }}</textarea>
                                    @error('observaciones_persona')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                                </div>
                            </div>

                            <h4 class="text-md font-semibold mt-6">Domicilio Legal</h4>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-2">
                                <div>
                                    <label class="block text-sm">Provincia *</label>
                                    <select name="domicilio_legal[provincia_id]" x-ref="dl_provincia_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                        <option value="">Seleccione…</option>
                                        @foreach($provincias as $p)
                                            <option value="{{ $p->id }}" @selected(old('domicilio_legal.provincia_id')==$p->id)>{{ $p->nombre }}</option>
                                        @endforeach
                                    </select>
                                    @error('domicilio_legal.provincia_id')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                                </div>
                                <div>
                                    <label class="block text-sm">Departamento</label>
                                    <select name="domicilio_legal[departamento_id]" x-ref="dl_departamento_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                        <option value="">Seleccione…</option>
                                        @foreach($departamentos as $d)
                                            <option value="{{ $d->id }}" @selected(old('domicilio_legal.departamento_id')==$d->id)>{{ $d->nombre }}</option>
                                        @endforeach
                                    </select>
                                    @error('domicilio_legal.departamento_id')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                                </div>
                                <div>
                                    <label class="block text-sm">Calle *</label>
                                    <input type="text" name="domicilio_legal[calle]" x-ref="dl_calle" value="{{ old('domicilio_legal.calle') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                    @error('domicilio_legal.calle')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                                </div>
                                <div>
                                    <label for="domicilio_legal_provincia_id" class="block text-sm">Provincia *</label>
                                    <select id="domicilio_legal_provincia_id" name="domicilio_legal[provincia_id]" x-ref="dl_provincia_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                    @error('domicilio_legal.numero')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                                </div>
                                <div>
                                    <label class="block text-sm">Barrio</label>
                                    <input type="text" name="domicilio_legal[barrio]" x-ref="dl_barrio" value="{{ old('domicilio_legal.barrio') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                    @error('domicilio_legal.barrio')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                                </div>
                                <div>
                                    <label for="domicilio_legal_departamento_id" class="block text-sm">Departamento</label>
                                    <select id="domicilio_legal_departamento_id" name="domicilio_legal[departamento_id]" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                <button type="button" @click="tab='legales'" class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-2 px-4 rounded">Anterior</button>
                                <button type="button" @click="tab='ubicacion'" class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 px-4 rounded">Siguiente</button>
                            </div>
                        </div>

                        <!-- Tab 3: Fecha, Hora y Ubicación (Domicilios Allanados + Mapa) -->
                        <div x-show="tab==='ubicacion'" x-cloak x-init="$watch('tab', (v) => { if (v==='ubicacion') $nextTick(()=> ensureMap()); }); if (tab==='ubicacion') $nextTick(()=> ensureMap());">
                                <div>
                                    <label for="domicilio_legal_calle" class="block text-sm">Calle *</label>
                                    <input id="domicilio_legal_calle" type="text" name="domicilio_legal[calle]" value="{{ old('domicilio_legal.calle') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                                    <h4 class="text-md font-semibold">Domicilios Allanados</h4>
                                    <button type="button" @click="agregarDomicilio()" class="text-sm bg-green-600 hover:bg-green-700 text-white font-semibold py-1 px-3 rounded">+ Agregar otro domicilio</button>
                                <div>
                                    <label for="domicilio_legal_numero" class="block text-sm">Número</label>
                                    <input id="domicilio_legal_numero" type="text" name="domicilio_legal[numero]" value="{{ old('domicilio_legal.numero') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                    <div class="border rounded p-3 mb-3">
                                        <div class="flex items-center justify-between mb-2">
                                <div>
                                    <label for="domicilio_legal_barrio" class="block text-sm">Barrio</label>
                                    <input id="domicilio_legal_barrio" type="text" name="domicilio_legal[barrio]" value="{{ old('domicilio_legal.barrio') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                                    <input type="radio" name="domicilio_seleccionado" :value="idx" x-model.number="domicilioSeleccionado">
                                                    Usar mapa para este
                                                </label>
                                                <button type="button" @click="quitarDomicilio(idx)" class="text-xs text-red-600 hover:text-red-800">Quitar</button>
                                            </div>
                                        </div>

                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                            <input type="hidden" :name="`domicilios_allanados[${idx}][id]`" x-model="dom.id">
                                            <div>
                                                <label class="block text-xs" :for="`da_${idx}_calle`">Calle</label>
                                                <input :id="`da_${idx}_calle`" type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" :name="`domicilios_allanados[${idx}][calle_allanada]`" x-model="dom.calle_allanada">
                                            </div>
                                            <div>
                                                <label class="block text-xs" :for="`da_${idx}_numero`">Número</label>
                                                <input :id="`da_${idx}_numero`" type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" :name="`domicilios_allanados[${idx}][numero_allanada]`" x-model="dom.numero_allanada">
                                            </div>
                                            <div>
                                                <label class="block text-xs" :for="`da_${idx}_monoblock`">Monoblock</label>
                                                <input :id="`da_${idx}_monoblock`" type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" :name="`domicilios_allanados[${idx}][monoblock]`" x-model="dom.monoblock">
                                            </div>
                                            <div>
                                                <label class="block text-xs" :for="`da_${idx}_torre`">Torre</label>
                                                <input :id="`da_${idx}_torre`" type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" :name="`domicilios_allanados[${idx}][torre]`" x-model="dom.torre">
                                            </div>
                                            <div>
                                                <label class="block text-xs" :for="`da_${idx}_piso`">Piso</label>
                                                <input :id="`da_${idx}_piso`" type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" :name="`domicilios_allanados[${idx}][piso]`" x-model="dom.piso">
                                            </div>
                                            <div>
                                                <label class="block text-xs" :for="`da_${idx}_depto`">Depto</label>
                                                <input :id="`da_${idx}_depto`" type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" :name="`domicilios_allanados[${idx}][depto]`" x-model="dom.depto">
                                            </div>
                                            <div>
                                                <label class="block text-xs" :for="`da_${idx}_provincia_id`">Provincia</label>
                                                <select :id="`da_${idx}_provincia_id`" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" :name="`domicilios_allanados[${idx}][provincia_id]`" x-model="dom.provincia_id">
                                                    <option value="">Seleccione…</option>
                                                    @foreach($provincias as $p)
                                                        <option value="{{ $p->id }}">{{ $p->nombre }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div>
                                                <label class="block text-xs" :for="`da_${idx}_departamento_id`">Departamento</label>
                                                <select :id="`da_${idx}_departamento_id`" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" :name="`domicilios_allanados[${idx}][departamento_id]`" x-model="dom.departamento_id">
                                                    <option value="">Seleccione…</option>
                                                    @foreach($departamentos as $d)
                                                        <option value="{{ $d->id }}">{{ $d->nombre }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div>
                                                <label class="block text-xs" :for="`da_${idx}_sector`">Sector</label>
                                                <input :id="`da_${idx}_sector`" type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" :name="`domicilios_allanados[${idx}][sector]`" x-model="dom.sector">
                                            </div>
                                            <div>
                                                <label class="block text-xs" :for="`da_${idx}_manzana`">Manzana</label>
                                                <input :id="`da_${idx}_manzana`" type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" :name="`domicilios_allanados[${idx}][manzana]`" x-model="dom.manzana">
                                            </div>
                                            <div>
                                                <label class="block text-xs" :for="`da_${idx}_lote`">Lote</label>
                                                <input :id="`da_${idx}_lote`" type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" :name="`domicilios_allanados[${idx}][lote]`" x-model="dom.lote">
                                            </div>
                                            <div>
                                                <label class="block text-xs" :for="`da_${idx}_casa`">Casa</label>
                                                <input :id="`da_${idx}_casa`" type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" :name="`domicilios_allanados[${idx}][casa]`" x-model="dom.casa">
                                            </div>
                                            <div>
                                                <label class="block text-xs" :for="`da_${idx}_latitud`">Latitud</label>
                                                <input :id="`da_${idx}_latitud`" type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" :name="`domicilios_allanados[${idx}][latitud]`" x-model="dom.latitud">
                                            </div>
                                            <div>
                                                <label class="block text-xs" :for="`da_${idx}_longitud`">Longitud</label>
                                                <input :id="`da_${idx}_longitud`" type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" :name="`domicilios_allanados[${idx}][longitud]`" x-model="dom.longitud">
                                            </div>
                                        </div>
                                    </div>
                                </template>
                                <template x-if="domicilios.length === 0">
                                    <p class="text-sm text-gray-500">No hay domicilios agregados. Usa “+ Agregar otro domicilio”.</p>
                                </template>

                                <!-- Lista de domicilios ya vinculados -->
                                <div class="mt-4">
                                    <h5 class="text-sm font-semibold mb-1">Seleccionar domicilio existente</h5>
                                    <div class="flex flex-col gap-1">
                                        @foreach($procedimiento->domicilios as $d)
                                            @php
                                                $lat = null; $lng = null;
                                                if (!empty($d->coordenadas_gps) && str_contains($d->coordenadas_gps, ',')) {
                                                    [$lat, $lng] = array_map('trim', explode(',', $d->coordenadas_gps));
                                                }
                                            @endphp
                                            <button type="button" class="text-xs text-left border rounded px-2 py-1 hover:bg-gray-50"
                                                @click="cargarDomicilioExistente({
                                                    id: {{ $d->id }},
                                                    calle_allanada: @js($d->calle),
                                                    numero_allanada: @js($d->numero),
                                                    provincia_id: {{ $d->provincia_id ?? 'null' }},
                                                    departamento_id: {{ $d->departamento_id ?? 'null' }},
                                                    monoblock: @js($d->monoblock),
                                                    torre: @js($d->torre),
                                                    piso: @js($d->piso),
                                                    depto: @js($d->depto),
                                                    sector: @js($d->sector),
                                                    manzana: @js($d->manzana),
                                                    lote: @js($d->lote),
                                                    casa: @js($d->casa),
                                                    latitud: {{ $lat ? "'$lat'" : 'null' }},
                                                    longitud: {{ $lng ? "'$lng'" : 'null' }}
                                                })">
                                                {{ $d->direccion_completa }}
                                            </button>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <!-- Mapa abajo -->
                            <div class="mt-6">
                                <h4 class="text-md font-semibold mb-2">Mapa</h4>
                                <div id="map" class="w-full h-80 rounded border relative z-0"></div>
                                <p class="text-xs text-gray-500 mt-2">Haz clic en el mapa para completar lat/long del domicilio seleccionado.</p>
                            </div>

                            <div class="flex justify-between mt-6">
                                <a href="{{ route('procedimientos.show', $procedimiento) }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-2 px-4 rounded">Cancelar</a>
                                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded">Guardar Cambios</button>
                            </div>
                        </div>

                        <!-- Personas Vinculadas (Edición de datos pivote) -->
                        <div class="mt-8 border-t pt-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Personas Vinculadas</h3>
                            @if($procedimiento->personas->count() === 0)
                                <p class="text-sm text-gray-500">Este procedimiento no tiene personas vinculadas.</p>
                            @else
                                <div class="space-y-4">
                                    @foreach($procedimiento->personas as $p)
                                        <div class="border rounded-lg p-4">
                                            <div class="mb-3">
                                                <p class="text-sm text-gray-600">DNI: <span class="font-semibold text-gray-900">{{ $p->dni }}</span></p>
                                                <p class="text-sm text-gray-600">Persona: <span class="font-semibold text-gray-900">{{ $p->apellidos }}, {{ $p->nombres }}</span></p>
                                            </div>
                                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                                <div>
                                                    <label for="pivot_situacion_{{ $p->id }}" class="block text-sm">Situación Procesal</label>
                                                    @php
                                                        $opciones = ['NO HALLADO', 'DETENIDO', 'APREHENDIDO', 'NOTIFICADO'];
                                                        $situacionActual = $p->pivot->situacion_procesal;
                                                    @endphp
                                                    <select name="personas[{{ $p->id }}][situacion_procesal]" id="pivot_situacion_{{ $p->id }}" data-persona-id="{{ $p->id }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm pivot-situacion">
                                                        @foreach ($opciones as $opcion)
                                                            <option value="{{ $opcion }}" @selected($opcion === $situacionActual)>{{ $opcion }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div id="contenedor_pedido_captura_{{ $p->id }}" class="flex items-center mt-6">
                                                    <input type="hidden" name="personas[{{ $p->id }}][pedido_captura]" value="0">
                                                    <input type="checkbox" id="pivot_captura_{{ $p->id }}" name="personas[{{ $p->id }}][pedido_captura]" value="1" class="rounded border-gray-300" @checked($p->pivot->pedido_captura ?? false)>
                                                    <label for="pivot_captura_{{ $p->id }}" class="ml-2 text-sm">Pedido de Captura</label>
                                                </div>
                                                <div class="md:col-span-1 md:col-start-1 md:col-end-4">
                                                    <label for="pivot_observaciones_{{ $p->id }}" class="block text-sm">Observaciones (Pivote)</label>
                                                    <textarea name="personas[{{ $p->id }}][observaciones]" id="pivot_observaciones_{{ $p->id }}" rows="2" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">{{ $p->pivot->observaciones }}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Leaflet CSS/JS -->
    @php
        $initialDoms = $procedimiento->domicilios->map(function($d){
            $lat = $lng = null;
            if (!empty($d->coordenadas_gps) && str_contains($d->coordenadas_gps, ',')) {
                [$lat, $lng] = array_map('trim', explode(',', $d->coordenadas_gps));
            }
            return [
                'id' => $d->id,
                'calle_allanada' => $d->calle,
                'numero_allanada' => $d->numero,
                'provincia_id' => $d->provincia_id,
                'departamento_id' => $d->departamento_id,
                'monoblock' => $d->monoblock,
                'torre' => $d->torre,
                'piso' => $d->piso,
                'depto' => $d->depto,
                'sector' => $d->sector,
                'manzana' => $d->manzana,
                'lote' => $d->lote,
                'casa' => $d->casa,
                'latitud' => $lat,
                'longitud' => $lng,
            ];
        })->values();
    @endphp
    <script id="initial-domicilios" type="application/json">{!! json_encode($initialDoms) !!}</script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

    <script>
        function cargaAsistente() {
            return {
                tab: 'legales',
                personas: [],
                personaSeleccionadaId: null,
                domicilios: [],
                domicilioSeleccionado: 0,
                map: null,
                marker: null,

                init(initialTab) {
                    this.tab = initialTab || 'legales';
                    // Precargar personas desde el servidor (solo metadatos necesarios)
                    this.personas = [];
                    // Precargar domicilios existentes desde el bloque JSON embebido
                    try {
                        const el = document.getElementById('initial-domicilios');
                        if (el && el.textContent) {
                            this.domicilios = JSON.parse(el.textContent);
                        }
                    } catch (e) { this.domicilios = []; }
                    if (this.domicilios.length === 0) {
                        this.domicilios = [{ id: null, calle_allanada: '', numero_allanada: '', provincia_id: '', departamento_id: '', monoblock: '', torre: '', piso: '', depto: '', sector: '', manzana: '', lote: '', casa: '', latitud: '', longitud: '' }];
                    }
                    this.domicilioSeleccionado = 0;
                    // Inicializar mapa solo cuando la pestaña esté visible
                    this.$watch('tab', (v) => {
                        if (v === 'ubicacion') {
                            this.$nextTick(() => { this.ensureMap(); });
                        }
                    });
                    if (this.tab === 'ubicacion') {
                        this.$nextTick(() => { this.ensureMap(); });
                    }
                },

                ensureMap() {
                    if (!window.L) return;
                    if (!this.map) {
                        this.initMap();
                    } else {
                        setTimeout(() => { try { this.map.invalidateSize(); } catch(e){} }, 0);
                    }
                },

                initMap() {
                    if (!window.L || this.map) return;
                    this.map = L.map('map').setView([-34.6037, -58.3816], 12);
                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        maxZoom: 19,
                        attribution: '&copy; OpenStreetMap'
                    }).addTo(this.map);

                    this.map.on('click', (e) => {
                        const { lat, lng } = e.latlng;
                        this.setLatLng(lat, lng);
                    });
                },

                setLatLng(lat, lng) {
                    if (this.marker) {
                        this.marker.setLatLng([lat, lng]);
                    } else if (this.map) {
                        this.marker = L.marker([lat, lng]).addTo(this.map);
                    }

                    const idx = this.domicilioSeleccionado ?? 0;
                    if (this.domicilios[idx]) {
                        this.domicilios[idx].latitud = lat.toFixed(6);
                        this.domicilios[idx].longitud = lng.toFixed(6);
                    }
                },

                agregarDomicilio() {
                    this.domicilios.push({ id: null, calle_allanada: '', numero_allanada: '', provincia_id: '', departamento_id: '', monoblock: '', torre: '', piso: '', depto: '', sector: '', manzana: '', lote: '', casa: '', latitud: '', longitud: '' });
                    this.domicilioSeleccionado = this.domicilios.length - 1;
                },

                quitarDomicilio(index) {
                    this.domicilios.splice(index, 1);
                    if (this.domicilios.length === 0) {
                        this.domicilios.push({ id: null, calle_allanada: '', numero_allanada: '', provincia_id: '', departamento_id: '', monoblock: '', torre: '', piso: '', depto: '', sector: '', manzana: '', lote: '', casa: '', latitud: '', longitud: '' });
                    }
                    this.domicilioSeleccionado = Math.max(0, this.domicilios.length - 1);
                },

                cargarPersona(p) {
                    if (this.$refs.persona_id) this.$refs.persona_id.value = p.id || '';
                    if (this.$refs.dni) this.$refs.dni.value = p.dni || '';
                    if (this.$refs.nombres) this.$refs.nombres.value = p.nombres || '';
                    if (this.$refs.apellidos) this.$refs.apellidos.value = p.apellidos || '';
                    if (this.$refs.fecha_nacimiento) this.$refs.fecha_nacimiento.value = p.fecha_nacimiento || '';
                    if (this.$refs.genero) this.$refs.genero.value = p.genero || '';
                    if (this.$refs.observaciones_persona) this.$refs.observaciones_persona.value = p.observaciones || '';
                    if (p.domicilio) {
                        if (this.$refs.dl_provincia_id) this.$refs.dl_provincia_id.value = p.domicilio.provincia_id || '';
                        if (this.$refs.dl_departamento_id) this.$refs.dl_departamento_id.value = p.domicilio.departamento_id || '';
                        if (this.$refs.dl_calle) this.$refs.dl_calle.value = p.domicilio.calle || '';
                        if (this.$refs.dl_numero) this.$refs.dl_numero.value = p.domicilio.numero || '';
                        if (this.$refs.dl_barrio) this.$refs.dl_barrio.value = p.domicilio.barrio || '';
                    }
                    this.personaSeleccionadaId = p.id || null;
                },

                limpiarPersona() {
                    if (this.$refs.persona_id) this.$refs.persona_id.value = '';
                    if (this.$refs.dni) this.$refs.dni.value = '';
                    if (this.$refs.nombres) this.$refs.nombres.value = '';
                    if (this.$refs.apellidos) this.$refs.apellidos.value = '';
                    if (this.$refs.fecha_nacimiento) this.$refs.fecha_nacimiento.value = '';
                    if (this.$refs.genero) this.$refs.genero.value = '';
                    if (this.$refs.observaciones_persona) this.$refs.observaciones_persona.value = '';
                    if (this.$refs.dl_provincia_id) this.$refs.dl_provincia_id.value = '';
                    if (this.$refs.dl_departamento_id) this.$refs.dl_departamento_id.value = '';
                    if (this.$refs.dl_calle) this.$refs.dl_calle.value = '';
                    if (this.$refs.dl_numero) this.$refs.dl_numero.value = '';
                    if (this.$refs.dl_barrio) this.$refs.dl_barrio.value = '';
                    this.personaSeleccionadaId = null;
                },

                cargarDomicilioExistente(d) {
                    // Buscar si ya está en la lista por id; si no, agregarlo y seleccionar
                    let idx = this.domicilios.findIndex(x => x.id === d.id);
                    if (idx === -1) {
                        this.domicilios.push(d);
                        idx = this.domicilios.length - 1;
                    } else {
                        this.domicilios[idx] = d;
                    }
                    this.domicilioSeleccionado = idx;
                    if (d.latitud && d.longitud) {
                        const lat = parseFloat(d.latitud), lng = parseFloat(d.longitud);
                        if (!isNaN(lat) && !isNaN(lng)) {
                            this.setLatLng(lat, lng);
                            if (this.map) { this.map.setView([lat, lng], 16); }
                        }
                    }
                }
            }
        }
    </script>

    <script>
        // Lógica condicional para mostrar/ocultar Pedido de Captura por persona
        document.addEventListener('DOMContentLoaded', function () {
            const selects = document.querySelectorAll('select.pivot-situacion');
            function setup(select) {
                const personaId = select.getAttribute('data-persona-id');
                const contenedor = document.getElementById('contenedor_pedido_captura_' + personaId);
                if (!contenedor) return;
                function toggle() {
                    if (select.value === 'NO HALLADO') {
                        contenedor.style.display = 'flex';
                    } else {
                        contenedor.style.display = 'none';
                        const cb = contenedor.querySelector('input[type="checkbox"]');
                        if (cb) cb.checked = false;
                    }
                }
                toggle();
                select.addEventListener('change', toggle);
            }
            selects.forEach(setup);
        });
    </script>
</x-app-layout>
