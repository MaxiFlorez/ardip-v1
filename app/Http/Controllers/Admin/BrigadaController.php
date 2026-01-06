<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brigada;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class BrigadaController extends Controller
{
    public function index()
    {
        $brigadas = Brigada::withCount('users')
            ->orderBy('nombre')
            ->paginate(15);

        ActivityLog::log('brigada_index', 'Acceso al listado de brigadas');

        return view('admin.brigadas.index', compact('brigadas'));
    }

    public function create()
    {
        return view('admin.brigadas.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255|unique:brigadas,nombre',
        ], [
            'nombre.required' => 'El nombre de la brigada es obligatorio.',
            'nombre.unique' => 'Ya existe una brigada con ese nombre.',
            'nombre.max' => 'El nombre no puede exceder 255 caracteres.',
        ]);

        $brigada = Brigada::create($validated);

        ActivityLog::log(
            'brigada_created',
            "Brigada creada: {$brigada->nombre}",
            [
                'model_type' => Brigada::class,
                'model_id' => $brigada->id,
                'properties' => ['nombre' => $brigada->nombre],
                'severity' => 'warning'
            ]
        );

        return redirect()
            ->route('admin.brigadas.index')
            ->with('success', "Brigada \"{$brigada->nombre}\" creada exitosamente.");
    }

    public function edit(Brigada $brigada)
    {
        return view('admin.brigadas.edit', compact('brigada'));
    }

    public function update(Request $request, Brigada $brigada)
    {
        $validated = $request->validate([
            'nombre' => [
                'required',
                'string',
                'max:255',
                Rule::unique('brigadas', 'nombre')->ignore($brigada->id),
            ],
        ], [
            'nombre.required' => 'El nombre de la brigada es obligatorio.',
            'nombre.unique' => 'Ya existe una brigada con ese nombre.',
            'nombre.max' => 'El nombre no puede exceder 255 caracteres.',
        ]);

        $original = $brigada->getOriginal();
        $brigada->update($validated);

        if ($original['nombre'] !== $brigada->nombre) {
            ActivityLog::log(
                'brigada_updated',
                "Brigada actualizada: {$original['nombre']} → {$brigada->nombre}",
                [
                    'model_type' => Brigada::class,
                    'model_id' => $brigada->id,
                    'properties' => [
                        'original' => $original,
                        'changes' => $brigada->getChanges(),
                    ],
                    'severity' => 'warning'
                ]
            );
        }

        return redirect()
            ->route('admin.brigadas.index')
            ->with('success', "Brigada \"{$brigada->nombre}\" actualizada exitosamente.");
    }

    public function destroy(Brigada $brigada)
    {
        // Verificar si tiene usuarios asociados
        if ($brigada->users()->count() > 0) {
            return redirect()
                ->route('admin.brigadas.index')
                ->with('error', "No se puede eliminar la brigada \"{$brigada->nombre}\" porque tiene {$brigada->users()->count()} usuario(s) asociado(s).");
        }

        $nombreBrigada = $brigada->nombre;

        try {
            $brigada->delete();

            ActivityLog::log(
                'brigada_deleted',
                "Brigada eliminada: {$nombreBrigada}",
                [
                    'model_type' => Brigada::class,
                    'model_id' => $brigada->id,
                    'properties' => ['nombre' => $nombreBrigada],
                    'severity' => 'critical'
                ]
            );

            return redirect()
                ->route('admin.brigadas.index')
                ->with('success', "Brigada \"{$nombreBrigada}\" eliminada exitosamente.");
        } catch (\Exception $e) {
            return redirect()
                ->route('admin.brigadas.index')
                ->with('error', "Error al eliminar la brigada: {$e->getMessage()}");
        }
    }
}
