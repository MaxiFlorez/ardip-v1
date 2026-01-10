<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProcedimientoRequest;
use App\Http\Requests\UpdateProcedimientoRequest;
use App\Http\Requests\VincularPersonaRequest;
use App\Http\Requests\VincularDomicilioRequest;
use App\Models\Brigada;
use App\Models\Domicilio;
use App\Models\Persona;
use App\Models\Procedimiento;
use App\Models\Ufi; // Importar el nuevo modelo
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProcedimientoController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:panel-carga')->only(['create', 'store', 'edit', 'update', 'destroy', 'vincularPersona', 'vincularDomicilio']);
        $this->middleware('can:panel-consulta')->only(['index', 'show']);
    }

    /**
     * Muestra el listado de procedimientos con búsqueda y paginación
     * Optimizado con Eager Loading para evitar problema N+1
     */
    public function index(Request $request)
    {
        // Obtén las listas para los selects del formulario de filtrado
        $ufis = Ufi::orderBy('nombre')->get();
        $brigadas = Brigada::orderBy('nombre')->get();

        // Aplica el Scope filtrar (que ignora campos nulos/vacíos automáticamente)
        // y devuelve un paginador incluso si está vacío
        $procedimientos = Procedimiento::filtrar($request->all())
            ->with(['ufi', 'brigada']) // Eager Loading para evitar problema N+1
            ->orderBy('fecha_procedimiento', 'desc') // Ordena por fecha descendente
            ->paginate(10) // Pagina de a 10 resultados
            ->withQueryString(); // Mantiene los filtros en los enlaces de paginación

        // Retorna la vista con todas las variables
        return view('procedimientos.index', compact('procedimientos', 'ufis', 'brigadas'));
    }

    /**
     * Muestra el formulario para crear un nuevo procedimiento
     */
    public function create()
    {
        $brigadas = Brigada::orderBy('nombre')->get();
        $ufis = Ufi::orderBy('nombre')->get(); // <-- Obtener UFIs
        return view('procedimientos.create', compact('brigadas', 'ufis')); // <-- Pasar UFIs a la vista
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
        
        // CRÍTICO: brigada_id es NOT NULL en BD
        // Fallar explícitamente si el usuario no tiene brigada asignada
        $brigada_id = Auth::user()->brigada_id;
        if ($brigada_id === null) {
            abort(403, 'No puedes crear procedimientos: tu usuario no tiene brigada asignada. Contacta al administrador.');
        }
        $validated['brigada_id'] = $brigada_id;

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
        // Validar acceso por brigada (solo para roles no-admin)
        /** @var \App\Models\User $user */
        $user = Auth::user();
        if (!$user->hasAnyRole(['super_admin', 'admin'])) {
            if ($procedimiento->brigada_id !== $user->brigada_id) {
                abort(403, 'No tienes acceso a procedimientos de otras brigadas.');
            }
        }

        $procedimiento->load(['personas', 'domicilios', 'usuario', 'brigada', 'ufi']); // <-- ufi añadido

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
        $ufis = Ufi::orderBy('nombre')->get(); // <-- Obtener UFIs
        return view('procedimientos.edit', compact('procedimiento', 'brigadas', 'ufis')); // <-- Pasar UFIs a la vista
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
    public function vincularPersona(VincularPersonaRequest $request, Procedimiento $procedimiento)
    {
        $validated = $request->validated();

        // Usar transacción para garantizar consistencia
        DB::transaction(function () use ($procedimiento, $validated, $request) {
            // Prepara los datos del pivote para la relación
            $pivot = [
                'situacion_procesal' => $validated['situacion_procesal'],
                'pedido_captura' => $request->boolean('pedido_captura'),
                'observaciones' => $validated['observaciones'] ?? null,
                'created_at' => now(),
                'updated_at' => now(),
            ];

            // Vincula la persona al procedimiento con los datos del pivote
            $procedimiento->personas()->syncWithoutDetaching([
                $validated['persona_id'] => $pivot,
            ]);
        });

        return redirect()
            ->route('procedimientos.show', $procedimiento)
            ->with('success', 'Persona vinculada correctamente.');
    }

    /**
     * Vincula un domicilio al procedimiento
     */
    public function vincularDomicilio(VincularDomicilioRequest $request, Procedimiento $procedimiento)
    {
        $validated = $request->validated();

        // Usar transacción para garantizar consistencia
        DB::transaction(function () use ($procedimiento, $validated) {
            $procedimiento->domicilios()->syncWithoutDetaching([
                $validated['domicilio_id'] => [
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ]);
        });

        return redirect()
            ->route('procedimientos.show', $procedimiento)
            ->with('success', 'Domicilio vinculado correctamente.');
    }

    /**
     * Genera un PDF con la ficha técnica del procedimiento
     */
    public function generarPdf(Procedimiento $procedimiento)
    {
        $procedimiento->load(['personas', 'domicilios', 'brigada', 'usuario', 'ufi']); // <-- ufi añadido

        $pdf = Pdf::loadView('procedimientos.pdf', [
            'procedimiento' => $procedimiento,
        ]);

        return $pdf->stream('ficha-tecnica.pdf');
    }
}
