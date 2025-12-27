<?php

namespace App\Http\Controllers;

use App\Models\Procedimiento;
use App\Models\Persona;
use App\Models\Domicilio;
use App\Models\Brigada;
use Illuminate\Http\Request;
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
        $query = Procedimiento::with('brigada');

        if ($buscar = $request->get('buscar')) {
            $query->where(function ($q) use ($buscar) {
                $q->where('legajo_fiscal', 'like', "%{$buscar}%")
                  ->orWhere('caratula', 'like', "%{$buscar}%");
            });
        }

        $procedimientos = $query->orderBy('fecha_procedimiento', 'desc')
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
     * Store: valida y guarda
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'legajo_fiscal' => 'required|string|max:255',
            'caratula' => 'required|string|max:500',
            'fecha_procedimiento' => 'required|date',
            'ufi' => 'nullable|string|max:255',
            'orden_judicial' => 'nullable|string|max:255',
            'brigada_id' => 'nullable|exists:brigadas,id',
        ]);

        $validated['usuario_id'] = Auth::id();
        $validated['brigada_id'] = $validated['brigada_id'] ?? Auth::user()->brigada_id ?? null;

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
     * Update: valida y actualiza
     */
    public function update(Request $request, Procedimiento $procedimiento)
    {
        $validated = $request->validate([
            'legajo_fiscal' => 'required|string|max:255',
            'caratula' => 'required|string|max:500',
            'fecha_procedimiento' => 'required|date',
            'ufi' => 'nullable|string|max:255',
            'orden_judicial' => 'nullable|string|max:255',
            'brigada_id' => 'nullable|exists:brigadas,id',
        ]);

        $procedimiento->update($validated);

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
}
