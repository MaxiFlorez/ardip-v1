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

        // 6. Datos para gráficos
        // Procedimientos por Brigada
        $procPorBrigada = $query->clone()
            ->selectRaw('brigadas.nombre, COUNT(*) as total')
            ->join('brigadas', 'procedimientos.brigada_id', '=', 'brigadas.id')
            ->groupBy('brigadas.id', 'brigadas.nombre')
            ->pluck('total', 'nombre');

        // Procedimientos por UFI
        $procPorUfi = $query->clone()
            ->selectRaw('ufis.nombre, COUNT(*) as total')
            ->join('ufis', 'procedimientos.ufi_id', '=', 'ufis.id')
            ->groupBy('ufis.id', 'ufis.nombre')
            ->orderByDesc('total')
            ->limit(10)
            ->pluck('total', 'nombre');

        // Últimos 5 procedimientos
        $ultimosProcedimientos = $query->clone()
            ->with(['brigada', 'ufi'])
            ->orderByDesc('fecha_procedimiento')
            ->limit(5)
            ->get();

        // 7. Retornar la vista pasando los datos para los filtros, KPIs y gráficos.
        return view('dashboard', [
            'totalProcedimientos' => $totalProcedimientos,
            'totalDetenidos' => $totalDetenidos,
            'totalPositivos' => $totalPositivos,
            'brigadas' => $brigadas,
            'periodoActual' => $periodoActual,
            'brigadaActual' => $brigadaActual,
            'procPorBrigada' => $procPorBrigada,
            'procPorUfi' => $procPorUfi,
            'ultimosProcedimientos' => $ultimosProcedimientos,
        ]);
    }
}