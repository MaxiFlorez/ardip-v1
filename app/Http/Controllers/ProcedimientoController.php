<?php

namespace App\Http\Controllers;

use App\Models\Procedimiento;
use App\Models\Persona;
use App\Models\Domicilio;
use App\Models\Brigada;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Requests\StoreProcedimientoRequest;
use App\Http\Requests\UpdateProcedimientoRequest;
use Illuminate\Support\Facades\Auth;

class ProcedimientoController extends Controller
{
    public function __construct()
    {
        // Escritura: crear/editar/borrar/vincular
        $this->middleware('can:panel-carga')->only([
            'create', 'store', 'edit', 'update', 'destroy', 'vincularPersona', 'vincularDomicilio'
        ]);

        // Lectura: index y show
        $this->middleware('can:panel-consulta')->only(['index', 'show']);
    }

    /**
     * Index: lista procedimientos con búsqueda opcional
     */
    public function index(Request $request)
    {
        // Eager load personas y domicilios para evitar N+1, además de brigada
        $query = Procedimiento::with(['brigada', 'personas', 'domicilios']);

        // Aplicar scope de búsqueda (parámetro 'search' en la URL)
        $query = $query->buscar($request->get('search'));

        // Orden por fecha de creación descendente y paginación
        $procedimientos = $query->orderBy('created_at', 'desc')
                                ->paginate(10)
                                ->withQueryString();

        return view('procedimientos.index', compact('procedimientos'));
    }

    /**
     * Formulario para crear
     */
    public function create()
    {
        $brigadas = Brigada::orderBy('nombre')->get();
        return view('procedimientos.create', compact('brigadas'));
    }

    /**
     * Almacena un nuevo procedimiento usando Form Request
     */
    public function store(StoreProcedimientoRequest $request)
    {
        $validated = $request->validated();

        // Completar datos del contexto
        $validated['usuario_id'] = Auth::id();
        $validated['brigada_id'] = Auth::user()->brigada_id ?? null;

        $procedimiento = Procedimiento::create($validated);

        return redirect()->route('procedimientos.show', $procedimiento)
                         ->with('success', 'Procedimiento creado correctamente.');
    }

    /**
     * Show: carga relaciones y muestra
     */
    public function show(Procedimiento $procedimiento)
    {
        $procedimiento->load('personas', 'domicilios', 'usuario', 'brigada');

        $personasDisponibles = Persona::orderBy('apellidos')->get();
        $domiciliosDisponibles = Domicilio::orderBy('calle')->get();

        return view('procedimientos.show', compact('procedimiento', 'personasDisponibles', 'domiciliosDisponibles'));
    }

    /**
     * Formulario de edición
     */
    public function edit(Procedimiento $procedimiento)
    {
        $brigadas = Brigada::orderBy('nombre')->get();
        return view('procedimientos.edit', compact('procedimiento', 'brigadas'));
    }

    /**
     * Actualiza un procedimiento usando Form Request
     */
    public function update(UpdateProcedimientoRequest $request, Procedimiento $procedimiento)
    {
        $procedimiento->update($request->validated());

        return redirect()->route('procedimientos.show', $procedimiento)
                         ->with('success', 'Procedimiento actualizado correctamente.');
    }

    /**
     * Destroy: elimina
     */
    public function destroy(Procedimiento $procedimiento)
    {
        $procedimiento->delete();

        return redirect()->route('procedimientos.index')
                         ->with('success', 'Procedimiento eliminado correctamente.');
    }

    /**
     * Vincular una persona usando syncWithoutDetaching con datos en pivote
     */
    public function vincularPersona(Request $request, Procedimiento $procedimiento)
    {
        $datos = $request->validate([
            'persona_id' => 'required|exists:personas,id',
            'situacion_procesal' => 'required|in:detenido,notificado,no_hallado,contravencion',
            'pedido_captura' => 'sometimes|boolean',
            'observaciones' => 'nullable|string',
        ]);

        $pivot = [
            'situacion_procesal' => $datos['situacion_procesal'],
            'pedido_captura' => (bool) ($datos['pedido_captura'] ?? false),
            'observaciones' => $datos['observaciones'] ?? null,
            'created_at' => now(),
            'updated_at' => now(),
        ];

        $procedimiento->personas()->syncWithoutDetaching([
            $datos['persona_id'] => $pivot,
        ]);

        return redirect()->route('procedimientos.show', $procedimiento)
                         ->with('success', 'Persona vinculada correctamente.');
    }

    /**
     * Vincular un domicilio usando syncWithoutDetaching
     */
    public function vincularDomicilio(Request $request, Procedimiento $procedimiento)
    {
        $datos = $request->validate([
            'domicilio_id' => 'required|exists:domicilios,id',
        ]);

        $procedimiento->domicilios()->syncWithoutDetaching([
            $datos['domicilio_id'] => ['created_at' => now(), 'updated_at' => now()],
        ]);

        return redirect()->route('procedimientos.show', $procedimiento)
                         ->with('success', 'Domicilio vinculado correctamente.');
    }

    /**
     * Genera PDF con la ficha técnica del procedimiento
     */
    public function generarPdf(Procedimiento $procedimiento)
    {
        $procedimiento->load(['personas', 'domicilios', 'brigada', 'usuario']);

        $pdf = Pdf::loadView('procedimientos.pdf', [
            'procedimiento' => $procedimiento,
        ]);

        return $pdf->stream('ficha-tecnica.pdf');
    }
}
