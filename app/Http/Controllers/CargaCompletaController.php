<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Models\Brigada;
use App\Models\Provincia;
use App\Models\Departamento;
use App\Models\Procedimiento;
use App\Models\Persona;
use App\Models\Domicilio;

class CargaCompletaController extends Controller
{
    public function create(Request $request)
    {
        $provincias = Provincia::orderBy('nombre')->get();
        $departamentos = Departamento::orderBy('nombre')->get();

        $sanJuanId = Provincia::where('nombre', 'San Juan')->value('id');

        $procedimiento = null;
        $vinculados = collect();
        $domiciliosHecho = collect();
        $procedimientoId = $request->input('procedimiento_id');
        $hasProcedimiento = $request->has('procedimiento_id');
        if ($procedimientoId) {
            $procedimiento = Procedimiento::with(['personas','domicilios'])->find($procedimientoId);
            if ($procedimiento) {
                $vinculados = $procedimiento->personas;
                $domiciliosHecho = $procedimiento->domicilios;
            }
        }

        return view('cargas.create', compact(
            'provincias',
            'departamentos',
            'sanJuanId',
            'procedimiento',
            'vinculados',
            'domiciliosHecho',
            'hasProcedimiento'
        ));
    }

    public function store(Request $request)
    {
        // Finaliza la carga sin crear recursos (ya hechos en los pasos parciales)
        return redirect()->route('procedimientos.index')
            ->with('success', 'Procedimiento finalizado con éxito.');
    }

