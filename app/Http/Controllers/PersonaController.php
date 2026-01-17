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
        // Limpieza: no verificaciones manuales, confiar en gates/middleware
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
    public function create(Request $request)
    {
        $procedimientoId = $request->query('procedimiento_id');
        return view('personas.create', compact('procedimientoId'));
    }

    public function store(StorePersonaRequest $request)
    {
        $validated = $request->validated();

        if ($request->hasFile('foto')) {
            $validated['foto'] = $this->uploadFile($request->file('foto'), 'fotos_personas');
        }

        $aliasInput = $request->input('alias', []);
        unset($validated['alias']);

        $persona = Persona::create($validated);

        if (!empty($aliasInput) && is_array($aliasInput)) {
            foreach ($aliasInput as $alias) {
                if (!empty(trim((string) $alias))) {
                    $persona->aliases()->create(['alias' => trim((string) $alias)]);
                }
            }
        }

        // Hub de Procedimientos: retorno inteligente y vinculación opcional
        if ($request->filled('procedimiento_id')) {
            $procedimientoId = $request->input('procedimiento_id');

            $persona->procedimientos()->attach($procedimientoId, [
                'situacion_procesal' => $request->input('situacion_procesal', 'notificado'),
                'observaciones' => $request->input('observaciones_vinculo')
            ]);

            return redirect()
                ->route('procedimientos.show', $procedimientoId)
                ->with('success', '✅ Persona creada y vinculada al procedimiento correctamente.');
        }

        return redirect()
            ->route('personas.show', $persona)
            ->with('success', '✅ Persona creada correctamente.');
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

    public function update(UpdatePersonaRequest $request, Persona $persona)
    {
        $validated = $request->validated();

        if ($request->hasFile('foto')) {
            $validated['foto'] = $this->updateFile(
                $request->file('foto'),
                $persona->foto,
                'fotos_personas'
            );
        }

        $aliasInput = $request->input('alias', []);
        unset($validated['alias']);

        $persona->update($validated);

        if (is_array($aliasInput)) {
            $persona->aliases()->delete();
            foreach ($aliasInput as $alias) {
                if (!empty(trim((string) $alias))) {
                    $persona->aliases()->create(['alias' => trim((string) $alias)]);
                }
            }
        }

        // Mantener retorno al Hub si viene con procedimiento_id
        if ($request->filled('procedimiento_id')) {
            return redirect()
                ->route('procedimientos.show', $request->input('procedimiento_id'))
                ->with('success', '✅ Persona actualizada y se mantuvo el contexto del procedimiento.');
        }

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