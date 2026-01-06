<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.users.show', $user) }}" class="text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100">
                ← Volver al Perfil
            </a>
            <h2 class="font-semibold text-xl md:text-2xl text-gray-800 dark:text-gray-200 leading-tight">
                📊 Historial Completo: {{ $user->name }}
            </h2>
        </div>
    </x-slot>

    <div class="py-6 md:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- Información del Usuario --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-4 md:p-6">
                    <div class="flex items-center gap-4">
                        <div class="h-12 w-12 rounded-full bg-gradient-to-br from-indigo-400 to-purple-500 flex items-center justify-center text-white font-bold text-lg">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-900 dark:text-gray-100">{{ $user->name }}</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400">{{ $user->email }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Tabla de Logs --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 md:p-8">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100">
                            Registro de Actividad ({{ $activityLogs->total() }} registros)
                        </h3>
                    </div>

                    @if ($activityLogs->count() > 0)
                        {{-- Vista Desktop --}}
                        <div class="hidden md:block overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                                <thead class="bg-gray-50 dark:bg-gray-900">
                                    <tr>
                                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">
                                            Fecha/Hora
                                        </th>
                                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">
                                            Acción
                                        </th>
                                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">
                                            Descripción
                                        </th>
                                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">
                                            IP
                                        </th>
                                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">
                                            Dispositivo
                                        </th>
                                        <th scope="col" class="px-4 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">
                                            Severidad
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach ($activityLogs as $log)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                            <td class="px-4 py-3 whitespace-nowrap text-gray-900 dark:text-gray-100">
                                                <div>{{ $log->created_at->format('d/m/Y') }}</div>
                                                <div class="text-xs text-gray-500">{{ $log->created_at->format('H:i:s') }}</div>
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap">
                                                <code class="text-xs bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded">{{ $log->action }}</code>
                                            </td>
                                            <td class="px-4 py-3 text-gray-600 dark:text-gray-400 max-w-xs">
                                                {{ $log->description }}
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap text-gray-500 dark:text-gray-400 text-xs">
                                                {{ $log->ip_address ?? '-' }}
                                            </td>
                                            <td class="px-4 py-3 text-gray-500 dark:text-gray-400 text-xs max-w-xs truncate" title="{{ $log->user_agent }}">
                                                {{ $log->user_agent ? Str::limit($log->user_agent, 30) : '-' }}
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap text-center">
                                                @php
                                                    $severityColors = [
                                                        'critical' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200',
                                                        'warning' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200',
                                                        'info' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200',
                                                    ];
                                                    $color = $severityColors[$log->severity] ?? 'bg-gray-100 text-gray-800';
                                                @endphp
                                                <span class="px-2 py-1 inline-flex text-xs font-semibold rounded {{ $color }}">
                                                    {{ ucfirst($log->severity) }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        {{-- Vista Mobile --}}
                        <div class="md:hidden space-y-4">
                            @foreach ($activityLogs as $log)
                                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 border border-gray-200 dark:border-gray-600">
                                    <div class="flex justify-between items-start mb-2">
                                        <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                            {{ $log->created_at->format('d/m/Y H:i') }}
                                        </div>
                                        @php
                                            $severityColors = [
                                                'critical' => 'bg-red-100 text-red-800',
                                                'warning' => 'bg-yellow-100 text-yellow-800',
                                                'info' => 'bg-blue-100 text-blue-800',
                                            ];
                                            $color = $severityColors[$log->severity] ?? 'bg-gray-100 text-gray-800';
                                        @endphp
                                        <span class="px-2 py-1 inline-flex text-xs font-semibold rounded {{ $color }}">
                                            {{ ucfirst($log->severity) }}
                                        </span>
                                    </div>
                                    <div class="mb-2">
                                        <code class="text-xs bg-white dark:bg-gray-600 px-2 py-1 rounded">{{ $log->action }}</code>
                                    </div>
                                    <p class="text-sm text-gray-600 dark:text-gray-300 mb-2">{{ $log->description }}</p>
                                    <div class="text-xs text-gray-500 dark:text-gray-400 space-y-1">
                                        <div>IP: {{ $log->ip_address ?? '-' }}</div>
                                        @if ($log->user_agent)
                                            <div class="truncate">Dispositivo: {{ Str::limit($log->user_agent, 50) }}</div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        {{-- Paginación --}}
                        @if ($activityLogs->hasPages())
                            <div class="mt-6">
                                {{ $activityLogs->links() }}
                            </div>
                        @endif
                    @else
                        <p class="text-center text-gray-500 dark:text-gray-400 py-12">
                            📭 No hay actividad registrada para este usuario.
                        </p>
                    @endif
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