    public function vincularPersona(Request $request)
    {
        $procedimiento = null;
        $procedimientoId = $request->input('procedimiento_id');
        $procId = null;

        DB::transaction(function () use ($request, &$procedimiento, $procedimientoId, &$procId) {
            // Si no hay procedimiento, crearlo con datos del Paso 1
            if (!$procedimientoId) {
                $datosProc = $request->validate([
                    'legajo_fiscal' => 'required|string|max:50',
                    'caratula' => 'required|string',
                    'fecha_procedimiento' => 'required|date|before_or_equal:today',
                    'hora_procedimiento' => 'nullable|date_format:H:i',
                    'orden_judicial' => 'required|in:Detención en caso de secuestro positivo,Detención directa,Notificación al acusado,Secuestro y notificación',
                    // Incluir aquí para acumular errores de ambas fechas en una sola respuesta
                    'fecha_nacimiento' => 'required|date|before_or_equal:today',
                ]);

                $procedimiento = Procedimiento::create([
                    'legajo_fiscal' => $datosProc['legajo_fiscal'],
                    'caratula' => $datosProc['caratula'],
                    'fecha_procedimiento' => $datosProc['fecha_procedimiento'],
                    'hora_procedimiento' => $datosProc['hora_procedimiento'] ?? null,
                    'orden_judicial' => $datosProc['orden_judicial'],
                    'brigada_id' => optional(Auth::user())->brigada_id,
                    'usuario_id' => Auth::id(),
                ]);
            } else {
                $procedimiento = Procedimiento::findOrFail($procedimientoId);
            }

            // Validación Persona
            $datosPersona = $request->validate([
                'dni' => 'required|digits:8|unique:personas,dni',
                'nombres' => 'required|string|max:100',
                'apellidos' => 'required|string|max:100',
                'fecha_nacimiento' => 'required|date|before_or_equal:today',
                'genero' => 'nullable|in:masculino,femenino,otro',
                'alias' => 'nullable|string|max:100',
                'nacionalidad' => 'nullable|string|max:50',
                'estado_civil' => 'nullable|in:soltero,casado,divorciado,viudo,concubinato',
                'observaciones' => 'nullable|string',
                'foto' => 'nullable|image|max:2048',
            ]);

            // Validación Domicilio (acepta "domicilio.*" o "domicilio_legal.*" para compatibilidad)
            $datosDom = $request->validate([
                'domicilio.provincia_id' => 'required_without:domicilio_legal.provincia_id|nullable|exists:provincias,id',
                'domicilio.departamento_id' => 'nullable|exists:departamentos,id',
                'domicilio.calle' => 'required_without:domicilio_legal.calle|nullable|string|max:150',
                'domicilio.numero' => 'nullable|integer|min:0',
                'domicilio.piso' => 'nullable|string|max:20',
                'domicilio.depto' => 'nullable|string|max:20',
                'domicilio.torre' => 'nullable|string|max:50',
                'domicilio.monoblock' => 'nullable|string|max:50',
                'domicilio.manzana' => 'nullable|string|max:50',
                'domicilio.lote' => 'nullable|string|max:50',
                'domicilio.casa' => 'nullable|string|max:50',
                'domicilio.barrio' => 'nullable|string|max:100',
                'domicilio.sector' => 'nullable|string|max:100',
                'domicilio.coordenadas_gps' => 'nullable|string',

                'domicilio_legal.provincia_id' => 'required_without:domicilio.provincia_id|nullable|exists:provincias,id',
                'domicilio_legal.departamento_id' => 'nullable|exists:departamentos,id',
                'domicilio_legal.calle' => 'required_without:domicilio.calle|nullable|string|max:150',
                'domicilio_legal.numero' => 'nullable|integer|min:0',
                'domicilio_legal.piso' => 'nullable|string|max:20',
                'domicilio_legal.depto' => 'nullable|string|max:20',
                'domicilio_legal.torre' => 'nullable|string|max:50',
                'domicilio_legal.monoblock' => 'nullable|string|max:50',
                'domicilio_legal.manzana' => 'nullable|string|max:50',
                'domicilio_legal.lote' => 'nullable|string|max:50',
                'domicilio_legal.casa' => 'nullable|string|max:50',
                'domicilio_legal.barrio' => 'nullable|string|max:100',
                'domicilio_legal.sector' => 'nullable|string|max:100',
                'domicilio_legal.coordenadas_gps' => 'nullable|string',
            ]);

            $dl = $request->input('domicilio');
            if (!$dl) {
                $dl = $request->input('domicilio_legal', []);
            }
            $domicilio = Domicilio::create([
                'calle' => $dl['calle'],
                'numero' => $dl['numero'] ?? null,
                'piso' => $dl['piso'] ?? null,
                'depto' => $dl['depto'] ?? null,
                'torre' => $dl['torre'] ?? null,
                'monoblock' => $dl['monoblock'] ?? null,
                'manzana' => $dl['manzana'] ?? null,
                'lote' => $dl['lote'] ?? null,
                'casa' => $dl['casa'] ?? null,
                'barrio' => $dl['barrio'] ?? null,
                'sector' => $dl['sector'] ?? null,
                'provincia_id' => $dl['provincia_id'],
                'departamento_id' => $dl['departamento_id'] ?? null,
                'coordenadas_gps' => $dl['coordenadas_gps'] ?? null,
            ]);

            $personaData = [
                'dni' => $datosPersona['dni'],
                'nombres' => $datosPersona['nombres'],
                'apellidos' => $datosPersona['apellidos'],
                'fecha_nacimiento' => $datosPersona['fecha_nacimiento'],
                'genero' => $datosPersona['genero'] ?? $request->input('persona.genero') ?? 'otro',
                'alias' => $datosPersona['alias'] ?? $request->input('persona.alias'),
                'nacionalidad' => $datosPersona['nacionalidad'] ?? $request->input('persona.nacionalidad') ?? 'Argentina',
                'estado_civil' => $datosPersona['estado_civil'] ?? $request->input('persona.estado_civil'),
                'observaciones' => $datosPersona['observaciones'] ?? $request->input('persona.observaciones'),
                'domicilio_id' => $domicilio->id,
            ];

            if ($request->hasFile('foto')) {
                $rutaFoto = $request->file('foto')->store('fotos_personas', 'public');
                $personaData['foto'] = $rutaFoto;
            }

            $persona = Persona::create($personaData);

            // Pivot: situacion_procesal, pedido_captura, observaciones
            $situacion = strtoupper(trim((string) $request->input('pivot.situacion_procesal')));
            $pedidoCaptura = $request->boolean('pivot.pedido_captura');
            if ($situacion !== 'NO HALLADO') {
                $pedidoCaptura = false;
            }
            $obsPivot = $request->input('pivot.observaciones');

            $procedimiento->personas()->attach($persona->id, [
                'situacion_procesal' => $situacion,
                'pedido_captura' => $pedidoCaptura,
                'observaciones' => $obsPivot,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Establecer el id para redirección
            $procId = $procedimiento->id;
        });

        return redirect()
            ->route('carga.create', ['procedimiento_id' => $procId, 'tab' => 'vinculados'])
            ->withInput([])
            ->with('success', 'Vinculado agregado correctamente.');
    }

    public function vincularDomicilio(Request $request)
    {
        $procedimiento = null;
        $procedimientoId = $request->input('procedimiento_id');
        $procId = null;

        DB::transaction(function () use ($request, &$procedimiento, $procedimientoId, &$procId) {
            // Asegurar Procedimiento (crear si no viene)
            if (!$procedimientoId) {
                $datosProc = $request->validate([
                    'legajo_fiscal' => 'required|string|max:50',
                    'caratula' => 'required|string',
                    'fecha_procedimiento' => 'required|date|before_or_equal:today',
                    'hora_procedimiento' => 'nullable|date_format:H:i',
                    'orden_judicial' => 'required|in:Detención en caso de secuestro positivo,Detención directa,Notificación al acusado,Secuestro y notificación',
                ]);

                $procedimiento = Procedimiento::create([
                    'legajo_fiscal' => $datosProc['legajo_fiscal'],
                    'caratula' => $datosProc['caratula'],
                    'fecha_procedimiento' => $datosProc['fecha_procedimiento'],
                    'hora_procedimiento' => $datosProc['hora_procedimiento'] ?? null,
                    'orden_judicial' => $datosProc['orden_judicial'],
                    'brigada_id' => optional(Auth::user())->brigada_id,
                    'usuario_id' => Auth::id(),
                ]);
            } else {
                $procedimiento = Procedimiento::findOrFail($procedimientoId);
            }

            // Validar datos de domicilio del hecho
            $datosHecho = $request->validate([
                'hecho.provincia_id' => 'required|exists:provincias,id',
                'hecho.departamento_id' => 'nullable|exists:departamentos,id',
                'hecho.calle' => 'required|string|max:150',
                'hecho.numero' => 'nullable|integer',
                'hecho.piso' => 'nullable|string|max:20',
                'hecho.depto' => 'nullable|string|max:20',
                'hecho.torre' => 'nullable|string|max:50',
                'hecho.monoblock' => 'nullable|string|max:50',
                'hecho.manzana' => 'nullable|string|max:50',
                'hecho.lote' => 'nullable|string|max:50',
                'hecho.casa' => 'nullable|string|max:50',
                'hecho.barrio' => 'nullable|string|max:100',
                'hecho.sector' => 'nullable|string|max:100',
                'hecho.coordenadas_gps' => 'nullable|string',
            ]);

            $h = data_get($datosHecho, 'hecho', []);
            $domHecho = Domicilio::create([
                'calle' => $h['calle'],
                'numero' => $h['numero'] ?? null,
                'piso' => $h['piso'] ?? null,
                'depto' => $h['depto'] ?? null,
                'torre' => $h['torre'] ?? null,
                'monoblock' => $h['monoblock'] ?? null,
                'manzana' => $h['manzana'] ?? null,
                'lote' => $h['lote'] ?? null,
                'casa' => $h['casa'] ?? null,
                'barrio' => $h['barrio'] ?? null,
                'sector' => $h['sector'] ?? null,
                'provincia_id' => $h['provincia_id'],
                'departamento_id' => $h['departamento_id'] ?? null,
                'coordenadas_gps' => $h['coordenadas_gps'] ?? null,
            ]);

            $procedimiento->domicilios()->attach([$domHecho->id => ['created_at' => now(), 'updated_at' => now()]]);

            $procId = $procedimiento->id;
        });

        return redirect()->route('carga.create', ['procedimiento_id' => $procId, 'tab' => 'ubicacion'])
            ->withInput([])
            ->with('success', 'Domicilio del hecho agregado correctamente.');
    }
}
