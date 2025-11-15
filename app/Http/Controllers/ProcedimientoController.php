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
        // Usamos 'with' para cargar la relación 'brigada' y evitar N+1 queries
        $procedimientos = Procedimiento::with('brigada') 
                                      ->orderBy('fecha_procedimiento', 'desc')
                                      ->paginate(10); 

        return view('procedimientos.index', compact('procedimientos'));
    }

    /**
     * Muestra el formulario para crear un nuevo procedimiento.
     */
    public function create()
    {
        // 3. OBTENER BRIGADAS PARA EL SELECT
        $brigadas = Brigada::orderBy('nombre')->get();
        
        return view('procedimientos.create', compact('brigadas'));
    }

    /**
     * Guarda un nuevo procedimiento en la base de datos.
     */
    public function store(Request $request)
    {
        // 4. VALIDACIÓN DE DATOS (SIMPLE POR AHORA)
        $request->validate([
            'legajo_fiscal' => 'required|string|max:50',
            'caratula' => 'required|string',
            'fecha_procedimiento' => 'required|date',
            'brigada_id' => 'required|exists:brigadas,id',
            'orden_secuestro' => 'nullable',
            'orden_detencion' => 'nullable',
        ]);

        // 5. CREAR PROCEDIMIENTO
        // Usamos $guarded, así que podemos pasar el request validado
        $datos = $request->all();
        
        // Asignar el usuario que lo está cargando
        $datos['usuario_id'] = Auth::id(); 
        
        // Convertir checkboxes (si no se marcan, no envían valor)
        $datos['orden_secuestro'] = $request->has('orden_secuestro');
        $datos['orden_detencion'] = $request->has('orden_detencion');

        // Valores por defecto para 'resultado' según la migración
        $datos['resultado_secuestro'] = $request->has('orden_secuestro') ? 'negativo' : 'no_aplica';
        $datos['resultado_detencion'] = $request->has('orden_detencion') ? 'negativo' : 'no_aplica';


        $procedimiento = Procedimiento::create($datos);

        // 6. REDIRIGIR (Por ahora al listado, luego al detalle)
        return redirect()->route('procedimientos.index')
                         ->with('success', 'Procedimiento creado exitosamente.');
    }
    public function show(Procedimiento $procedimiento)
    {
        // Cargamos las relaciones que definimos en el Modelo
        // para poder usarlas en la vista
        $procedimiento->load(
            'brigada',
            'personas.domicilio.provincia',
            'personas.domicilio.departamento',
            'domicilios.provincia',
            'domicilios.departamento'
        );

        // 2. ADICIÓN: Buscamos TODAS las personas para el menú desplegable
        $personasDisponibles = Persona::orderBy('apellidos')->get();

        //ADICIÓN: Buscamos TODOS los domicilios
        $domiciliosDisponibles = Domicilio::orderBy('calle')->get(); // Ordenamos por calle

        return view('procedimientos.show', compact(
            'procedimiento', 
            'personasDisponibles', 
            'domiciliosDisponibles' // <-- Añadimos domicilios al compact
        ));

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
        ]);

        // Actualizar procedimiento
        $procedimiento->update([
            'legajo_fiscal' => $datosProc['legajo_fiscal'],
            'caratula' => $datosProc['caratula'],
            'fecha_procedimiento' => $datosProc['fecha_procedimiento'],
            'hora_procedimiento' => $datosProc['hora_procedimiento'] ?? null,
            'brigada_id' => $datosProc['brigada_id'],
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
                    'situacion_procesal' => 'notificado',
                    'observaciones' => null,
                    'created_at' => now(),
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
        // 1. Validar los datos del formulario
        $datosValidados = $request->validate([
            'persona_id' => 'required|exists:personas,id',
            'situacion_procesal' => 'required|in:detenido,notificado,no_hallado,contravencion', //
            'observaciones' => 'nullable|string',
        ]);

        // 2. Verificar si la persona ya está vinculada (para evitar duplicados)
        $vinculoExistente = $procedimiento->personas()
                            ->where('persona_id', $datosValidados['persona_id'])
                            ->exists();

        if ($vinculoExistente) {
            return redirect()->route('procedimientos.show', $procedimiento)
                             ->with('error', 'Esta persona ya está vinculada a este procedimiento.');
        }

        // 3. Vincular la persona usando la relación 'personas()'
        // El método attach() guarda en la tabla pivote 'procedimiento_personas'
        $procedimiento->personas()->attach($datosValidados['persona_id'], [
            'situacion_procesal' => $datosValidados['situacion_procesal'],
            'observaciones' => $datosValidados['observaciones'],
            'created_at' => now(), // Aseguramos los timestamps
            'updated_at' => now(), // Aseguramos los timestamps
        ]);

        // 4. Redirigir de vuelta al detalle con mensaje de éxito
        return redirect()->route('procedimientos.show', $procedimiento)
                         ->with('success', 'Persona vinculada exitosamente.');
    }

    public function vincularDomicilio(Request $request, Procedimiento $procedimiento)
    {
        // 1. Validar los datos del formulario
        $datosValidados = $request->validate([
            'domicilio_id' => 'required|exists:domicilios,id',
        ]);

        // 2. Verificar si el domicilio ya está vinculado (para evitar duplicados)
        $vinculoExistente = $procedimiento->domicilios()
                            ->where('domicilio_id', $datosValidados['domicilio_id'])
                            ->exists();

        if ($vinculoExistente) {
            return redirect()->route('procedimientos.show', $procedimiento)
                             ->with('error', 'Este domicilio ya está vinculado a este procedimiento.');
        }

        // 3. Vincular el domicilio usando la relación 'domicilios()'
        // El método attach() guarda en la tabla pivote 'procedimiento_domicilios'
        $procedimiento->domicilios()->attach($datosValidados['domicilio_id'], [
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 4. Redirigir de vuelta al detalle con mensaje de éxito
        return redirect()->route('procedimientos.show', $procedimiento)
                         ->with('success', 'Domicilio vinculado exitosamente.');
    }

}
