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
        $procedimiento->load('brigada', 'personas', 'domicilios');

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
        // Cargamos las brigadas para el menú desplegable
        $brigadas = Brigada::orderBy('nombre')->get();

        // Enviamos el procedimiento y las brigadas a la vista
        return view('procedimientos.edit', compact('procedimiento', 'brigadas'));
    }

    /**
     * Actualiza el procedimiento en la base de datos.
     */
    public function update(Request $request, Procedimiento $procedimiento)
    {
        // 1. VALIDACIÓN (similar a 'store')
        $request->validate([
            'legajo_fiscal' => 'required|string|max:50',
            'caratula' => 'required|string',
            'fecha_procedimiento' => 'required|date',
            'brigada_id' => 'required|exists:brigadas,id',
            'orden_secuestro' => 'nullable',
            'orden_detencion' => 'nullable',
            // Añadimos validación para los resultados (basado en la migración)
            'resultado_secuestro' => 'required_if:orden_secuestro,true|in:positivo,negativo,no_aplica',
            'resultado_detencion' => 'required_if:orden_detencion,true|in:positivo,negativo,no_aplica',
        ]);

        // 2. PREPARAR DATOS
        $datos = $request->all();

        // Convertir checkboxes
        $datos['orden_secuestro'] = $request->has('orden_secuestro');
        $datos['orden_detencion'] = $request->has('orden_detencion');

        // Lógica de resultados (si se quita la orden, el resultado vuelve a 'no_aplica')
        $datos['resultado_secuestro'] = $datos['orden_secuestro'] ? $request->input('resultado_secuestro', 'negativo') : 'no_aplica';
        $datos['resultado_detencion'] = $datos['orden_detencion'] ? $request->input('resultado_detencion', 'negativo') : 'no_aplica';

        // 3. ACTUALIZAR EL PROCEDIMIENTO
        $procedimiento->update($datos);

        // 4. REDIRIGIR A LA VISTA DE DETALLE
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
