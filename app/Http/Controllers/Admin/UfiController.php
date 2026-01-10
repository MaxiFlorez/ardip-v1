<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ufi;
use App\Models\ActivityLog;
// use Illuminate\Http\Request; // No se usa directamente
// use Illuminate\Validation\Rule; // Regla trasladada al Form Request
use App\Http\Requests\StoreUfiRequest;
use App\Http\Requests\UpdateUfiRequest;

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

    public function store(StoreUfiRequest $request)
    {
        $validated = $request->validated();

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

    public function update(UpdateUfiRequest $request, Ufi $ufi)
    {
        $validated = $request->validated();

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
