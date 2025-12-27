<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProcedimientoRequest;
use App\Http\Requests\UpdateProcedimientoRequest;
use App\Models\Brigada;
use App\Models\Domicilio;
use App\Models\Persona;
use App\Models\Procedimiento;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProcedimientoController extends Controller
{
    /**
     * Constructor: define permisos de acceso mediante gates
     */
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
     * Muestra el listado de procedimientos con búsqueda y paginación
     * Optimizado con Eager Loading para evitar problema N+1
     */
    public function index(Request $request)
    {
        $procedimientos = Procedimiento::with(['brigada', 'personas', 'domicilios'])
            ->buscar($request->get('search'))
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();

        return view('procedimientos.index', compact('procedimientos'));
    }

    /**
     * Muestra el formulario para crear un nuevo procedimiento
     */
    public function create()
    {
        $brigadas = Brigada::orderBy('nombre')->get();
        return view('procedimientos.create', compact('brigadas'));
    }

    /**
     * Almacena un nuevo procedimiento en la base de datos
     * Utiliza Form Request para validación
     */
    public function store(StoreProcedimientoRequest $request)
    {
        $validated = $request->validated();

        // Asignar datos del contexto de autenticación
        $validated['usuario_id'] = Auth::id();
        $validated['brigada_id'] = Auth::user()->brigada_id ?? null;

        $procedimiento = Procedimiento::create($validated);

        return redirect()
            ->route('procedimientos.show', $procedimiento)
            ->with('success', 'Procedimiento creado correctamente.');
    }

    /**
     * Muestra los detalles de un procedimiento específico
     */
    public function show(Procedimiento $procedimiento)
    {
        $procedimiento->load(['personas', 'domicilios', 'usuario', 'brigada']);

        $personasDisponibles = Persona::orderBy('apellidos')->get();
        $domiciliosDisponibles = Domicilio::orderBy('calle')->get();

        return view('procedimientos.show', compact('procedimiento', 'personasDisponibles', 'domiciliosDisponibles'));
    }

    /**
     * Muestra el formulario para editar un procedimiento existente
     */
    public function edit(Procedimiento $procedimiento)
    {
        $brigadas = Brigada::orderBy('nombre')->get();
        return view('procedimientos.edit', compact('procedimiento', 'brigadas'));
    }

    /**
     * Actualiza un procedimiento existente
     * Utiliza Form Request para validación
     */
    public function update(UpdateProcedimientoRequest $request, Procedimiento $procedimiento)
    {
        $procedimiento->update($request->validated());

        return redirect()
            ->route('procedimientos.show', $procedimiento)
            ->with('success', 'Procedimiento actualizado correctamente.');
    }

    /**
     * Elimina un procedimiento de la base de datos
     */
    public function destroy(Procedimiento $procedimiento)
    {
        $procedimiento->delete();

        return redirect()
            ->route('procedimientos.index')
            ->with('success', 'Procedimiento eliminado correctamente.');
    }

    /**
     * Vincula una persona al procedimiento con datos de pivote
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

        return redirect()
            ->route('procedimientos.show', $procedimiento)
            ->with('success', 'Persona vinculada correctamente.');
    }

    /**
     * Vincula un domicilio al procedimiento
     */
    public function vincularDomicilio(Request $request, Procedimiento $procedimiento)
    {
        $datos = $request->validate([
            'domicilio_id' => 'required|exists:domicilios,id',
        ]);

        $procedimiento->domicilios()->syncWithoutDetaching([
            $datos['domicilio_id'] => [
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        return redirect()
            ->route('procedimientos.show', $procedimiento)
            ->with('success', 'Domicilio vinculado correctamente.');
    }

    /**
     * Genera un PDF con la ficha técnica del procedimiento
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
