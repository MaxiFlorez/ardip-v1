<?php

namespace App\Http\Controllers;

use App\Models\Procedimiento;
use App\Models\Brigada;
use App\Models\Persona;
use App\Models\Documento;
use App\Models\Ufi;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // 1. Calcular métricas generales
        $totalProcedimientos = Procedimiento::count();
        $totalPersonas = Persona::count();
        $totalDocumentos = Documento::count();
        $totalBrigadas = Brigada::count();

        // 2. Datos para Gráfico de Dona: Procedimientos por UFI

        // 2. Datos para Gráfico de Dona: Procedimientos por UFI
        $procPorUfi = Procedimiento::query()
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

        // 3. Últimos 5 Procedimientos cargados
        $ultimosProcedimientos = Procedimiento::query()
            ->with(['brigada', 'ufi'])
            ->latest('created_at')
            ->take(5)
            ->get();

        // 4. Enviar todo a la vista
        return view('dashboard', [
            'totalProcedimientos' => $totalProcedimientos,
            'totalPersonas' => $totalPersonas,
            'totalDocumentos' => $totalDocumentos,
            'totalBrigadas' => $totalBrigadas,
            'procPorUfi' => $procPorUfi,
            'ultimosProcedimientos' => $ultimosProcedimientos,
        ]);
    }
}