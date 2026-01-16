<?php

namespace App\Http\Controllers;

use App\Models\Domicilio;
use Illuminate\Http\Request;
use App\Http\Requests\StoreDomicilioRequest;
use App\Http\Requests\UpdateDomicilioRequest;

class DomicilioController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:operativo-escritura')->only(['create', 'store', 'edit', 'update', 'destroy']);
        $this->middleware('can:acceso-operativo')->only(['index', 'show']);
    }

    /**
     * Lista domicilios paginados
     */
    public function index()
    {
        $domicilios = Domicilio::orderBy('departamento')->orderBy('calle')->paginate(15);
        return view('domicilios.index', compact('domicilios'));
    }

    /**
     * Formulario de creación
     */
    public function create(Request $request)
    {
        $procedimientoId = $request->query('procedimiento_id');
        return view('domicilios.create', compact('procedimientoId'));
    }

    /**
     * Guarda un nuevo domicilio
     */
    public function store(StoreDomicilioRequest $request)
    {
        $validated = $request->validated();

        $domicilio = Domicilio::create($validated);

        // Lógica de retorno inteligente
        if ($request->filled('procedimiento_id')) {
            $procedimientoId = $request->input('procedimiento_id');
            
            // Vincular automáticamente a la tabla pivote
            $domicilio->procedimientos()->attach($procedimientoId);
            
            return redirect()
                ->route('procedimientos.show', $procedimientoId)
                ->with('success', '✅ Domicilio creado y vinculado al procedimiento correctamente.');
        }

        // Comportamiento normal
        return redirect()->route('domicilios.index')
                         ->with('success', 'Domicilio agregado exitosamente.');
    }

    /**
     * Muestra un domicilio (carga relaciones si aplica)
     */
    public function show(Domicilio $domicilio)
    {
        $domicilio->load('procedimientos');
        return view('domicilios.show', compact('domicilio'));
    }

    /**
     * Formulario de edición
     */
    public function edit(Domicilio $domicilio)
    {
        return view('domicilios.edit', compact('domicilio'));
    }

    /**
     * Actualiza el domicilio
     */
    public function update(UpdateDomicilioRequest $request, Domicilio $domicilio)
    {
        $validated = $request->validated();

        $domicilio->update($validated);

        return redirect()->route('domicilios.index')
                         ->with('success', 'Domicilio actualizado exitosamente.');
    }

    /**
     * Elimina un domicilio
     */
    public function destroy(Domicilio $domicilio)
    {
        $domicilio->delete();

        return redirect()->route('domicilios.index')
                         ->with('success', 'Domicilio eliminado exitosamente.');
    }
}
