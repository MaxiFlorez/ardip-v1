<?php

namespace App\Http\Controllers;

use App\Models\Procedimiento;
use App\Models\Brigada;
use App\Models\Ufi;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // 1. Filtros
        $periodo = $request->get('periodo', 'mes');
        $brigadaId = $request->get('brigada_id');

        $queryBase = Procedimiento::query()
            ->rangoFecha($periodo)
            ->deBrigada($brigadaId);

        // 3. Calcular Datos (Ingredientes para la Vista)
        $totalProcedimientos = (clone $queryBase)->count();
        
        $totalDetenidos = (clone $queryBase)
            ->withCount('personas')
            ->get()
            ->sum('personas_count');

        $totalPositivos = (clone $queryBase)
            ->positivos()
            ->count();

        // Datos para Gráficos
        $procPorBrigada = (clone $queryBase)
            ->selectRaw('count(*) as total, brigada_id')
            ->whereNotNull('brigada_id')
            ->groupBy('brigada_id')
            ->with('brigada')
            ->get()
            ->mapWithKeys(function ($item) {
                $nombre = $item->brigada ? $item->brigada->nombre : 'Desconocida';
                return [$nombre => $item->total];
            });

        $procPorUfi = (clone $queryBase)
            ->selectRaw('count(*) as total, ufi_id')
            ->whereNotNull('ufi_id')
            ->groupBy('ufi_id')
            ->orderByDesc('total')
            ->limit(10)
            ->with('ufi')
            ->get()
            ->mapWithKeys(function ($item) {
                $nombre = $item->ufi ? $item->ufi->nombre : 'Desconocida';
                return [$nombre => $item->total];
            });

        $ultimosProcedimientos = (clone $queryBase)
            ->with(['brigada', 'ufi'])
            ->latest('fecha_procedimiento')
            ->take(5)
            ->get();

        $brigadas = Brigada::orderBy('nombre')->get();

        // 4. Enviar todo a la vista
        return view('dashboard', [
            'totalProcedimientos' => $totalProcedimientos,
            'totalDetenidos' => $totalDetenidos,
            'totalPositivos' => $totalPositivos,
            'procPorBrigada' => $procPorBrigada,
            'procPorUfi' => $procPorUfi,
            'ultimosProcedimientos' => $ultimosProcedimientos,
            'brigadas' => $brigadas,
            'periodoActual' => $periodo,
            'brigadaActual' => $brigadaId,
        ]);
    }
}