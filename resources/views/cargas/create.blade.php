<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Carga Unificada (Actuación + Persona + Domicilios)
        </h2>
    </x-slot>

    @php
        $errorsPaso1 = $errors->hasAny(['legajo_fiscal','caratula','fecha_procedimiento','hora_procedimiento','brigada_id']);
        $errorsPaso2 = $errors->hasAny([
            'dni','nombres','apellidos','fecha_nacimiento','foto',
            'persona.genero','persona.alias','persona.nacionalidad','persona.estado_civil','persona.observaciones',
            'domicilio_legal.calle','domicilio_legal.numero','domicilio_legal.barrio','domicilio_legal.provincia_id','domicilio_legal.departamento_id'
        ]);
        $errorsPaso3 = $errors->hasAny(['domicilios_allanados.*.calle_allanada','domicilios_allanados.*.latitud','domicilios_allanados.*.longitud']);
        $initialTab = $errorsPaso2 ? 'vinculados' : ($errorsPaso3 ? 'ubicacion' : 'legales');
    @endphp

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

                    <form action="{{ route('carga.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

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
                                    <input id="fecha_procedimiento" type="date" name="fecha_procedimiento" value="{{ old('fecha_procedimiento') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                                    @error('fecha_procedimiento')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                                </div>
                                <div>
                                    <label for="hora_procedimiento" class="block text-sm">Hora Procedimiento</label>
                                    <input id="hora_procedimiento" type="time" name="hora_procedimiento" value="{{ old('hora_procedimiento') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                    @error('hora_procedimiento')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                                </div>
                                <div>
                                    <label for="brigada_id" class="block text-sm">Brigada *</label>
                                    <select id="brigada_id" name="brigada_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                                        <option value="">Seleccione…</option>
                                        @foreach($brigadas as $b)
                                            <option value="{{ $b->id }}" @selected(old('brigada_id')==$b->id)>{{ $b->nombre }}</option>
                                        @endforeach
                                    </select>
                                    @error('brigada_id')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                                </div>
                            </div>

                            <div class="flex justify-end mt-6">
                                <button type="button" @click="tab='vinculados'" class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 px-4 rounded">Siguiente</button>
                            </div>
                        </div>

                        <!-- Tab 2: Vinculados (Persona + Domicilio Legal) -->
                        <div x-show="tab==='vinculados'" x-cloak>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label for="dni" class="block text-sm">DNI *</label>
                                    <input id="dni" type="text" name="dni" maxlength="8" value="{{ old('dni') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                                    @error('dni')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                                </div>
                                <div>
                                    <label for="nombres" class="block text-sm">Nombres *</label>
                                    <input id="nombres" type="text" name="nombres" value="{{ old('nombres') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                                    @error('nombres')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                                </div>
                                <div>
                                    <label for="apellidos" class="block text-sm">Apellidos *</label>
                                    <input id="apellidos" type="text" name="apellidos" value="{{ old('apellidos') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                                    @error('apellidos')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                                </div>
                                <div>
                                    <label for="fecha_nacimiento" class="block text-sm">Fecha Nacimiento *</label>
                                    <input id="fecha_nacimiento" type="date" name="fecha_nacimiento" value="{{ old('fecha_nacimiento') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                                    @error('fecha_nacimiento')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                                </div>
                                <div>
                                    <label for="persona_genero" class="block text-sm">Género</label>
                                    <select id="persona_genero" name="persona[genero]" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                        <option value="">-- Seleccione --</option>
                                        <option value="masculino" @selected(old('persona.genero')==='masculino')>Masculino</option>
                                        <option value="femenino" @selected(old('persona.genero')==='femenino')>Femenino</option>
                                        <option value="otro" @selected(old('persona.genero')==='otro')>Otro</option>
                                    </select>
                                    @error('persona.genero')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                                </div>
                                <div>
                                    <label for="foto" class="block text-sm">Foto (opcional)</label>
                                    <input id="foto" type="file" name="foto" accept="image/*" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                    @error('foto')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                                </div>
                                <div>
                                    <label for="persona_alias" class="block text-sm">Alias</label>
                                    <input id="persona_alias" type="text" name="persona[alias]" value="{{ old('persona.alias') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                    @error('persona.alias')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                                </div>
                                <div>
                                    <label for="persona_nacionalidad" class="block text-sm">Nacionalidad</label>
                                    <input id="persona_nacionalidad" type="text" name="persona[nacionalidad]" value="{{ old('persona.nacionalidad', 'Argentina') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                    @error('persona.nacionalidad')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                                </div>
                                <div>
                                    <label for="persona_estado_civil" class="block text-sm">Estado civil</label>
                                    <select id="persona_estado_civil" name="persona[estado_civil]" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                        <option value="">-- Seleccione --</option>
                                        <option value="soltero" @selected(old('persona.estado_civil')==='soltero')>Soltero</option>
                                        <option value="casado" @selected(old('persona.estado_civil')==='casado')>Casado</option>
                                        <option value="divorciado" @selected(old('persona.estado_civil')==='divorciado')>Divorciado</option>
                                        <option value="viudo" @selected(old('persona.estado_civil')==='viudo')>Viudo</option>
                                        <option value="concubinato" @selected(old('persona.estado_civil')==='concubinato')>Concubinato</option>
                                    </select>
                                    @error('persona.estado_civil')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                                </div>
                                <div class="md:col-span-3">
                                    <label for="persona_observaciones" class="block text-sm">Observaciones (Persona)</label>
                                    <textarea id="persona_observaciones" name="persona[observaciones]" rows="2" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">{{ old('persona.observaciones') }}</textarea>
                                    @error('persona.observaciones')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                                </div>
                            </div>

                            <h4 class="text-md font-semibold mt-6">Domicilio Legal</h4>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-2">
                                <div>
                                    <label for="domicilio_legal_provincia_id" class="block text-sm">Provincia *</label>
                                    <select id="domicilio_legal_provincia_id" name="domicilio_legal[provincia_id]" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                                        <option value="">Seleccione…</option>
                                        @foreach($provincias as $p)
                                            <option value="{{ $p->id }}" @selected(old('domicilio_legal.provincia_id')==$p->id)>{{ $p->nombre }}</option>
                                        @endforeach
                                    </select>
                                    @error('domicilio_legal.provincia_id')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                                </div>
                                <div>
                                    <label for="domicilio_legal_departamento_id" class="block text-sm">Departamento</label>
                                    <select id="domicilio_legal_departamento_id" name="domicilio_legal[departamento_id]" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                        <option value="">Seleccione…</option>
                                        @foreach($departamentos as $d)
                                            <option value="{{ $d->id }}" @selected(old('domicilio_legal.departamento_id')==$d->id)>{{ $d->nombre }}</option>
                                        @endforeach
                                    </select>
                                    @error('domicilio_legal.departamento_id')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                                </div>
                                <div>
                                    <label for="domicilio_legal_calle" class="block text-sm">Calle *</label>
                                    <input id="domicilio_legal_calle" type="text" name="domicilio_legal[calle]" value="{{ old('domicilio_legal.calle') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                                    @error('domicilio_legal.calle')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                                </div>
                                <div>
                                    <label for="domicilio_legal_numero" class="block text-sm">Número</label>
                                    <input id="domicilio_legal_numero" type="text" name="domicilio_legal[numero]" value="{{ old('domicilio_legal.numero') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                    @error('domicilio_legal.numero')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                                </div>
                                <div>
                                    <label for="domicilio_legal_barrio" class="block text-sm">Barrio</label>
                                    <input id="domicilio_legal_barrio" type="text" name="domicilio_legal[barrio]" value="{{ old('domicilio_legal.barrio') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                    @error('domicilio_legal.barrio')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                                </div>
                            </div>

                            <div class="flex justify-between mt-6">
                                <button type="button" @click="tab='legales'" class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-2 px-4 rounded">Anterior</button>
                                <button type="button" @click="tab='ubicacion'" class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 px-4 rounded">Siguiente</button>
                            </div>
                        </div>

                        <!-- Tab 3: Fecha, Hora y Ubicación (Domicilios Allanados + Mapa) -->
                        <div x-show="tab==='ubicacion'" x-cloak x-init="$watch('tab', (v) => { if (v==='ubicacion') $nextTick(()=> ensureMap()); }); if (tab==='ubicacion') $nextTick(()=> ensureMap());">
                            <!-- Campos arriba -->
                            <div class="mt-4">
                                <div class="flex items-center justify-between mb-2">
                                    <h4 class="text-md font-semibold">Domicilios Allanados</h4>
                                    <button type="button" @click="agregarDomicilio()" class="text-sm bg-green-600 hover:bg-green-700 text-white font-semibold py-1 px-3 rounded">+ Agregar otro domicilio</button>
                                </div>

                                <template x-for="(dom, idx) in domicilios" :key="idx">
                                    <div class="border rounded p-3 mb-3">
                                        <div class="flex items-center justify-between mb-2">
                                            <span class="text-sm font-medium">Domicilio #<span x-text="idx+1"></span></span>
                                            <div class="flex items-center gap-2">
                                                <label class="text-xs text-gray-600 flex items-center gap-1">
                                                    <input type="radio" name="domicilio_seleccionado" :value="idx" x-model.number="domicilioSeleccionado">
                                                    Usar mapa para este
                                                </label>
                                                <button type="button" @click="quitarDomicilio(idx)" class="text-xs text-red-600 hover:text-red-800">Quitar</button>
                                            </div>
                                        </div>

                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
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
                            </div>

                            <!-- Mapa abajo -->
                            <div class="mt-6">
                                <h4 class="text-md font-semibold mb-2">Mapa</h4>
                                <div id="map" class="w-full h-80 rounded border relative z-0"></div>
                                <p class="text-xs text-gray-500 mt-2">Haz clic en el mapa para completar lat/long del domicilio seleccionado.</p>
                            </div>

                            <div class="flex justify-between mt-6">
                                <button type="button" @click="tab='vinculados'" class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-2 px-4 rounded">Anterior</button>
                                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded">Finalizar Carga</button>
                            </div>
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
        function cargaAsistente() {
            return {
                tab: 'legales',
                domicilios: [],
                domicilioSeleccionado: 0,
                map: null,
                marker: null,

                init(initialTab) {
                    this.tab = initialTab || 'legales';
                    // iniciar con un domicilio vacío por UX
                    this.domicilios = [{ calle_allanada: '', numero_allanada: '', provincia_id: '', departamento_id: '', monoblock: '', torre: '', piso: '', depto: '', sector: '', manzana: '', lote: '', casa: '', latitud: '', longitud: '' }];
                    this.domicilioSeleccionado = 0;

                    // Inicializar mapa solo cuando la pestaña esté visible
                    this.$watch('tab', (v) => {
                        if (v === 'ubicacion') {
                            this.$nextTick(() => {
                                this.ensureMap();
                            });
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
                    this.map = L.map('map').setView([-34.6037, -58.3816], 12); // centro aproximado
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
                    this.domicilios.push({ calle_allanada: '', numero_allanada: '', provincia_id: '', departamento_id: '', monoblock: '', torre: '', piso: '', depto: '', sector: '', manzana: '', lote: '', casa: '', latitud: '', longitud: '' });
                    this.domicilioSeleccionado = this.domicilios.length - 1;
                },

                quitarDomicilio(index) {
                    this.domicilios.splice(index, 1);
                    if (this.domicilios.length === 0) {
                        this.domicilios.push({ calle_allanada: '', numero_allanada: '', provincia_id: '', departamento_id: '', monoblock: '', torre: '', piso: '', depto: '', sector: '', manzana: '', lote: '', casa: '', latitud: '', longitud: '' });
                    }
                    this.domicilioSeleccionado = Math.max(0, this.domicilios.length - 1);
                }
            }
        }
    </script>
</x-app-layout>
