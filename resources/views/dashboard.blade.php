<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl md:text-2xl text-gray-800 dark:text-gray-200 leading-tight">
                📊 Panel de Supervisión Estadístico
            </h2>
        </div>
    </x-slot>

    {{-- Chart.js CDN --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.js"></script>

    <div class="py-6 md:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            
            {{-- 1. GRID DE MÉTRICAS (KPIs) --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                
                {{-- Card: Total Procedimientos --}}
                <x-stat-card 
                    title="Procedimientos" 
                    :value="$totalProcedimientos ?? 0"
                    subtitle="Total Registrados"
                    color="blue"
                >
                    <x-slot name="icon">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </x-slot>
                </x-stat-card>

                {{-- Card: Total Personas --}}
                <x-stat-card 
                    title="Personas" 
                    :value="$totalPersonas ?? 0"
                    subtitle="Base de Datos"
                    color="green"
                >
                    <x-slot name="icon">
                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </x-slot>
                </x-stat-card>

                {{-- Card: Total Documentos --}}
                <x-stat-card 
                    title="Documentos" 
                    :value="$totalDocumentos ?? 0"
                    subtitle="Archivos Adjuntos"
                    color="purple"
                >
                    <x-slot name="icon">
                        <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                        </svg>
                    </x-slot>
                </x-stat-card>

                {{-- Card: Brigadas Activas --}}
                <x-stat-card 
                    title="Brigadas Activas" 
                    :value="$brigadasActivas ?? 0"
                    subtitle="Con Movimientos"
                    color="orange"
                >
                    <x-slot name="icon">
                        <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </x-slot>
                </x-stat-card>
            </div>

            {{-- 2. SECCIÓN PRINCIPAL: GRÁFICO Y TABLA --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                
                {{-- GRÁFICO DE DONA (UFIs) --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg lg:col-span-1 border border-gray-200">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"/>
                            </svg>
                            Distribución por UFI
                        </h3>
                        <div class="relative h-64 w-full">
                            <canvas id="chartUfi"></canvas>
                        </div>
                    </div>
                </div>

                {{-- TABLA DE ÚLTIMOS MOVIMIENTOS --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg lg:col-span-2 border border-gray-200">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Últimas Cargas en el Sistema
                        </h3>
                        
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Carátula / UFI</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Acción</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @forelse($ultimosProcedimientos as $proc)
                                        <tr class="hover:bg-gray-50 transition-colors">
                                            <td class="px-4 py-3 whitespace-nowrap text-sm font-mono text-gray-500">#{{ $proc->id }}</td>
                                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-600">
                                                {{ $proc->created_at->format('d/m/Y') }}
                                            </td>
                                            <td class="px-4 py-3 text-sm text-gray-900">
                                                <div class="font-medium truncate max-w-xs" title="{{ $proc->caratula }}">{{ $proc->caratula }}</div>
                                                <div class="text-xs text-gray-500">{{ $proc->ufi->nombre ?? 'Sin UFI' }}</div>
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                    {{ $proc->estado === 'FINALIZADO' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                                    {{ $proc->estado ?? 'PENDIENTE' }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap text-right text-sm font-medium">
                                                <a href="{{ route('procedimientos.show', $proc) }}" class="text-blue-600 hover:text-blue-900 flex items-center justify-end gap-1">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                                    Ver
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="px-4 py-8 text-center text-gray-500">
                                                <p>No hay movimientos recientes para mostrar.</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- CHART.JS LOGIC --}}
    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const chartCanvas = document.getElementById('chartUfi');
            if (!chartCanvas) return;

            const ctx = chartCanvas.getContext('2d');
            const data = @json($procedimientosPorUfi ?? []); 

            if (data.length === 0) {
                // Mostrar mensaje si no hay datos
                chartCanvas.parentElement.innerHTML = `
                    <div class="flex flex-col items-center justify-center h-full text-gray-400">
                        <svg class="w-12 h-12 mb-2 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"/></svg>
                        <span class="text-sm">Sin datos estadísticos</span>
                    </div>`;
                return;
            }

            const labels = data.map(item => item.nombre_ufi);
            const values = data.map(item => item.total);
            const colors = [
                '#3B82F6', '#10B981', '#F59E0B', '#EF4444', '#8B5CF6', 
                '#EC4899', '#6366F1', '#14B8A6', '#F97316', '#64748B'
            ];

            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: labels,
                    datasets: [{
                        data: values,
                        backgroundColor: colors,
                        borderWidth: 0,
                        hoverOffset: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '70%',
                    plugins: {
                        legend: {
                            position: 'right',
                            labels: { 
                                boxWidth: 10,
                                font: { size: 11, family: "'Inter', sans-serif" },
                                color: '#374151' // text-gray-700
                            }
                        }
                    }
                }
            });
        });
    </script>
    @endpush
</x-app-layout>