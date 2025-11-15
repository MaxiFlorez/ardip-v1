<?php

namespace App\Http\Controllers;

use App\Models\Domicilio;
use App\Models\Provincia; // <--- IMPORTAR EL MODELO PROVINCIA
use App\Models\Departamento; // <--- IMPORTAR EL MODELO DEPARTAMENTO
use Illuminate\Http\Request;
use App\Http\Requests\DomicilioRequest;

class DomicilioController extends Controller
{
    /**
     * Muestra el listado de domicilios.
     */
    public function index()
    {
        // Eager loading reducido (solo provincia y departamento)
        $domicilios = Domicilio::with(['departamento', 'provincia'])
            ->orderBy('departamento_id')
            ->orderBy('calle')
            ->paginate(15);

        return view('domicilios.index', compact('domicilios'));
    }

    /**
     * Muestra el formulario para crear un nuevo domicilio.
     */
    public function create()
    {
    // 3. PROVINCIAS Y DEPARTAMENTOS PARA SELECTS (sin barrios)
        $provincias = Provincia::orderBy('nombre')->get();
        $departamentos = Departamento::orderBy('nombre')->get();
        // Provincia por defecto: San Juan
        $provinciaDefaultId = Provincia::where('nombre', 'San Juan')->value('id');
    return view('domicilios.create', compact('provincias', 'departamentos', 'provinciaDefaultId'));
    }

    /**
     * Guarda el nuevo domicilio en la base de datos.
     */
    public function store(DomicilioRequest $request)
    {
        // Validación centralizada en DomicilioRequest
        $datosValidados = $request->validated();

        // Si la provincia seleccionada NO es San Juan, se fuerza departamento_id a null
        $provinciaSanJuanId = Provincia::where('nombre', 'San Juan')->value('id');
        if ($datosValidados['provincia_id'] !== $provinciaSanJuanId) {
            $datosValidados['departamento_id'] = null;
        }

        Domicilio::create($datosValidados);

        return redirect()->route('domicilios.index')
                         ->with('success', 'Domicilio agregado exitosamente.');
    }

    /**
     * Muestra el detalle de un domicilio específico.
     */
    public function show(Domicilio $domicilio)
    {
        // 5. CARGAMOS LAS RELACIONES (sin barrio relacional)
    $domicilio->load('procedimientos', 'provincia', 'departamento'); 
        return view('domicilios.show', compact('domicilio'));
    }

    /**
     * Muestra el formulario para editar un domicilio.
     */
    public function edit(Domicilio $domicilio)
    {
    // 6. PROVINCIAS Y DEPARTAMENTOS (sin barrios)
        $provincias = Provincia::orderBy('nombre')->get();
        $departamentos = Departamento::orderBy('nombre')->get();
        // Provincia por defecto: San Juan (por si el domicilio no tiene provincia asignada)
        $provinciaDefaultId = Provincia::where('nombre', 'San Juan')->value('id');
    return view('domicilios.edit', compact('domicilio', 'provincias', 'departamentos', 'provinciaDefaultId'));
    }

    /**
     * Actualiza el domicilio en la base de datos.
     */
    public function update(DomicilioRequest $request, Domicilio $domicilio)
    {
        // Validación centralizada en DomicilioRequest
        $datosValidados = $request->validated();

        // Si la provincia cambiada NO es San Juan, se limpia departamento_id
        $provinciaSanJuanId = Provincia::where('nombre', 'San Juan')->value('id');
        if ($datosValidados['provincia_id'] !== $provinciaSanJuanId) {
            $datosValidados['departamento_id'] = null;
        }

        $domicilio->update($datosValidados);

        return redirect()->route('domicilios.index')
                         ->with('success', 'Domicilio actualizado exitosamente.');
    }

    /**
     * Elimina el domicilio de la base de datos.
     */
    public function destroy(Domicilio $domicilio)
    {
        $domicilio->delete();

        return redirect()->route('domicilios.index')
                         ->with('success', 'Domicilio eliminado exitosamente.');
    }
}