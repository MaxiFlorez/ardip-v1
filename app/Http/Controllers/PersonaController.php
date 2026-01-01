<?php

namespace App\Http\Controllers;

use App\Models\Persona;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage; // ← NUEVO

class PersonaController extends Controller
{
    public function __construct()
    {
        // Solo los que tienen permiso de 'panel-carga' pueden CREAR, EDITAR o BORRAR
        $this->middleware('can:panel-carga')->only(['create', 'store', 'edit', 'update', 'destroy']);

        // Solo los que tienen permiso de 'panel-consulta' pueden VER listas y detalles
        $this->middleware('can:panel-consulta')->only(['index', 'show']);
    }

    // ... resto del código
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
    public function store(Request $request)
    {
        // Validaciones (incluye mimes y fecha antes de hoy)
        $validated = $request->validate([
            'dni' => 'required|string|max:8|unique:personas,dni',
            'nombres' => 'required|string|max:100',
            'apellidos' => 'required|string|max:100',
            'fecha_nacimiento' => 'required|date|before:today',
            'genero' => 'required|in:masculino,femenino,otro',
            // Alias como array de strings
            'alias' => 'nullable|array',
            'alias.*' => 'nullable|string|max:100',
            'nacionalidad' => 'nullable|string|max:50',
            'estado_civil' => 'nullable|in:soltero,casado,divorciado,viudo,concubinato',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'observaciones' => 'nullable|string',
        ]);

        // Procesar foto si existe (storage/app/public/fotos_personas)
        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('fotos_personas', 'public');
            $validated['foto'] = $path;
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
    public function update(Request $request, Persona $persona)
    {
        // Validar los datos (DNI único excepto el actual)
        $validated = $request->validate([
            'dni' => 'required|string|max:8|unique:personas,dni,' . $persona->id,
            'nombres' => 'required|string|max:100',
            'apellidos' => 'required|string|max:100',
            'fecha_nacimiento' => 'required|date|before:today',
            'genero' => 'required|in:masculino,femenino,otro',
            // Alias como array
            'alias' => 'nullable|array',
            'alias.*' => 'nullable|string|max:100',
            'nacionalidad' => 'nullable|string|max:50',
            'estado_civil' => 'nullable|in:soltero,casado,divorciado,viudo,concubinato',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'observaciones' => 'nullable|string',
        ]);

        // Manejar la foto si existe
        if ($request->hasFile('foto')) {
            // Eliminar foto anterior si existe (comprobando existencia en el disco)
            if ($persona->foto) {
                if (Storage::disk('public')->exists($persona->foto)) {
                    Storage::disk('public')->delete($persona->foto);
                }
            }
            $path = $request->file('foto')->store('fotos_personas', 'public');
            $validated['foto'] = $path;
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
        // Eliminar foto si existe
        if ($persona->foto) {
            if (Storage::disk('public')->exists($persona->foto)) {
                Storage::disk('public')->delete($persona->foto);
            }
        }

        // Eliminar persona
        $persona->delete();

        // Redirigir con mensaje de éxito
        return redirect()->route('personas.index')
            ->with('success', 'Persona eliminada exitosamente.');
    }
}