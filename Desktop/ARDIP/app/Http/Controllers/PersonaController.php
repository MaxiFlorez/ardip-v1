<?php

namespace App\Http\Controllers;

use App\Models\Persona;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage; // ← NUEVO

class PersonaController extends Controller
{
    // ... resto del código
    /**
     * Muestra el listado de todas las personas
     */
    public function index()
    {
        $personas = Persona::orderBy('apellidos')->get();
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
        // Validar los datos
        $validated = $request->validate([
            'dni' => 'required|string|size:8|unique:personas,dni',
            'nombres' => 'required|string|max:100',
            'apellidos' => 'required|string|max:100',
            'fecha_nacimiento' => 'required|date',
            'genero' => 'required|in:masculino,femenino,otro',
            'alias' => 'nullable|string|max:100',
            'nacionalidad' => 'nullable|string|max:50',
            'estado_civil' => 'nullable|in:soltero,casado,divorciado,viudo,concubinato',
            'foto' => 'nullable|image|max:2048', // 2MB máximo
            'observaciones' => 'nullable|string',
        ]);

        // Manejar la foto si existe
        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('fotos_personas', 'public');
            $validated['foto'] = $path;
        }

        // Crear la persona
        Persona::create($validated);

        // Redirigir con mensaje de éxito
        return redirect()->route('personas.index')
            ->with('success', 'Persona creada exitosamente.');
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
            'dni' => 'required|string|size:8|unique:personas,dni,' . $persona->id,
            'nombres' => 'required|string|max:100',
            'apellidos' => 'required|string|max:100',
            'fecha_nacimiento' => 'required|date',
            'genero' => 'required|in:masculino,femenino,otro',
            'alias' => 'nullable|string|max:100',
            'nacionalidad' => 'nullable|string|max:50',
            'estado_civil' => 'nullable|in:soltero,casado,divorciado,viudo,concubinato',
            'foto' => 'nullable|image|max:2048',
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

        // Actualizar la persona
        $persona->update($validated);

        // Redirigir con mensaje de éxito
        return redirect()->route('personas.show', $persona)
            ->with('success', 'Persona actualizada exitosamente.');
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