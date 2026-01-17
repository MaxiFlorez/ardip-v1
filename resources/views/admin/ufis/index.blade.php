<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <h2 class="font-semibold text-xl md:text-2xl text-gray-800 dark:text-gray-200 leading-tight">
                🏛️ Gestión de UFIs
            </h2>
            <a href="{{ route('admin.ufis.create') }}" class="inline-flex items-center px-4 py-2 bg-primary-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-primary-700 active:bg-primary-900 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 transition ease-in-out duration-150">
                ➕ Nueva UFI
            </a>
        </div>
    </x-slot>

    <div class="py-6 md:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- Alertas --}}
            @if (session('success'))
                <div class="mb-6 bg-success-50 dark:bg-success-900/30 border-l-4 border-success-500 p-4 rounded-lg shadow-sm">
                    <div class="flex items-center">
                        <span class="text-2xl mr-3">✅</span>
                        <span class="text-success-800 dark:text-success-200 font-medium">{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            @if (session('error'))
                <div class="mb-6 bg-danger-50 dark:bg-danger-900/30 border-l-4 border-danger-500 p-4 rounded-lg shadow-sm">
                    <div class="flex items-center">
                        <span class="text-2xl mr-3">❌</span>
                        <span class="text-danger-800 dark:text-danger-200 font-medium">{{ session('error') }}</span>
                    </div>
                </div>
            @endif

            {{-- Barra de Filtros --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-4 md:p-6 text-gray-900 dark:text-gray-100">
                    <form method="GET" action="{{ route('admin.ufis.index') }}" class="flex items-center gap-2">
                        
                        {{-- Buscador --}}
                        <div class="flex-1">
                            <label for="search" class="block font-medium text-sm text-gray-700 dark:text-gray-300 mb-1">Buscar</label>
                            <input 
                                type="text" 
                                name="search" 
                                id="search" 
                                value="{{ request('search') }}"
                                placeholder="Nombre de UFI..."
                                class="w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-primary-500 dark:focus:border-primary-600 focus:ring-primary-500 dark:focus:ring-primary-600 text-sm"
                            >
                        </div>

                        {{-- Botones --}}
                        <div class="flex items-end gap-2">
                            <button type="submit" class="px-4 py-2 bg-primary-600 text-white rounded-md hover:bg-primary-700 transition duration-150 text-sm font-medium">
                                🔍 Filtrar
                            </button>
                            <a href="{{ route('admin.ufis.index') }}" class="px-4 py-2 text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 transition duration-150 text-sm">
                                Limpiar
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Tabla Desktop / Cards Mobile --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @if ($ufis->isEmpty())
                        <div class="text-center py-8">
                            <p class="text-gray-500 text-lg">No hay UFIs registradas.</p>
                            <a href="{{ route('admin.ufis.create') }}" class="mt-4 inline-flex items-center px-4 py-2 bg-primary-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-primary-700 active:bg-primary-900 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                ➕ Crear Primera UFI
                            </a>
                        </div>
                    @else
                        <!-- Vista Desktop: Tabla -->
                        <div class="hidden md:block overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Nombre
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Procedimientos Asociados
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Acciones
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach ($ufis as $ufi)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ $ufi->nombre }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-success-100 text-success-800">
                                                    {{ $ufi->procedimientos_count }} procedimiento(s)
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                                @can('super-admin')
                                                    <a href="{{ route('admin.ufis.edit', $ufi) }}" class="text-primary-600 hover:text-primary-900">
                                                        ✏️ Editar
                                                    </a>
                                                    <form action="{{ route('admin.ufis.destroy', $ufi) }}" method="POST" class="inline-block" onsubmit="return confirm('¿Estás seguro de eliminar la UFI {{ $ufi->nombre }}?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-danger-600 hover:text-danger-900">
                                                            🗑️ Eliminar
                                                        </button>
                                                    </form>
                                                @endcan
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Vista Mobile: Cards -->
                        <div class="md:hidden space-y-4">
                            @foreach ($ufis as $ufi)
                                <div class="bg-white border rounded-lg p-4 shadow">
                                    <div class="flex justify-between items-start mb-2">
                                        <div>
                                            <h3 class="text-lg font-semibold text-gray-900">{{ $ufi->nombre }}</h3>
                                            <p class="text-sm text-gray-500">ID: {{ $ufi->id }}</p>
                                        </div>
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-success-100 text-success-800">
                                            {{ $ufi->procedimientos_count }} proc.
                                        </span>
                                    </div>
                                    <div class="flex space-x-2 mt-3">
                                        @can('super-admin')
                                            <a href="{{ route('admin.ufis.edit', $ufi) }}" class="flex-1 bg-primary-500 hover:bg-primary-700 text-white text-center py-2 px-3 rounded text-sm">
                                                ✏️ Editar
                                            </a>
                                            <form action="{{ route('admin.ufis.destroy', $ufi) }}" method="POST" class="flex-1" onsubmit="return confirm('¿Eliminar {{ $ufi->nombre }}?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="w-full bg-danger-500 hover:bg-danger-700 text-white py-2 px-3 rounded text-sm">
                                                    🗑️ Eliminar
                                                </button>
                                            </form>
                                        @endcan
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Paginación -->
                        <div class="mt-6">
                            {{ $ufis->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>







