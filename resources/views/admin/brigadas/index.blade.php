<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <h2 class="font-semibold text-xl md:text-2xl text-gray-800 dark:text-gray-200 leading-tight">
                📋 Gestión de Brigadas
            </h2>
            <a href="{{ route('admin.brigadas.create') }}" class="inline-flex items-center px-4 py-2 bg-primary-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-primary-700 active:bg-primary-900 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 transition ease-in-out duration-150">
                ➕ Nueva Brigada
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Alertas -->
            @if (session('success'))
                <div class="mb-4 bg-success-100 border border-success-400 text-success-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            @if (session('error'))
                <div class="mb-4 bg-danger-100 border border-danger-400 text-danger-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            @if ($brigadas->isEmpty())
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <div class="text-center py-8">
                            <p class="text-gray-500 dark:text-gray-400 text-lg">No hay brigadas registradas.</p>
                            <a href="{{ route('admin.brigadas.create') }}" class="mt-4 inline-flex items-center px-4 py-2 bg-primary-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-primary-700 active:bg-primary-900 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                ➕ Crear Primera Brigada
                            </a>
                        </div>
                    </div>
                </div>
            @else
                <x-tabla :headers="['Nombre', 'Usuarios Asignados', 'Acciones']">
                    @foreach ($brigadas as $brigada)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition duration-150">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                    {{ $brigada->nombre }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-secondary-100 text-secondary-800 dark:bg-secondary-900 dark:text-secondary-200">
                                    {{ $brigada->users_count }} usuario(s)
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                                @can('super-admin')
                                    <a href="{{ route('admin.brigadas.edit', $brigada) }}" class="text-warning-600 hover:text-warning-900 dark:text-warning-400 dark:hover:text-warning-300" title="Editar">
                                        ✏️
                                    </a>
                                    <form action="{{ route('admin.brigadas.destroy', $brigada) }}" method="POST" class="inline" onsubmit="return confirm('¿Estás seguro de eliminar la brigada {{ $brigada->nombre }}?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-danger-600 hover:text-danger-900 dark:text-danger-400 dark:hover:text-danger-300" title="Eliminar">
                                            🗑️
                                        </button>
                                    </form>
                                @endcan
                            </td>
                        </tr>
                    @endforeach

                    <x-slot name="mobileView">
                        @foreach ($brigadas as $brigada)
                            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 border border-gray-200 dark:border-gray-600">
                                <div class="flex justify-between items-start mb-2">
                                    <div>
                                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ $brigada->nombre }}</h3>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">ID: {{ $brigada->id }}</p>
                                    </div>
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-secondary-100 text-secondary-800 dark:bg-secondary-900 dark:text-secondary-200">
                                        {{ $brigada->users_count }} usuario(s)
                                    </span>
                                </div>
                                <div class="flex gap-2 flex-wrap mt-3">
                                    @can('super-admin')
                                        <a href="{{ route('admin.brigadas.edit', $brigada) }}" class="flex-1 text-center px-3 py-2 bg-warning-600 text-white text-xs rounded hover:bg-warning-700 transition">
                                            ✏️ Editar
                                        </a>
                                        <form action="{{ route('admin.brigadas.destroy', $brigada) }}" method="POST" class="flex-1" onsubmit="return confirm('¿Eliminar {{ $brigada->nombre }}?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="w-full px-3 py-2 bg-danger-600 text-white text-xs rounded hover:bg-danger-700 transition">
                                                🗑️ Eliminar
                                            </button>
                                        </form>
                                    @endcan
                                </div>
                            </div>
                        @endforeach
                    </x-slot>
                </x-tabla>

                <!-- Paginación -->
                @if ($brigadas->hasPages())
                    <div class="mt-6">
                        {{ $brigadas->links() }}
                    </div>
                @endif
            @endif
        </div>
    </div>
</x-app-layout>







