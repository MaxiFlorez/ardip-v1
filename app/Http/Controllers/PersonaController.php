<?php

namespace App\Http\Controllers;

use App\Models\Persona;
use App\Http\Requests\StorePersonaRequest;
use App\Http\Requests\UpdatePersonaRequest;
use App\Traits\HandlesFileUploads;
use Illuminate\Http\Request;

class PersonaController extends Controller
{
    use HandlesFileUploads;
    public function __construct()
    {
        $this->middleware('can:operativo-escritura')->only(['create', 'store', 'edit', 'update', 'destroy']);
        $this->middleware('can:acceso-operativo')->only(['index', 'show']);
    }

    /**
     * Listado con búsqueda inteligente y filtros combinados
     */
    public function index(Request $request)
    {
        $query = Persona::query()->with('aliases');

        // Búsqueda por nombres, apellidos o alias
        if ($request->filled('buscar')) {
            $buscar = trim((string) $request->buscar);
            $query->where(function($q) use ($buscar) {
                $q->where('nombres', 'LIKE', "%{$buscar}%")
                  ->orWhere('apellidos', 'LIKE', "%{$buscar}%")
                  ->orWhereHas('aliases', function($qa) use ($buscar) {
                      $qa->where('alias', 'LIKE', "%{$buscar}%");
                  });
            });
        }

        // Filtro por zona (departamento) a través de domicilios
        if ($request->filled('departamento')) {
            $query->whereHas('domicilios', function($q) use ($request) {
                $q->where('departamento', $request->departamento);
            });
        }

        // Filtro por rango de edad aproximado
        if ($request->filled('edad_min') && $request->filled('edad_max')) {
            $hoy = now();
            $fechaMax = (clone $hoy)->subYears((int) $request->edad_min);
            $fechaMin = (clone $hoy)->subYears(((int) $request->edad_max) + 1);
            $query->whereBetween('fecha_nacimiento', [$fechaMin, $fechaMax]);
        }

        $personas = $query->orderBy('apellidos')->paginate(20);

        return view('personas.index', compact('personas'));
    }

    /**
     * Muestra el formulario para crear una nueva persona
     */
    public function create()
    {
        return view('personas.create');
    }

    /**
     * Guarda una nueva persona en la base de datos
     */
    public function store(StorePersonaRequest $request)
    {
        $validated = $request->validated();

        // Procesar foto si existe usando el trait
        if ($request->hasFile('foto')) {
            $validated['foto'] = $this->uploadFile($request->file('foto'), 'fotos_personas');
        }

        // Alias del request (se procesan por separado para no persistir en personas)
        $aliasInput = $request->input('alias', []);
        unset($validated['alias']);

        // Crear la persona
        $persona = Persona::create($validated);

        // Guardar alias
        if (!empty($aliasInput) && is_array($aliasInput)) {
            foreach ($aliasInput as $alias) {
                if (!empty(trim((string) $alias))) {
                    $persona->aliases()->create(['alias' => trim((string) $alias)]);
                }
            }
        }

        // Redirigir al detalle con mensaje de éxito
        return redirect()
            ->route('personas.show', $persona)
            ->with('success', '✅ Persona creada correctamente con foto.');
    }

    /**
     * Muestra los detalles de una persona específica
     */
    public function show(Persona $persona)
    {
        // Cargar las relaciones
        $persona->load('procedimientos');
        
        return view('personas.show', compact('persona'));
    }

    /**
     * Muestra el formulario para editar una persona
     */
    public function edit(Persona $persona)
    {
        return view('personas.edit', compact('persona'));
    }

    /**
     * Actualiza los datos de una persona en la base de datos
     */
    public function update(UpdatePersonaRequest $request, Persona $persona)
    {
        $validated = $request->validated();

        // Manejar la foto si existe usando el trait
        if ($request->hasFile('foto')) {
            $validated['foto'] = $this->updateFile(
                $request->file('foto'),
                $persona->foto,
                'fotos_personas'
            );
        }

        // Alias del request (se procesan por separado)
        $aliasInput = $request->input('alias', []);
        unset($validated['alias']);

        // Actualizar la persona
        $persona->update($validated);

        // Sincronizar alias (eliminar y recrear)
        if (is_array($aliasInput)) {
            $persona->aliases()->delete();
            foreach ($aliasInput as $alias) {
                if (!empty(trim((string) $alias))) {
                    $persona->aliases()->create(['alias' => trim((string) $alias)]);
                }
            }
        }

        // Redirigir con mensaje de éxito
        return redirect()->route('personas.show', $persona)
            ->with('success', '✅ Persona actualizada correctamente.');
    }

    /**
     * Elimina una persona de la base de datos
     */
    public function destroy(Persona $persona)
    {
        // Eliminar foto si existe usando el trait
        $this->deleteFile($persona->foto);

        // Eliminar persona
        $persona->delete();

        // Redirigir con mensaje de éxito
        return redirect()->route('personas.index')
            ->with('success', 'Persona eliminada exitosamente.');
    }
}