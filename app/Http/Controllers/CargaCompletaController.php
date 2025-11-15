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
    public function create()
    {
        $brigadas = Brigada::orderBy('nombre')->get();
        $provincias = Provincia::orderBy('nombre')->get();
        $departamentos = Departamento::orderBy('nombre')->get();

        return view('cargas.create', compact(
            'brigadas',
            'provincias',
            'departamentos'
        ));
    }

    public function store(Request $request)
    {
        $procedimiento = null;
        $persona = null;
        $domicilioLegal = null;
        $domiciliosAllanadosIds = [];

        DB::transaction(function () use ($request, &$procedimiento, &$persona, &$domicilioLegal, &$domiciliosAllanadosIds) {
            // Paso A: Procedimiento
            $datosProc = $request->validate([
                'legajo_fiscal' => 'required|string|max:50',
                'caratula' => 'required|string',
                'fecha_procedimiento' => 'required|date',
                'hora_procedimiento' => 'nullable|date_format:H:i',
                'brigada_id' => 'required|exists:brigadas,id',
            ]);

            $procedimiento = Procedimiento::create([
                'legajo_fiscal' => $datosProc['legajo_fiscal'],
                'caratula' => $datosProc['caratula'],
                'fecha_procedimiento' => $datosProc['fecha_procedimiento'],
                'hora_procedimiento' => $datosProc['hora_procedimiento'] ?? null,
                'brigada_id' => $datosProc['brigada_id'],
                'usuario_id' => Auth::id(),
                // Defaults coherentes con el controlador actual
                'orden_secuestro' => false,
                'orden_detencion' => false,
                'resultado_secuestro' => 'no_aplica',
                'resultado_detencion' => 'no_aplica',
            ]);

            // Paso B: Persona y Domicilio Legal
            // Validación principal (campos básicos)
            $datosPersonaBasicos = $request->validate([
                'dni' => 'required|string|size:8|unique:personas,dni',
                'nombres' => 'required|string|max:100',
                'apellidos' => 'required|string|max:100',
                'fecha_nacimiento' => 'required|date',
                'foto' => 'nullable|image|max:2048',
            ]);

            // Validación extendida (campos anidados persona.*)
            $datosPersonaExtra = $request->validate([
                'persona.genero' => 'nullable|in:masculino,femenino,otro',
                'persona.alias' => 'nullable|string|max:100',
                'persona.nacionalidad' => 'nullable|string|max:50',
                'persona.estado_civil' => 'nullable|in:soltero,casado,divorciado,viudo,concubinato',
                'persona.observaciones' => 'nullable|string',
            ]);

            $validatedDom = $request->validate([
                'domicilio_legal.calle' => 'required|string|max:150',
                'domicilio_legal.numero' => 'nullable|string|max:20',
                'domicilio_legal.barrio' => 'nullable|string|max:100',
                'domicilio_legal.provincia_id' => 'required|exists:provincias,id',
                'domicilio_legal.departamento_id' => 'nullable|exists:departamentos,id',
            ]);

            $dl = data_get($validatedDom, 'domicilio_legal', []);

            $domicilioLegal = Domicilio::create([
                'calle' => $dl['calle'],
                'numero' => $dl['numero'] ?? null,
                'barrio' => $dl['barrio'] ?? null,
                'provincia_id' => $dl['provincia_id'],
                'departamento_id' => $dl['departamento_id'] ?? null,
            ]);

            // Foto persona
            $personaData = [
                'dni' => $datosPersonaBasicos['dni'],
                'nombres' => $datosPersonaBasicos['nombres'],
                'apellidos' => $datosPersonaBasicos['apellidos'],
                'fecha_nacimiento' => $datosPersonaBasicos['fecha_nacimiento'],
                'genero' => data_get($datosPersonaExtra, 'persona.genero') ?? 'otro',
                'alias' => data_get($datosPersonaExtra, 'persona.alias'),
                'nacionalidad' => data_get($datosPersonaExtra, 'persona.nacionalidad') ?: 'Argentina',
                'estado_civil' => data_get($datosPersonaExtra, 'persona.estado_civil'),
                'observaciones' => data_get($datosPersonaExtra, 'persona.observaciones'),
            ];
            if ($request->hasFile('foto')) {
                $rutaFoto = $request->file('foto')->store('fotos_personas', 'public');
                $personaData['foto'] = $rutaFoto;
            }

            $persona = Persona::create(array_merge($personaData, [
                'domicilio_id' => $domicilioLegal->id,
            ]));

            // Vincular persona ↔ domicilio legal (pivote de inteligencia)
                // Removed the pivot attachment for legal domicile

            // Paso C: Domicilios allanados (array)
            // Validación de array de domicilios allanados
            $request->validate([
                'domicilios_allanados' => 'array',
                'domicilios_allanados.*.calle_allanada' => 'nullable|string|max:150',
                'domicilios_allanados.*.numero_allanada' => 'nullable|string|max:20',
                'domicilios_allanados.*.provincia_id' => 'nullable|exists:provincias,id',
                'domicilios_allanados.*.departamento_id' => 'nullable|exists:departamentos,id',
                'domicilios_allanados.*.latitud' => 'nullable|numeric',
                'domicilios_allanados.*.longitud' => 'nullable|numeric',
            ]);

            $doms = (array) $request->input('domicilios_allanados', []);
            foreach ($doms as $item) {
                // Validación item a item (tolerante: algunos campos opcionales)
                $calle = $item['calle_allanada'] ?? null;
                if (!$calle) {
                    continue; // saltar vacíos
                }
                $lat = $item['latitud'] ?? null;
                $lng = $item['longitud'] ?? null;
                $coords = ($lat && $lng) ? (trim($lat) . ',' . trim($lng)) : null;

                $domAllanado = Domicilio::create([
                    'calle' => $calle,
                    'numero' => $item['numero_allanada'] ?? null,
                    'provincia_id' => $item['provincia_id'] ?? null,
                    'departamento_id' => $item['departamento_id'] ?? null,
                    'sector' => $item['sector'] ?? null,
                    'manzana' => $item['manzana'] ?? null,
                    'lote' => $item['lote'] ?? null,
                    'casa' => $item['casa'] ?? null,
                    'monoblock' => $item['monoblock'] ?? null,
                    'torre' => $item['torre'] ?? null,
                    'piso' => $item['piso'] ?? null,
                    'depto' => $item['depto'] ?? null,
                    'coordenadas_gps' => $coords,
                ]);

                $domiciliosAllanadosIds[] = $domAllanado->id;
            }

            // Paso D: Vinculaciones finales
            $procedimiento->personas()->attach($persona->id, [
                'situacion_procesal' => 'notificado',
                'observaciones' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            if (!empty($domiciliosAllanadosIds)) {
                $attachData = [];
                $now = now();
                foreach ($domiciliosAllanadosIds as $did) {
                    $attachData[$did] = ['created_at' => $now, 'updated_at' => $now];
                }
                $procedimiento->domicilios()->attach($attachData);
            }
        });

        return redirect()->route('procedimientos.show', $procedimiento)
            ->with('success', 'Carga completa creada exitosamente.');
    }
}
