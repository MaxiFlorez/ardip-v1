<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Models\Procedimiento;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Verificar permiso manualmente (después de que Laravel cargue las relaciones)
        if (! Gate::allows('admin-general')) {
            abort(403, 'No tienes permiso para acceder al Dashboard.');
        }

        $semanaActual = now()->startOfWeek();
        $semanaFin = now()->endOfWeek();

        $brigada_id = $request->get('brigada_id');

        $query = Procedimiento::whereBetween('fecha_procedimiento', [$semanaActual, $semanaFin]);

        if ($brigada_id) {
            $query->where('brigada_id', $brigada_id);
        }

        $procedimientos = $query->get();

        $stats = [
            'total_procedimientos' => $procedimientos->count(),
            'total_detenidos' => $procedimientos->sum(function ($p) {
                return $p->personas()->wherePivot('situacion_procesal', 'detenido')->count();
            }),
            'secuestros_positivos' => $procedimientos->where('resultado_secuestro', 'positivo')->count(),
            'detenciones_positivas' => $procedimientos->where('resultado_detencion', 'positivo')->count(),
        ];

        $porBrigada = Procedimiento::selectRaw('brigada_id, COUNT(*) as total')
            ->whereBetween('fecha_procedimiento', [$semanaActual, $semanaFin])
            ->groupBy('brigada_id')
            ->with('brigada')
            ->get();

        return view('dashboard', compact('stats', 'porBrigada', 'procedimientos'));
    }
}