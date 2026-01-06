<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                📋 Gestión de Brigadas
            </h2>
            <a href="{{ route('admin.brigadas.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                ➕ Nueva Brigada
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Alertas -->
            @if (session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            @if (session('error'))
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if ($brigadas->isEmpty())
                        <div class="text-center py-8">
                            <p class="text-gray-500 text-lg">No hay brigadas registradas.</p>
                            <a href="{{ route('admin.brigadas.create') }}" class="mt-4 inline-block bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                ➕ Crear Primera Brigada
                            </a>
                        </div>
                    @else
                        <!-- Vista Desktop: Tabla -->
                        <div class="hidden md:block overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            ID
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Nombre
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Usuarios Asignados
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Acciones
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach ($brigadas as $brigada)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $brigada->id }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ $brigada->nombre }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                                    {{ $brigada->users_count }} usuario(s)
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                                <a href="{{ route('admin.brigadas.edit', $brigada) }}" class="text-indigo-600 hover:text-indigo-900">
                                                    ✏️ Editar
                                                </a>
                                                <form action="{{ route('admin.brigadas.destroy', $brigada) }}" method="POST" class="inline-block" onsubmit="return confirm('¿Estás seguro de eliminar la brigada {{ $brigada->nombre }}?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900">
                                                        🗑️ Eliminar
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Vista Mobile: Cards -->
                        <div class="md:hidden space-y-4">
                            @foreach ($brigadas as $brigada)
                                <div class="bg-white border rounded-lg p-4 shadow">
                                    <div class="flex justify-between items-start mb-2">
                                        <div>
                                            <h3 class="text-lg font-semibold text-gray-900">{{ $brigada->nombre }}</h3>
                                            <p class="text-sm text-gray-500">ID: {{ $brigada->id }}</p>
                                        </div>
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                            {{ $brigada->users_count }} usuario(s)
                                        </span>
                                    </div>
                                    <div class="flex space-x-2 mt-3">
                                        <a href="{{ route('admin.brigadas.edit', $brigada) }}" class="flex-1 bg-indigo-500 hover:bg-indigo-700 text-white text-center py-2 px-3 rounded text-sm">
                                            ✏️ Editar
                                        </a>
                                        <form action="{{ route('admin.brigadas.destroy', $brigada) }}" method="POST" class="flex-1" onsubmit="return confirm('¿Eliminar {{ $brigada->nombre }}?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="w-full bg-red-500 hover:bg-red-700 text-white py-2 px-3 rounded text-sm">
                                                🗑️ Eliminar
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Paginación -->
                        <div class="mt-6">
                            {{ $brigadas->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
