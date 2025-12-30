<?php

namespace App\Http\Controllers;

use App\Models\Brigada;
use App\Models\Procedimiento;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // 1. Obtener $brigadas para el filtro de la vista.
        $brigadas = Brigada::all();

        // 2. Crear una consulta base.
        $query = Procedimiento::query();

        // 3. Aplicar los filtros usando los scopes del modelo.
        $periodoActual = $request->input('periodo', 'mes');
        $brigadaActual = $request->input('brigada_id');

        $query->rangoFecha($periodoActual)
              ->deBrigada($brigadaActual);

        // 4. Calcular las 3 Estadísticas (KPIs) sobre la consulta filtrada.
        
        // Se clona la query para cada cálculo para evitar que los cálculos se afecten entre sí.
        $totalProcedimientos = $query->clone()->count();
        $totalDetenidos = $query->clone()->withCount('personas')->get()->sum('personas_count');
        $totalPositivos = $query->clone()->positivos()->count();

        // 5. Retornar la vista pasando los datos para los filtros y los KPIs.
        return view('dashboard', [
            'totalProcedimientos' => $totalProcedimientos,
            'totalDetenidos' => $totalDetenidos,
            'totalPositivos' => $totalPositivos,
            'brigadas' => $brigadas,
            'periodoActual' => $periodoActual,
            'brigadaActual' => $brigadaActual,
        ]);
    }
}