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

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
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







