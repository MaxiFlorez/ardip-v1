<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <h2 class="font-semibold text-xl md:text-2xl text-gray-800 dark:text-gray-200 leading-tight">
                👥 Gestión de Usuarios
            </h2>
            <a href="{{ route('admin.users.create') }}" class="inline-flex items-center px-4 py-2 bg-primary-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-primary-700 active:bg-primary-900 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 transition ease-in-out duration-150">
                ➕ Nuevo Usuario
            </a>
        </div>
    </x-slot>

    <div class="py-6 md:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- Alertas --}}
            @if (session('success'))
                <div class="mb-6 bg-success-50 border-l-4 border-success-500 p-4 rounded">
                    <p class="text-success-700">✅ {{ session('success') }}</p>
                </div>
            @endif

            @if (session('error'))
                <div class="mb-6 bg-danger-50 border-l-4 border-danger-500 p-4 rounded">
                    <p class="text-danger-700">❌ {{ session('error') }}</p>
                </div>
            @endif

            {{-- Barra de Filtros --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-4 md:p-6 text-gray-900 dark:text-gray-100">
                    <form method="GET" action="{{ route('admin.users.index') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        
                        {{-- Buscador --}}
                        <div>
                            <label for="search" class="block font-medium text-sm text-gray-700 dark:text-gray-300 mb-1">Buscar</label>
                            <input 
                                type="text" 
                                name="search" 
                                id="search" 
                                value="{{ request('search') }}"
                                placeholder="Nombre o email..."
                                class="w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-primary-500 dark:focus:border-primary-600 focus:ring-primary-500 dark:focus:ring-primary-600 text-sm"
                            >
                        </div>

                        {{-- Filtro por Rol --}}
                        <div>
                            <label for="role_id" class="block font-medium text-sm text-gray-700 dark:text-gray-300 mb-1">Rol</label>
                            <select name="role_id" id="role_id" class="w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-primary-500 dark:focus:border-primary-600 focus:ring-primary-500 dark:focus:ring-primary-600 text-sm">
                                <option value="">Todos los roles</option>
                                @foreach ($roles as $role)
                                    <option value="{{ $role->id }}" @selected(request('role_id') == $role->id)>
                                        {{ $role->label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Filtro por Estado --}}
                        <div>
                            <label for="active" class="block font-medium text-sm text-gray-700 dark:text-gray-300 mb-1">Estado</label>
                            <select name="active" id="active" class="w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-primary-500 dark:focus:border-primary-600 focus:ring-primary-500 dark:focus:ring-primary-600 text-sm">
                                <option value="">Todos</option>
                                <option value="1" @selected(request('active') === '1')>Activos</option>
                                <option value="0" @selected(request('active') === '0')>Inactivos</option>
                            </select>
                        </div>

                        {{-- Botones --}}
                        <div class="md:col-span-3 flex items-center gap-2 flex-wrap">
                            <button type="submit" class="px-4 py-2 bg-primary-600 text-white rounded-md hover:bg-primary-700 transition duration-150 text-sm font-medium">
                                🔍 Filtrar
                            </button>
                            <a href="{{ route('admin.users.index') }}" class="px-4 py-2 text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 transition duration-150 text-sm">
                                Limpiar
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Tabla Desktop / Cards Mobile --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                
                {{-- Vista Desktop (Tabla) --}}
                <div class="hidden md:block overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-900">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Usuario
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Rol
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Destino
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Estado
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Última Conexión
                                </th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Acciones
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse ($users as $user)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition duration-150">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                <div class="h-10 w-10 rounded-full bg-gradient-to-br from-primary-400 to-secondary-500 flex items-center justify-center text-white font-bold">
                                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                                </div>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                    @if($user->jerarquia)
                                                        <span class="text-warning-600 dark:text-warning-400 font-semibold">{{ $user->jerarquia }}</span>
                                                    @endif
                                                    {{ $user->name }}
                                                </div>
                                                <div class="text-sm text-gray-500 dark:text-gray-400">
                                                    {{ $user->email }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @php
                                            $roleColors = [
                                                'super_admin' => 'bg-danger-100 text-danger-800 dark:bg-danger-900 dark:text-danger-200',
                                                'admin' => 'bg-secondary-100 text-secondary-800 dark:bg-secondary-900 dark:text-secondary-200',
                                                'panel-carga' => 'bg-secondary-100 text-secondary-800 dark:bg-secondary-900 dark:text-secondary-200',
                                                'panel-consulta' => 'bg-success-100 text-success-800 dark:bg-success-900 dark:text-success-200',
                                            ];
                                            $roleName = $user->roles->first()?->name ?? 'sin-rol';
                                            $roleColor = $roleColors[$roleName] ?? 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300';
                                        @endphp
                                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $roleColor }}">
                                            {{ $user->roles->first()?->label ?? 'Sin Rol' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                        {{ $user->brigada?->nombre ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if ($user->active)
                                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-success-100 text-success-800 dark:bg-success-900 dark:text-success-200">
                                                ✓ Activo
                                            </span>
                                        @else
                                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-danger-100 text-danger-800 dark:bg-danger-900 dark:text-danger-200">
                                                ✗ Inactivo
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                        {{ $user->last_login_at ? $user->last_login_at->diffForHumans() : 'Nunca' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                                        <a href="{{ route('admin.users.history', $user) }}" class="text-secondary-600 hover:text-blue-900 dark:text-secondary-400 dark:hover:text-blue-300" title="Ver Historial">
                                            📊
                                        </a>
                                        <a href="{{ route('admin.users.show', $user) }}" class="text-primary-600 hover:text-primary-900 dark:text-primary-400 dark:hover:text-primary-300" title="Ver Detalles">
                                            👁️
                                        </a>
                                        <a href="{{ route('admin.users.edit', $user) }}" class="text-warning-600 hover:text-warning-900 dark:text-warning-400 dark:hover:text-warning-300" title="Editar">
                                            ✏️
                                        </a>
                                        <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline" onsubmit="return confirm('¿Estás seguro de eliminar este usuario?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-danger-600 hover:text-danger-900 dark:text-danger-400 dark:hover:text-danger-300" title="Eliminar">
                                                🗑️
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                        No se encontraron usuarios.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Vista Mobile (Cards) --}}
                <div class="md:hidden space-y-4 p-4">
                    @forelse ($users as $user)
                        <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 border border-gray-200 dark:border-gray-600">
                            <div class="flex items-start justify-between mb-3">
                                <div class="flex items-center">
                                    <div class="h-12 w-12 rounded-full bg-gradient-to-br from-primary-400 to-secondary-500 flex items-center justify-center text-white font-bold text-lg">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                    <div class="ml-3">
                                        <div class="font-medium text-gray-900 dark:text-gray-100">
                                            @if($user->jerarquia)
                                                <span class="text-warning-600 dark:text-warning-400 font-semibold text-xs">{{ $user->jerarquia }}</span>
                                            @endif
                                            {{ $user->name }}
                                        </div>
                                        <div class="text-sm text-gray-600 dark:text-gray-400">{{ $user->email }}</div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-2 gap-2 mb-3 text-sm">
                                @if($user->jerarquia)
                                <div class="col-span-2">
                                    <span class="text-gray-500 dark:text-gray-400">Jerarquía:</span>
                                    <span class="ml-1 text-gray-900 dark:text-gray-100 font-semibold">{{ $user->jerarquia }}</span>
                                </div>
                                @endif
                                <div>
                                    <span class="text-gray-500 dark:text-gray-400">Rol:</span>
                                    @php
                                        $roleColors = [
                                            'super_admin' => 'bg-danger-100 text-danger-800',
                                            'admin' => 'bg-secondary-100 text-secondary-800',
                                            'panel-carga' => 'bg-secondary-100 text-secondary-800',
                                            'panel-consulta' => 'bg-success-100 text-success-800',
                                        ];
                                        $roleName = $user->roles->first()?->name ?? 'sin-rol';
                                        $roleColor = $roleColors[$roleName] ?? 'bg-gray-100 text-gray-800';
                                    @endphp
                                    <span class="ml-1 px-2 py-0.5 inline-flex text-xs font-semibold rounded {{ $roleColor }}">
                                        {{ $user->roles->first()?->label ?? 'Sin Rol' }}
                                    </span>
                                </div>
                                <div>
                                    <span class="text-gray-500 dark:text-gray-400">Estado:</span>
                                    @if ($user->active)
                                        <span class="ml-1 px-2 py-0.5 inline-flex text-xs font-semibold rounded bg-success-100 text-success-800">
                                            Activo
                                        </span>
                                    @else
                                        <span class="ml-1 px-2 py-0.5 inline-flex text-xs font-semibold rounded bg-danger-100 text-danger-800">
                                            Inactivo
                                        </span>
                                    @endif
                                </div>
                                <div class="col-span-2">
                                    <span class="text-gray-500 dark:text-gray-400">Destino:</span>
                                    <span class="ml-1 text-gray-900 dark:text-gray-100">{{ $user->brigada?->nombre ?? '-' }}</span>
                                </div>
                                <div class="col-span-2">
                                    <span class="text-gray-500 dark:text-gray-400">Última conexión:</span>
                                    <span class="ml-1 text-gray-900 dark:text-gray-100">{{ $user->last_login_at ? $user->last_login_at->diffForHumans() : 'Nunca' }}</span>
                                </div>
                            </div>

                            <div class="flex gap-2 flex-wrap">
                                <a href="{{ route('admin.users.history', $user) }}" class="flex-1 text-center px-3 py-2 bg-secondary-600 text-white text-xs rounded hover:bg-secondary-700 transition">
                                    📊 Historial
                                </a>
                                <a href="{{ route('admin.users.show', $user) }}" class="flex-1 text-center px-3 py-2 bg-primary-600 text-white text-xs rounded hover:bg-primary-700 transition">
                                    👁️ Ver
                                </a>
                                <a href="{{ route('admin.users.edit', $user) }}" class="flex-1 text-center px-3 py-2 bg-warning-600 text-white text-xs rounded hover:bg-warning-700 transition">
                                    ✏️ Editar
                                </a>
                                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="flex-1" onsubmit="return confirm('¿Eliminar usuario?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-full px-3 py-2 bg-danger-600 text-white text-xs rounded hover:bg-danger-700 transition">
                                        🗑️
                                    </button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <p class="text-center text-gray-500 dark:text-gray-400 py-8">No se encontraron usuarios.</p>
                    @endforelse
                </div>

                {{-- Paginación --}}
                @if ($users->hasPages())
                    <div class="px-4 py-3 border-t border-gray-200 dark:border-gray-700">
                        {{ $users->links() }}
                    </div>
                @endif

            </div>
        </div>
    </div>
</x-app-layout>







