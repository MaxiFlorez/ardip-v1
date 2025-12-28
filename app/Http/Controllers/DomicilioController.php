<?php

namespace App\Http\Controllers;

use App\Models\Domicilio;
use Illuminate\Http\Request;

class DomicilioController extends Controller
{
    public function __construct()
    {
        // Escritura: crear/editar/borrar
        $this->middleware('can:panel-carga')->only(['create', 'store', 'edit', 'update', 'destroy']);

        // Lectura: index y show
        $this->middleware('can:panel-consulta')->only(['index', 'show']);
    }

    /**
     * Lista domicilios paginados
     */
    public function index()
    {
        $this->authorize('panel-consulta');
        $domicilios = Domicilio::orderBy('departamento')->orderBy('calle')->paginate(15);
        return view('domicilios.index', compact('domicilios'));
    }

    /**
     * Formulario de creación
     */
    public function create()
    {
        $this->authorize('panel-carga');
        return view('domicilios.create');
    }

    /**
     * Guarda un nuevo domicilio
     */
    public function store(Request $request)
    {
        $this->authorize('panel-carga');
        $validated = $request->validate([
            'departamento' => 'required|string|max:100',
            'provincia' => 'nullable|string|max:100',
            'calle' => 'nullable|string|max:255',
            'numero' => 'nullable|string|max:20',
            'piso' => 'nullable|string|max:10',
            'depto' => 'nullable|string|max:10',
            'torre' => 'nullable|string|max:10',
            'monoblock' => 'nullable|string|max:100',
            'manzana' => 'nullable|string|max:20',
            'lote' => 'nullable|string|max:20',
            'casa' => 'nullable|string|max:20',
            'barrio' => 'nullable|string|max:100',
            'sector' => 'nullable|string|max:100',
            'coordenadas_gps' => 'nullable|string|max:100',
        ]);

        Domicilio::create($validated);

        return redirect()->route('domicilios.index')
                         ->with('success', 'Domicilio agregado exitosamente.');
    }

    /**
     * Muestra un domicilio (carga relaciones si aplica)
     */
    public function show(Domicilio $domicilio)
    {
        $this->authorize('panel-consulta');
        $domicilio->load('procedimientos');
        return view('domicilios.show', compact('domicilio'));
    }

    /**
     * Formulario de edición
     */
    public function edit(Domicilio $domicilio)
    {
        $this->authorize('panel-carga');
        return view('domicilios.edit', compact('domicilio'));
    }

    /**
     * Actualiza el domicilio
     */
    public function update(Request $request, Domicilio $domicilio)
    {
        $this->authorize('panel-carga');
        $validated = $request->validate([
            'departamento' => 'required|string|max:100',
            'provincia' => 'nullable|string|max:100',
            'calle' => 'nullable|string|max:255',
            'numero' => 'nullable|string|max:20',
            'piso' => 'nullable|string|max:10',
            'depto' => 'nullable|string|max:10',
            'torre' => 'nullable|string|max:10',
            'monoblock' => 'nullable|string|max:100',
            'manzana' => 'nullable|string|max:20',
            'lote' => 'nullable|string|max:20',
            'casa' => 'nullable|string|max:20',
            'barrio' => 'nullable|string|max:100',
            'sector' => 'nullable|string|max:100',
            'coordenadas_gps' => 'nullable|string|max:100',
        ]);

        $domicilio->update($validated);

        return redirect()->route('domicilios.index')
                         ->with('success', 'Domicilio actualizado exitosamente.');
    }

    /**
     * Elimina un domicilio
     */
    public function destroy(Domicilio $domicilio)
    {
        $this->authorize('panel-carga');
        $domicilio->delete();

        return redirect()->route('domicilios.index')
                         ->with('success', 'Domicilio eliminado exitosamente.');
    }
}
