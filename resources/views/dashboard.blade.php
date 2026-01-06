<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl md:text-2xl text-primary-800 dark:text-primary-200 leading-tight">
                📊 Tablero de Comando
            </h2>
            <span class="px-3 py-1 bg-primary-100 dark:bg-primary-900 text-primary-800 dark:text-primary-200 rounded-full text-xs font-semibold">
                🔒 Administrativo
            </span>
        </div>
    </x-slot>

    {{-- Chart.js CDN --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.js"></script>

    <div class="py-6 md:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            
            {{-- Barra de Filtros --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg border border-primary-200 dark:border-primary-700">
                <div class="p-4 md:p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-sm font-semibold text-primary-700 dark:text-primary-300 mb-4">🔍 Filtros</h3>
                    <form method="GET" action="{{ route('dashboard') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        
                        {{-- Select de Periodo --}}
                        <div>
                            <label for="periodo" class="block font-medium text-sm text-gray-700 dark:text-gray-300 mb-2">Período</label>
                            <select name="periodo" id="periodo" class="w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-primary-500 dark:focus:border-primary-600 focus:ring-primary-500 dark:focus:ring-primary-600 text-sm">
                                <option value="semana" @selected($periodoActual == 'semana')>Semana</option>
                                <option value="mes" @selected($periodoActual == 'mes')>Mes</option>
                                <option value="anio" @selected($periodoActual == 'anio')>Año</option>
                            </select>
                        </div>

                        {{-- Select de Brigada --}}
                        <div>
                            <label for="brigada_id" class="block font-medium text-sm text-gray-700 dark:text-gray-300 mb-2">Brigada</label>
                            <select name="brigada_id" id="brigada_id" class="w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-primary-500 dark:focus:border-primary-600 focus:ring-primary-500 dark:focus:ring-primary-600 text-sm">
                                <option value="">Todas</option>
                                @if ($brigadas && $brigadas->isNotEmpty())
                                    @foreach ($brigadas as $brigada)
                                        <option value="{{ $brigada->id }}" @selected($brigadaActual == $brigada->id)>
                                            {{ $brigada->nombre }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        
                        {{-- Botones --}}
                        <div class="flex items-end gap-2 col-span-1 md:col-span-2">
                            <button type="submit" class="flex-1 px-4 py-2 bg-primary-600 text-white rounded-md hover:bg-primary-700 transition duration-150 text-sm font-medium">
                                🔍 Filtrar
                            </button>
                            <a href="{{ route('dashboard') }}" class="flex-1 px-4 py-2 text-primary-600 dark:text-primary-400 hover:bg-primary-50 dark:hover:bg-primary-900 rounded-md transition duration-150 text-sm font-medium text-center">
                                ↺ Limpiar
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            {{-- KPIs Cards --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                
                {{-- Card: Total Procedimientos --}}
                <div class="bg-gradient-to-br from-primary-50 to-primary-100 dark:from-primary-900 dark:to-primary-800 overflow-hidden shadow-md rounded-lg border-l-4 border-primary-600">
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-sm font-medium text-primary-700 dark:text-primary-300">Total Procedimientos</h3>
                                <p class="mt-3 text-4xl font-bold text-primary-900 dark:text-primary-100">{{ $totalProcedimientos ?? 0 }}</p>
                                <p class="mt-2 text-xs text-primary-600 dark:text-primary-400">Período: <strong>{{ ucfirst($periodoActual ?? 'mes') }}</strong></p>
                            </div>
                            <div class="text-4xl">📋</div>
                        </div>
                    </div>
                </div>

                {{-- Card: Total Detenidos --}}
                <div class="bg-gradient-to-br from-secondary-50 to-secondary-100 dark:from-secondary-900 dark:to-secondary-800 overflow-hidden shadow-md rounded-lg border-l-4 border-secondary-600">
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-sm font-medium text-secondary-700 dark:text-secondary-300">Total Detenidos</h3>
                                <p class="mt-3 text-4xl font-bold text-secondary-900 dark:text-secondary-100">{{ $totalDetenidos ?? 0 }}</p>
                                <p class="mt-2 text-xs text-secondary-600 dark:text-secondary-400">Período: <strong>{{ ucfirst($periodoActual ?? 'mes') }}</strong></p>
                            </div>
                            <div class="text-4xl">👥</div>
                        </div>
                    </div>
                </div>

                {{-- Card: Allanamientos Positivos --}}
                <div class="bg-gradient-to-br from-success-50 to-success-100 dark:from-success-900 dark:to-success-800 overflow-hidden shadow-md rounded-lg border-l-4 border-success-600">
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-sm font-medium text-success-700 dark:text-success-300">✓ Positivos</h3>
                                <p class="mt-3 text-4xl font-bold text-success-900 dark:text-success-100">{{ $totalPositivos ?? 0 }}</p>
                                <p class="mt-2 text-xs text-success-600 dark:text-success-400">Período: <strong>{{ ucfirst($periodoActual ?? 'mes') }}</strong></p>
                            </div>
                            <div class="text-4xl">✅</div>
                        </div>
                    </div>
                </div>

            </div>

            {{-- Gráficos --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                
                {{-- Gráfico Torta: Por Brigada --}}
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-md rounded-lg border border-primary-200 dark:border-primary-700 p-6">
                    <h3 class="text-lg font-semibold text-primary-900 dark:text-primary-100 mb-4">📋 Procedimientos por Brigada</h3>
                    <div class="relative h-80">
                        <canvas id="chartBrigada"></canvas>
                    </div>
                </div>

                {{-- Gráfico Barras: Por UFI --}}
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-md rounded-lg border border-primary-200 dark:border-primary-700 p-6">
                    <h3 class="text-lg font-semibold text-primary-900 dark:text-primary-100 mb-4">🏛️ Procedimientos por UFI (Top 10)</h3>
                    <div class="relative h-80">
                        <canvas id="chartUfi"></canvas>
                    </div>
                </div>

            </div>

            {{-- Tabla: Últimos Procedimientos --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-md rounded-lg border border-primary-200 dark:border-primary-700">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-primary-900 dark:text-primary-100 mb-4">🕐 Últimos 5 Procedimientos</h3>
                    
                    {{-- Tabla Desktop --}}
                    <div class="hidden md:block overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="bg-primary-50 dark:bg-primary-900 border-b border-primary-200 dark:border-primary-700">
                                <tr>
                                    <th class="px-6 py-3 text-left font-semibold text-primary-900 dark:text-primary-100">Legajo</th>
                                    <th class="px-6 py-3 text-left font-semibold text-primary-900 dark:text-primary-100">Carátula</th>
                                    <th class="px-6 py-3 text-left font-semibold text-primary-900 dark:text-primary-100">Brigada</th>
                                    <th class="px-6 py-3 text-left font-semibold text-primary-900 dark:text-primary-100">UFI</th>
                                    <th class="px-6 py-3 text-left font-semibold text-primary-900 dark:text-primary-100">Fecha</th>
                                    <th class="px-6 py-3 text-left font-semibold text-primary-900 dark:text-primary-100">Estado</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-primary-200 dark:divide-primary-700">
                                @forelse ($ultimosProcedimientos ?? [] as $proc)
                                    <tr class="hover:bg-primary-50 dark:hover:bg-primary-900 transition">
                                        <td class="px-6 py-4 text-gray-900 dark:text-gray-100">
                                            <span class="font-mono text-xs bg-primary-100 dark:bg-primary-800 px-2 py-1 rounded">{{ $proc->legajo_fiscal }}</span>
                                        </td>
                                        <td class="px-6 py-4 text-gray-900 dark:text-gray-100">{{ $proc->caratula }}</td>
                                        <td class="px-6 py-4 text-gray-900 dark:text-gray-100">{{ $proc->brigada?->nombre ?? '-' }}</td>
                                        <td class="px-6 py-4 text-gray-900 dark:text-gray-100">{{ $proc->ufi?->nombre ?? '-' }}</td>
                                        <td class="px-6 py-4 text-gray-600 dark:text-gray-400 text-sm">{{ $proc->fecha_procedimiento?->format('d/m/Y') ?? '-' }}</td>
                                        <td class="px-6 py-4">
                                            @if ($proc->es_positivo)
                                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-success-100 text-success-800 dark:bg-success-900 dark:text-success-200">
                                                    ✓ Positivo
                                                </span>
                                            @else
                                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-danger-100 text-danger-800 dark:bg-danger-900 dark:text-danger-200">
                                                    ✗ Negativo
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                            No hay procedimientos para mostrar
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Cards Mobile --}}
                    <div class="md:hidden space-y-4">
                        @forelse ($ultimosProcedimientos ?? [] as $proc)
                            <div class="bg-primary-50 dark:bg-primary-900 rounded-lg p-4 border border-primary-200 dark:border-primary-700">
                                <div class="flex justify-between items-start mb-3">
                                    <div>
                                        <span class="font-mono text-xs bg-primary-200 dark:bg-primary-800 px-2 py-1 rounded">{{ $proc->legajo_fiscal }}</span>
                                        <p class="font-medium text-gray-900 dark:text-gray-100 mt-2">{{ $proc->caratula }}</p>
                                    </div>
                                    @if ($proc->es_positivo)
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-success-100 text-success-800 dark:bg-success-900 dark:text-success-200">
                                            ✓ Positivo
                                        </span>
                                    @else
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-danger-100 text-danger-800 dark:bg-danger-900 dark:text-danger-200">
                                            ✗ Negativo
                                        </span>
                                    @endif
                                </div>
                                <div class="grid grid-cols-2 gap-2 text-sm text-gray-600 dark:text-gray-400">
                                    <div><strong>Brigada:</strong> {{ $proc->brigada?->nombre ?? '-' }}</div>
                                    <div><strong>UFI:</strong> {{ $proc->ufi?->nombre ?? '-' }}</div>
                                    <div class="col-span-2"><strong>Fecha:</strong> {{ $proc->fecha_procedimiento?->format('d/m/Y') ?? '-' }}</div>
                                </div>
                            </div>
                        @empty
                            <p class="text-center text-gray-500 dark:text-gray-400 py-8">No hay procedimientos para mostrar</p>
                        @endforelse
                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- Variables globales para gráficos --}}
    <script>
        window.dataBrigada = @json($procPorBrigada ?? []);
        window.dataUfi = @json($procPorUfi ?? []);
    </script>

    {{-- Script para inicializar gráficos con Chart.js --}}
    <script>
        // Colores semánticos
        const colors = {
            primary: '#475569',
            secondary: '#0284c7',
            success: '#059669',
            danger: '#dc2626',
            warning: '#d97706',
        };

        // Gráfico Torta: Por Brigada
        if (Object.keys(window.dataBrigada).length > 0) {
            const ctxBrigada = document.getElementById('chartBrigada').getContext('2d');
            new Chart(ctxBrigada, {
                type: 'doughnut',
                data: {
                    labels: Object.keys(window.dataBrigada),
                    datasets: [{
                        data: Object.values(window.dataBrigada),
                        backgroundColor: [
                            colors.primary,
                            colors.secondary,
                            colors.success,
                            colors.warning,
                            colors.danger,
                            '#6366f1',
                            '#ec4899',
                            '#f59e0b',
                            '#14b8a6',
                            '#8b5cf6',
                        ],
                        borderColor: '#ffffff',
                        borderWidth: 2,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: { padding: 15, usePointStyle: true }
                        }
                    }
                }
            });
        } else {
            document.getElementById('chartBrigada').parentElement.innerHTML = '<p class="text-center text-gray-500 dark:text-gray-400 py-12">Sin datos disponibles</p>';
        }

        // Gráfico Barras: Por UFI
        if (Object.keys(window.dataUfi).length > 0) {
            const ctxUfi = document.getElementById('chartUfi').getContext('2d');
            const ufiValues = Object.values(window.dataUfi);
            const maxValue = Math.max(...ufiValues) + 2;
            
            new Chart(ctxUfi, {
                type: 'bar',
                data: {
                    labels: Object.keys(window.dataUfi),
                    datasets: [{
                        label: 'Procedimientos',
                        data: ufiValues,
                        backgroundColor: colors.secondary,
                        borderColor: colors.primary,
                        borderWidth: 1,
                        borderRadius: 5,
                    }]
                },
                options: {
                    indexAxis: 'y',
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        x: { 
                            beginAtZero: true, 
                            max: maxValue
                        }
                    }
                }
            });
        } else {
            document.getElementById('chartUfi').parentElement.innerHTML = '<p class="text-center text-gray-500 dark:text-gray-400 py-12">Sin datos disponibles</p>';
        }
    </script>

</x-app-layout>