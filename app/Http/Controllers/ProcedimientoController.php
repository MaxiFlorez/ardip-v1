<?php

namespace App\Http\Controllers;

use App\Models\Procedimiento;
use Illuminate\Http\Request;
use App\Models\Brigada; 
use Illuminate\Support\Facades\Auth;
use App\Models\Persona;
use App\Models\Domicilio;

class ProcedimientoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Listado ordenado por fecha y hora (descendente) con brigada precargada
        $procedimientos = Procedimiento::with('brigada')
            ->latest('fecha_procedimiento')
            ->latest('hora_procedimiento')
            ->paginate(10);

        return view('procedimientos.index', compact('procedimientos'));
    }

    /**
     * Muestra el formulario para crear un nuevo procedimiento.
     */
    public function create()
    {
        // Flujo 3: la brigada se autocompleta desde el usuario autenticado
        return view('procedimientos.create');
    }

    /**
     * Guarda un nuevo procedimiento en la base de datos.
     */
    public function store(Request $request)
    {
        // Validación (solo contenedor del legajo)
        $validated = $request->validate([
            'legajo_fiscal' => 'required|string|max:50',
            'caratula' => 'required|string',
            'fecha_procedimiento' => 'required|date|before_or_equal:today',
            'orden_judicial' => 'required|in:Detención en caso de secuestro positivo,Detención directa,Notificación al acusado,Secuestro y notificación',
        ]);

        // Crear Procedimiento con autocompletado de usuario y brigada
        $procedimiento = Procedimiento::create([
            'legajo_fiscal' => $validated['legajo_fiscal'],
            'caratula' => $validated['caratula'],
            'fecha_procedimiento' => $validated['fecha_procedimiento'],
            'hora_procedimiento' => null,
            'orden_judicial' => $validated['orden_judicial'],
            'usuario_id' => Auth::id(),
            'brigada_id' => optional(Auth::user())->brigada_id,
        ]);

        // Redirigir al detalle para continuar con vinculaciones
        return redirect()->route('procedimientos.show', $procedimiento)
                         ->with('success', 'Procedimiento creado. Ahora puede vincular personas y domicilios.');
    }
    public function show(Procedimiento $procedimiento)
    {
        // Cargar relaciones principales para la vista de detalle
        $procedimiento->load('brigada', 'personas', 'domicilios');

        // Catálogos para formularios en show (vinculación)
        $provincias = \App\Models\Provincia::orderBy('nombre')->get();
        $departamentos = \App\Models\Departamento::orderBy('nombre')->get();
        $sanJuanId = \App\Models\Provincia::where('nombre', 'San Juan')->value('id');

        return view('procedimientos.show', compact('procedimiento', 'provincias', 'departamentos', 'sanJuanId'));

    }

    public function edit(Procedimiento $procedimiento)
    {
        // Catálogos
        $brigadas = Brigada::orderBy('nombre')->get();
        $provincias = \App\Models\Provincia::orderBy('nombre')->get();
        $departamentos = \App\Models\Departamento::orderBy('nombre')->get();

        // Cargar relaciones necesarias para evitar N+1 en vistas y accesores
        $procedimiento->load(
            'brigada',
            'personas.domicilio.provincia',
            'personas.domicilio.departamento',
            'domicilios.provincia',
            'domicilios.departamento'
        );

        return view('procedimientos.edit', compact('procedimiento', 'brigadas', 'provincias', 'departamentos'));
    }

    /**
     * Actualiza el procedimiento en la base de datos.
     */
    public function update(Request $request, Procedimiento $procedimiento)
    {
        // Validación de pestaña 1 (Datos Legales)
        $datosProc = $request->validate([
            'legajo_fiscal' => 'required|string|max:50',
            'caratula' => 'required|string',
            'fecha_procedimiento' => 'required|date',
            'hora_procedimiento' => 'nullable|date_format:H:i',
            'brigada_id' => 'required|exists:brigadas,id',
            'orden_judicial' => 'nullable|in:Detención en caso de secuestro positivo,Detención directa,Notificación al acusado,Secuestro y notificación',
        ]);

        // Actualizar procedimiento
        $procedimiento->update([
            'legajo_fiscal' => $datosProc['legajo_fiscal'],
            'caratula' => $datosProc['caratula'],
            'fecha_procedimiento' => $datosProc['fecha_procedimiento'],
            'hora_procedimiento' => $datosProc['hora_procedimiento'] ?? null,
            'brigada_id' => $datosProc['brigada_id'],
            'orden_judicial' => $datosProc['orden_judicial'] ?? null,
        ]);

        // Pestaña 2 (opcional): Persona + Domicilio Legal
        // Soporta: actualizar persona existente (persona_id) o crear/actualizar por DNI.
        if ($request->filled('dni') || $request->filled('persona_id')) {
            $datosPersona = $request->validate([
                'persona_id' => 'nullable|exists:personas,id',
                'dni' => 'required|string|size:8',
                'nombres' => 'required|string|max:100',
                'apellidos' => 'required|string|max:100',
                'fecha_nacimiento' => 'required|date',
                'genero' => 'nullable|in:masculino,femenino,otro',
                'alias' => 'nullable|string|max:100',
                'nacionalidad' => 'nullable|string|max:50',
                'estado_civil' => 'nullable|in:soltero,casado,divorciado,viudo,concubinato',
                'foto' => 'nullable|image|max:2048',
                'observaciones_persona' => 'nullable|string',
            ]);

            $validatedDom = $request->validate([
                'domicilio_legal.calle' => 'required|string|max:150',
                'domicilio_legal.numero' => 'nullable|string|max:20',
                'domicilio_legal.barrio' => 'nullable|string|max:100',
                'domicilio_legal.provincia_id' => 'required|exists:provincias,id',
                'domicilio_legal.departamento_id' => 'nullable|exists:departamentos,id',
            ]);

            if (!empty($datosPersona['persona_id'])) {
                $persona = Persona::findOrFail($datosPersona['persona_id']);
            } else {
                $persona = Persona::firstOrNew(['dni' => $datosPersona['dni']]);
            }

            // Aseguramos defaults para campos opcionales que no deben quedar en NULL por restricciones DB
            $persona->fill([
                'dni' => $datosPersona['dni'],
                'nombres' => $datosPersona['nombres'],
                'apellidos' => $datosPersona['apellidos'],
                'fecha_nacimiento' => $datosPersona['fecha_nacimiento'],
                'genero' => $datosPersona['genero'] ?? $persona->genero ?? null,
                'alias' => $datosPersona['alias'] ?? $persona->alias ?? '',
                'nacionalidad' => ($datosPersona['nacionalidad'] ?? $persona->nacionalidad ?? 'SIN DATO'),
                'estado_civil' => $datosPersona['estado_civil'] ?? $persona->estado_civil ?? '',
                'observaciones' => $datosPersona['observaciones_persona'] ?? $persona->observaciones ?? '',
            ]);
            if ($request->hasFile('foto')) {
                $path = $request->file('foto')->store('fotos_personas', 'public');
                $persona->foto = $path;
            }
            $persona->save();

            $dl = data_get($validatedDom, 'domicilio_legal', []);
            $domicilioLegal = Domicilio::create([
                'calle' => $dl['calle'],
                'numero' => $dl['numero'] ?? null,
                'barrio' => $dl['barrio'] ?? null,
                'provincia_id' => $dl['provincia_id'],
                'departamento_id' => $dl['departamento_id'] ?? null,
            ]);
            $persona->domicilio_id = $domicilioLegal->id;
            $persona->save();

            if (! $procedimiento->personas()->where('personas.id', $persona->id)->exists()) {
                $procedimiento->personas()->attach($persona->id, [
                    'situacion_procesal' => 'NOTIFICADO',
                    'observaciones' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        // Actualizar datos pivote de personas vinculadas (Fase 15)
        $personasPivot = (array) $request->input('personas', []);
        if (!empty($personasPivot)) {
            $permitidos = ['NO HALLADO', 'DETENIDO', 'APREHENDIDO', 'NOTIFICADO'];
            foreach ($personasPivot as $personaId => $pivot) {
                if (! $procedimiento->personas()->where('personas.id', $personaId)->exists()) {
                    continue;
                }
                $situacion = strtoupper(trim((string)($pivot['situacion_procesal'] ?? '')));
                if (! in_array($situacion, $permitidos, true)) {
                    continue;
                }
                $pedido = isset($pivot['pedido_captura']) && (int)$pivot['pedido_captura'] === 1 ? 1 : 0;
                if ($situacion !== 'NO HALLADO') {
                    $pedido = 0; // Regla: solo con NO HALLADO
                }
                $obs = isset($pivot['observaciones']) ? trim((string)$pivot['observaciones']) : null;

                $procedimiento->personas()->updateExistingPivot($personaId, [
                    'situacion_procesal' => $situacion,
                    'pedido_captura' => $pedido,
                    'observaciones' => $obs,
                    'updated_at' => now(),
                ]);
            }
        }

        // Pestaña 3: Domicilios allanados (agregar nuevos si se envían)
        $request->validate([
            'domicilios_allanados' => 'array',
            'domicilios_allanados.*.id' => 'nullable|exists:domicilios,id',
            'domicilios_allanados.*.calle_allanada' => 'nullable|string|max:150',
            'domicilios_allanados.*.numero_allanada' => 'nullable|string|max:20',
            'domicilios_allanados.*.provincia_id' => 'nullable|exists:provincias,id',
            'domicilios_allanados.*.departamento_id' => 'nullable|exists:departamentos,id',
            'domicilios_allanados.*.monoblock' => 'nullable|alpha_num|max:100',
            'domicilios_allanados.*.torre' => 'nullable|string|max:10',
            'domicilios_allanados.*.piso' => 'nullable|string|max:10',
            'domicilios_allanados.*.depto' => 'nullable|string|max:10',
            'domicilios_allanados.*.sector' => 'nullable|string|max:100',
            'domicilios_allanados.*.manzana' => 'nullable|alpha_num|max:20',
            'domicilios_allanados.*.lote' => 'nullable|alpha_num|max:20',
            'domicilios_allanados.*.casa' => 'nullable|alpha_num|max:20',
            'domicilios_allanados.*.latitud' => 'nullable|numeric',
            'domicilios_allanados.*.longitud' => 'nullable|numeric',
        ]);

        $attachIds = [];
        foreach ((array) $request->input('domicilios_allanados', []) as $item) {
            $calle = $item['calle_allanada'] ?? null;
            if (!$calle) continue;
            $coords = (isset($item['latitud'], $item['longitud']) && $item['latitud'] !== '' && $item['longitud'] !== '')
                ? (trim($item['latitud']).','.trim($item['longitud'])) : null;

            if (!empty($item['id'])) {
                $dom = $procedimiento->domicilios()->where('domicilios.id', $item['id'])->first();
                if ($dom) {
                    $dom->update([
                        'calle' => $calle,
                        'numero' => $item['numero_allanada'] ?? null,
                        'provincia_id' => $item['provincia_id'] ?? null,
                        'departamento_id' => $item['departamento_id'] ?? null,
                        'monoblock' => $item['monoblock'] ?? null,
                        'torre' => $item['torre'] ?? null,
                        'piso' => $item['piso'] ?? null,
                        'depto' => $item['depto'] ?? null,
                        'sector' => $item['sector'] ?? null,
                        'manzana' => $item['manzana'] ?? null,
                        'lote' => $item['lote'] ?? null,
                        'casa' => $item['casa'] ?? null,
                        'coordenadas_gps' => $coords,
                    ]);
                    $attachIds[] = $dom->id;
                } else {
                    $dom = Domicilio::find($item['id']);
                    if ($dom) { $attachIds[] = $dom->id; }
                }
            } else {
                $dom = Domicilio::create([
                    'calle' => $calle,
                    'numero' => $item['numero_allanada'] ?? null,
                    'provincia_id' => $item['provincia_id'] ?? null,
                    'departamento_id' => $item['departamento_id'] ?? null,
                    'monoblock' => $item['monoblock'] ?? null,
                    'torre' => $item['torre'] ?? null,
                    'piso' => $item['piso'] ?? null,
                    'depto' => $item['depto'] ?? null,
                    'sector' => $item['sector'] ?? null,
                    'manzana' => $item['manzana'] ?? null,
                    'lote' => $item['lote'] ?? null,
                    'casa' => $item['casa'] ?? null,
                    'coordenadas_gps' => $coords,
                ]);
                $attachIds[] = $dom->id;
            }
        }
        if ($attachIds) {
            $now = now();
            $attachData = [];
            foreach ($attachIds as $id) { $attachData[$id] = ['created_at' => $now, 'updated_at' => $now]; }
            $procedimiento->domicilios()->attach($attachData);
        }

        return redirect()->route('procedimientos.show', $procedimiento)
                         ->with('success', 'Procedimiento actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Procedimiento $procedimiento)
    {
        // 1. Eliminar el procedimiento
        $procedimiento->delete();

        // 2. Redirigir de vuelta al listado con un mensaje de éxito
        return redirect()->route('procedimientos.index')
                         ->with('success', 'Procedimiento eliminado exitosamente.');
    }

    public function vincularPersona(Request $request, Procedimiento $procedimiento)
    {
        // 1) Validación de Persona
        $datosPersona = $request->validate([
            'dni' => 'required|numeric|digits:8|unique:personas,dni',
            'nombres' => 'required|string|max:100',
            'apellidos' => 'required|string|max:100',
            'fecha_nacimiento' => 'required|date|before_or_equal:today',
            'genero' => 'nullable|in:masculino,femenino,otro',
            'alias' => 'nullable|string|max:100',
            'nacionalidad' => 'nullable|string|max:50',
            'estado_civil' => 'nullable|in:soltero,casado,divorciado,viudo,concubinato',
            'foto' => 'nullable|image|max:2048',
            'observaciones' => 'nullable|string',
        ]);

        // 2) Validación de Domicilio
        $datosDomicilio = $request->validate([
            'domicilio.calle' => 'required|string|max:150',
            'domicilio.numero' => 'nullable|integer|min:0',
            'domicilio.barrio' => 'nullable|string|max:100',
            'domicilio.provincia_id' => 'required|exists:provincias,id',
            'domicilio.departamento_id' => 'nullable|exists:departamentos,id',
            'domicilio.monoblock' => 'nullable|alpha_num|max:100',
            'domicilio.torre' => 'nullable|string|max:10',
            'domicilio.piso' => 'nullable|string|max:10',
            'domicilio.depto' => 'nullable|string|max:10',
            'domicilio.sector' => 'nullable|string|max:100',
            'domicilio.manzana' => 'nullable|alpha_num|max:20',
            'domicilio.lote' => 'nullable|alpha_num|max:20',
            'domicilio.casa' => 'nullable|alpha_num|max:20',
            'domicilio.latitud' => 'nullable|numeric',
            'domicilio.longitud' => 'nullable|numeric',
            'domicilio.coordenadas_gps' => 'nullable|string',
        ]);

        // 3) Validación de Pivote
        $datosPivot = $request->validate([
            'pivot.situacion_procesal' => 'required|in:NO HALLADO,DETENIDO,APREHENDIDO,NOTIFICADO',
            'pivot.pedido_captura' => 'nullable|boolean',
            'pivot.observaciones' => 'nullable|string',
        ]);

        // 4) Crear Domicilio
        $dom = data_get($datosDomicilio, 'domicilio', []);
        $coordenadas = null;
        // Preferir coordenadas_gps (lat,long) si viene en el request
        if (!empty($dom['coordenadas_gps']) && is_string($dom['coordenadas_gps'])) {
            $coordenadas = trim($dom['coordenadas_gps']);
        } elseif (isset($dom['latitud'], $dom['longitud']) && $dom['latitud'] !== null && $dom['longitud'] !== null) {
            $coordenadas = (trim((string)$dom['latitud']).','.trim((string)$dom['longitud']));
        }
        $domicilio = Domicilio::create([
            'calle' => $dom['calle'],
            'numero' => $dom['numero'] ?? null,
            'barrio' => $dom['barrio'] ?? null,
            'provincia_id' => $dom['provincia_id'],
            'departamento_id' => $dom['departamento_id'] ?? null,
            'monoblock' => $dom['monoblock'] ?? null,
            'torre' => $dom['torre'] ?? null,
            'piso' => $dom['piso'] ?? null,
            'depto' => $dom['depto'] ?? null,
            'sector' => $dom['sector'] ?? null,
            'manzana' => $dom['manzana'] ?? null,
            'lote' => $dom['lote'] ?? null,
            'casa' => $dom['casa'] ?? null,
            'coordenadas_gps' => $coordenadas,
        ]);

        // 5) Crear/Actualizar Persona (evitar duplicados por DNI)
        $persona = Persona::firstOrNew(['dni' => $datosPersona['dni']]);
        $persona->fill([
            'nombres' => $datosPersona['nombres'],
            'apellidos' => $datosPersona['apellidos'],
            'fecha_nacimiento' => $datosPersona['fecha_nacimiento'],
            'genero' => $datosPersona['genero'] ?? $persona->genero ?? null,
            'alias' => $datosPersona['alias'] ?? $persona->alias ?? null,
            'nacionalidad' => $datosPersona['nacionalidad'] ?? $persona->nacionalidad ?? null,
            'estado_civil' => $datosPersona['estado_civil'] ?? $persona->estado_civil ?? null,
            'observaciones' => $datosPersona['observaciones'] ?? $persona->observaciones ?? null,
        ]);
        $persona->domicilio_id = $domicilio->id;
        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('fotos_personas', 'public');
            $persona->foto = $path;
        }
        $persona->save();

        // 6) Vincular en pivote
        $situacion = strtoupper(trim(data_get($datosPivot, 'pivot.situacion_procesal')));
        $pedidoCaptura = (int) (data_get($datosPivot, 'pivot.pedido_captura', 0) ? 1 : 0);
        if ($situacion !== 'NO HALLADO') {
            $pedidoCaptura = 0; // Regla de negocio
        }
        $obsPivot = data_get($datosPivot, 'pivot.observaciones');

        // Evitar duplicar vínculo
        if (! $procedimiento->personas()->where('personas.id', $persona->id)->exists()) {
            $procedimiento->personas()->attach($persona->id, [
                'situacion_procesal' => $situacion,
                'pedido_captura' => $pedidoCaptura,
                'observaciones' => $obsPivot,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // 7) Redirigir
        return redirect()->route('procedimientos.show', $procedimiento)
                         ->with('success', 'Persona vinculada correctamente.')
                         ->withInput([]);
    }

    public function vincularDomicilio(Request $request, Procedimiento $procedimiento)
    {
        // Validación de los campos del domicilio del hecho
        $hecho = $request->validate([
            'hecho.provincia_id' => 'required|exists:provincias,id',
            'hecho.departamento_id' => 'nullable|exists:departamentos,id',
            'hecho.calle' => 'required|string|max:150',
            'hecho.numero' => 'nullable|numeric|min:0',
            'hecho.barrio' => 'nullable|string|max:100',
            'hecho.monoblock' => 'nullable|alpha_num|max:100',
            'hecho.torre' => 'nullable|string|max:10',
            'hecho.piso' => 'nullable|string|max:10',
            'hecho.depto' => 'nullable|string|max:10',
            'hecho.sector' => 'nullable|string|max:100',
            'hecho.manzana' => 'nullable|alpha_num|max:20',
            'hecho.lote' => 'nullable|alpha_num|max:20',
            'hecho.casa' => 'nullable|alpha_num|max:20',
            'hecho.coordenadas_gps' => 'nullable|string',
        ]);

        $h = data_get($hecho, 'hecho', []);

        // Crear el Domicilio del hecho
        $nuevo = Domicilio::create([
            'provincia_id' => $h['provincia_id'],
            'departamento_id' => $h['departamento_id'] ?? null,
            'calle' => $h['calle'],
            'numero' => $h['numero'] ?? null,
            'barrio' => $h['barrio'] ?? null,
            'monoblock' => $h['monoblock'] ?? null,
            'torre' => $h['torre'] ?? null,
            'piso' => $h['piso'] ?? null,
            'depto' => $h['depto'] ?? null,
            'sector' => $h['sector'] ?? null,
            'manzana' => $h['manzana'] ?? null,
            'lote' => $h['lote'] ?? null,
            'casa' => $h['casa'] ?? null,
            'coordenadas_gps' => $h['coordenadas_gps'] ?? null,
        ]);

        // Vincular al procedimiento (evitar duplicado por si existiera igual dirección)
        if (! $procedimiento->domicilios()->where('domicilios.id', $nuevo->id)->exists()) {
            $procedimiento->domicilios()->attach($nuevo->id, [
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return redirect()->route('procedimientos.show', $procedimiento)
                         ->with('success', 'Domicilio del hecho vinculado.')
                         ->withInput([]);
    }

}
