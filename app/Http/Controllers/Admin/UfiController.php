<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ufi;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UfiController extends Controller
{
    public function index()
    {
        $ufis = Ufi::withCount('procedimientos')
            ->orderBy('nombre')
            ->paginate(15);

        ActivityLog::log('ufi_index', 'Acceso al listado de UFIs');

        return view('admin.ufis.index', compact('ufis'));
    }

    public function create()
    {
        return view('admin.ufis.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255|unique:ufis,nombre',
        ], [
            'nombre.required' => 'El nombre de la UFI es obligatorio.',
            'nombre.unique' => 'Ya existe una UFI con ese nombre.',
            'nombre.max' => 'El nombre no puede exceder 255 caracteres.',
        ]);

        $ufi = Ufi::create($validated);

        ActivityLog::log(
            'ufi_created',
            "UFI creada: {$ufi->nombre}",
            [
                'model_type' => Ufi::class,
                'model_id' => $ufi->id,
                'properties' => ['nombre' => $ufi->nombre],
                'severity' => 'warning'
            ]
        );

        return redirect()
            ->route('admin.ufis.index')
            ->with('success', "UFI \"{$ufi->nombre}\" creada exitosamente.");
    }

    public function edit(Ufi $ufi)
    {
        return view('admin.ufis.edit', compact('ufi'));
    }

    public function update(Request $request, Ufi $ufi)
    {
        $validated = $request->validate([
            'nombre' => [
                'required',
                'string',
                'max:255',
                Rule::unique('ufis', 'nombre')->ignore($ufi->id),
            ],
        ], [
            'nombre.required' => 'El nombre de la UFI es obligatorio.',
            'nombre.unique' => 'Ya existe una UFI con ese nombre.',
            'nombre.max' => 'El nombre no puede exceder 255 caracteres.',
        ]);

        $original = $ufi->nombre;
        $ufi->update($validated);

        if ($original !== $ufi->nombre) {
            ActivityLog::log(
                'ufi_updated',
                "UFI actualizada: {$original} → {$ufi->nombre}",
                [
                    'model_type' => Ufi::class,
                    'model_id' => $ufi->id,
                    'properties' => [
                        'original_nombre' => $original,
                        'new_nombre' => $ufi->nombre,
                    ],
                    'severity' => 'warning'
                ]
            );
        }

        return redirect()
            ->route('admin.ufis.index')
            ->with('success', "UFI \"{$ufi->nombre}\" actualizada exitosamente.");
    }

    public function destroy(Ufi $ufi)
    {
        // Verificar si tiene procedimientos asociados
        if ($ufi->procedimientos()->count() > 0) {
            return redirect()
                ->route('admin.ufis.index')
                ->with('error', "No se puede eliminar la UFI \"{$ufi->nombre}\" porque tiene {$ufi->procedimientos()->count()} procedimiento(s) asociado(s).");
        }

        $nombreUfi = $ufi->nombre;

        try {
            $ufi->delete();

            ActivityLog::log(
                'ufi_deleted',
                "UFI eliminada: {$nombreUfi}",
                [
                    'model_type' => Ufi::class,
                    'model_id' => $ufi->id,
                    'properties' => ['nombre' => $nombreUfi],
                    'severity' => 'critical'
                ]
            );

            return redirect()
                ->route('admin.ufis.index')
                ->with('success', "UFI \"{$nombreUfi}\" eliminada exitosamente.");
        } catch (\Exception $e) {
            return redirect()
                ->route('admin.ufis.index')
                ->with('error', "Error al eliminar la UFI: {$e->getMessage()}");
        }
    }
}
