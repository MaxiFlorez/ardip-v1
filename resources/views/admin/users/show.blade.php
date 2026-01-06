<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.users.index') }}" class="text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100">
                ← Volver
            </a>
            <h2 class="font-semibold text-xl md:text-2xl text-gray-800 dark:text-gray-200 leading-tight">
                👤 Perfil de {{ $user->name }}
            </h2>
        </div>
    </x-slot>

    <div class="py-6 md:py-12">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            
            {{-- Tarjeta de Información del Usuario --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 md:p-8">
                    <div class="flex flex-col md:flex-row gap-6">
                        
                        {{-- Avatar --}}
                        <div class="flex-shrink-0">
                            <div class="h-24 w-24 rounded-full bg-gradient-to-br from-primary-400 to-secondary-500 flex items-center justify-center text-white font-bold text-4xl">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                        </div>

                        {{-- Información --}}
                        <div class="flex-1">
                            <h3 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-2">{{ $user->name }}</h3>
                            <p class="text-gray-600 dark:text-gray-400 mb-4">{{ $user->email }}</p>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                                <div>
                                    <span class="font-medium text-gray-700 dark:text-gray-300">Rol:</span>
                                    @php
                                        $roleColors = [
                                            'super_admin' => 'bg-danger-100 text-danger-800 dark:bg-danger-900 dark:text-danger-200',
                                            'admin' => 'bg-secondary-100 text-secondary-800 dark:bg-secondary-900 dark:text-secondary-200',
                                            'cargador' => 'bg-secondary-100 text-secondary-800 dark:bg-secondary-900 dark:text-secondary-200',
                                            'consultor' => 'bg-success-100 text-success-800 dark:bg-success-900 dark:text-success-200',
                                        ];
                                        $roleName = $user->roles->first()?->name ?? 'sin-rol';
                                        $roleColor = $roleColors[$roleName] ?? 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300';
                                    @endphp
                                    <span class="ml-2 px-2 py-1 inline-flex text-xs font-semibold rounded-full {{ $roleColor }}">
                                        {{ $user->roles->first()?->label ?? 'Sin Rol' }}
                                    </span>
                                </div>
                                
                                <div>
                                    <span class="font-medium text-gray-700 dark:text-gray-300">Estado:</span>
                                    @if ($user->active)
                                        <span class="ml-2 px-2 py-1 inline-flex text-xs font-semibold rounded-full bg-success-100 text-success-800 dark:bg-success-900 dark:text-success-200">
                                            ✓ Activo
                                        </span>
                                    @else
                                        <span class="ml-2 px-2 py-1 inline-flex text-xs font-semibold rounded-full bg-danger-100 text-danger-800 dark:bg-danger-900 dark:text-danger-200">
                                            ✗ Inactivo
                                        </span>
                                    @endif
                                </div>
                                
                                <div>
                                    <span class="font-medium text-gray-700 dark:text-gray-300">Brigada:</span>
                                    <span class="ml-2 text-gray-900 dark:text-gray-100">{{ $user->brigada?->nombre ?? 'Sin brigada' }}</span>
                                </div>
                                
                                <div>
                                    <span class="font-medium text-gray-700 dark:text-gray-300">Última conexión:</span>
                                    <span class="ml-2 text-gray-900 dark:text-gray-100">{{ $user->last_login_at ? $user->last_login_at->diffForHumans() : 'Nunca' }}</span>
                                </div>

                                <div>
                                    <span class="font-medium text-gray-700 dark:text-gray-300">Miembro desde:</span>
                                    <span class="ml-2 text-gray-900 dark:text-gray-100">{{ $user->created_at->format('d/m/Y') }}</span>
                                </div>
                            </div>

                            {{-- Acciones Rápidas --}}
                            <div class="mt-6 flex gap-3 flex-wrap">
                                <a href="{{ route('admin.users.edit', $user) }}" class="px-4 py-2 bg-warning-600 text-white text-sm rounded hover:bg-warning-700 transition">
                                    ✏️ Editar
                                </a>
                                <a href="{{ route('admin.users.history', $user) }}" class="px-4 py-2 bg-secondary-600 text-white text-sm rounded hover:bg-secondary-700 transition">
                                    📊 Ver Historial Completo
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Historial de Actividad (Últimas 50) --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 md:p-8">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-4">
                        📊 Actividad Reciente (Últimas 50 acciones)
                    </h3>

                    @if ($activityLogs->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                                <thead class="bg-gray-50 dark:bg-gray-900">
                                    <tr>
                                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">
                                            Fecha
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
                                            Severidad
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach ($activityLogs as $log)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                            <td class="px-4 py-3 whitespace-nowrap text-gray-900 dark:text-gray-100">
                                                {{ $log->created_at->format('d/m/Y H:i') }}
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap">
                                                <code class="text-xs bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded">{{ $log->action }}</code>
                                            </td>
                                            <td class="px-4 py-3 text-gray-600 dark:text-gray-400">
                                                {{ $log->description }}
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap text-gray-500 dark:text-gray-400">
                                                {{ $log->ip_address ?? '-' }}
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap">
                                                @php
                                                    $severityColors = [
                                                        'critical' => 'bg-danger-100 text-danger-800 dark:bg-danger-900 dark:text-danger-200',
                                                        'warning' => 'bg-warning-100 text-warning-800 dark:bg-warning-900 dark:text-warning-200',
                                                        'info' => 'bg-secondary-100 text-secondary-800 dark:bg-secondary-900 dark:text-secondary-200',
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

                        {{-- Paginación --}}
                        @if ($activityLogs->hasPages())
                            <div class="mt-4">
                                {{ $activityLogs->links() }}
                            </div>
                        @endif
                    @else
                        <p class="text-center text-gray-500 dark:text-gray-400 py-8">
                            No hay actividad registrada para este usuario.
                        </p>
                    @endif
                </div>
            </div>

        </div>
    </div>
</x-app-layout>







