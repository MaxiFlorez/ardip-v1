<?php

namespace App\Http\Controllers;

use App\Models\Domicilio;
use Illuminate\Http\Request;

class DomicilioController extends Controller
{
<<<<<<< HEAD
    public function __construct()
    {
        // Seguridad: Carga/Admin editan, Todos consultan
        $this->middleware('can:panel-carga')->only(['create', 'store', 'edit', 'update', 'destroy']);
        $this->middleware('can:panel-consulta')->only(['index', 'show']);
    }

    public function index()
    {
        // Placeholder
        return view('dashboard');
=======
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // 2. BUSCAR DOMICILIOS (ordenados por departamento)
        $domicilios = Domicilio::orderBy('departamento')->orderBy('calle')->paginate(15);

        // 3. ENVIARLOS A LA VISTA
        return view('domicilios.index', compact('domicilios'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('domicilios.create');
    }

    /**
     * Guarda el nuevo domicilio en la base de datos.
     */
    public function store(Request $request)
    {
        // 1. VALIDACIÓN (Basada en la migración de SESION_3.md)
        $datosValidados = $request->validate([
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

        // 2. GUARDAR
        // Usamos el modelo Domicilio que ya tiene $guarded = ['id']
        Domicilio::create($datosValidados);

        // 3. REDIRIGIR
        return redirect()->route('domicilios.index')
                         ->with('success', 'Domicilio agregado exitosamente.');
    }

    public function show(Domicilio $domicilio)
    {
        // Cargamos las relaciones (aunque domicilio aún no tiene, es buena práctica)
        $domicilio->load('procedimientos'); // Veremos los procedimientos asociados
        return view('domicilios.show', compact('domicilio'));
    }

    /**
     * Muestra el formulario para editar un domicilio.
     */
    public function edit(Domicilio $domicilio)
    {
        return view('domicilios.edit', compact('domicilio'));
    }

    /**
     * Actualiza el domicilio en la base de datos.
     */
    public function update(Request $request, Domicilio $domicilio)
    {
        // 1. VALIDACIÓN (Igual que 'store', pero aflojamos 'provincia')
        $datosValidados = $request->validate([
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

        // 2. ACTUALIZAR
        $domicilio->update($datosValidados);

        // 3. REDIRIGIR
        return redirect()->route('domicilios.index')
                         ->with('success', 'Domicilio actualizado exitosamente.');
    }

    /**
     * Elimina el domicilio de la base de datos.
     */
    public function destroy(Domicilio $domicilio)
    {
        // (Podemos agregar lógica de restricción si está vinculado a un procedimiento)
        
        $domicilio->delete();

        return redirect()->route('domicilios.index')
                         ->with('success', 'Domicilio eliminado exitosamente.');
>>>>>>> 1be5c15e951f017a99140e5a308014f89bf3fbf1
    }
}
