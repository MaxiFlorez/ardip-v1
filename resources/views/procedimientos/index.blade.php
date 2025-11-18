<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-lg md:text-xl lg:text-2xl text-gray-800 leading-tight">
            {{ __('Listado de Procedimientos') }}
        </h2>
    </x-slot>

    <div class="py-8 md:py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-4 md:p-6 lg:p-8 text-gray-900">

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

                    <h2 class="text-xl font-semibold leading-tight text-gray-800 mb-6">U.F.I. Delitos contra la Propiedad</h2>

                    <div class="flex justify-end mb-4">
                        <a href="{{ route('procedimientos.create') }}"
                           class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded w-full md:w-auto text-center">
                            Nueva Carga
                        </a>
                    </div>

                    <!-- Tabla en md+ -->
                    <div class="hidden md:block overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 text-sm md:text-base">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Legajo Fiscal</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Carátula</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Brigada</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Orden Judicial</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($procedimientos as $procedimiento)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $procedimiento->legajo_fiscal }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-500 break-words">{{ Str::limit($procedimiento->caratula, 40) }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $procedimiento->fecha_procedimiento->format('d/m/Y') }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-500 break-words">{{ $procedimiento->brigada->nombre ?? 'N/A' }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-500 break-words">{{ $procedimiento->orden_judicial ?? 'N/A' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <a href="{{ route('procedimientos.show', $procedimiento) }}" class="text-indigo-600 hover:text-indigo-900">Ver</a>
                                            <a href="{{ route('procedimientos.edit', $procedimiento) }}" class="text-indigo-600 hover:text-indigo-900 ml-4">Editar</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">No hay procedimientos registrados.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Cards en móvil -->
                    <div class="block md:hidden space-y-4">
                        @forelse ($procedimientos as $procedimiento)
                            <div class="rounded-lg border border-gray-200 p-4">
                                <div class="flex items-center justify-between">
                                    <div class="text-sm font-semibold text-gray-900">Legajo: {{ $procedimiento->legajo_fiscal }}</div>
                                    <div class="text-xs text-gray-500">{{ $procedimiento->fecha_procedimiento->format('d/m/Y') }}</div>
                                </div>
                                <div class="mt-1 text-xs text-gray-700 break-words">{{ Str::limit($procedimiento->caratula, 80) }}</div>
                                <div class="mt-1 text-xs text-gray-600 break-words"><span class="font-medium">Brigada:</span> {{ $procedimiento->brigada->nombre ?? 'N/A' }}</div>
                                <div class="mt-1 text-xs text-gray-600 break-words"><span class="font-medium">Orden Judicial:</span> {{ $procedimiento->orden_judicial ?? 'N/A' }}</div>
                                <div class="mt-3 grid grid-cols-2 gap-2">
                                    <a href="{{ route('procedimientos.show', $procedimiento) }}" class="text-center bg-indigo-600 hover:bg-indigo-700 text-white py-2 rounded text-sm">Ver</a>
                                    <a href="{{ route('procedimientos.edit', $procedimiento) }}" class="text-center bg-gray-100 hover:bg-gray-200 text-gray-800 py-2 rounded text-sm">Editar</a>
                                </div>
                            </div>
                        @empty
                            <p class="text-center text-sm text-gray-500">No hay procedimientos registrados.</p>
                        @endforelse
                    </div>
                    
                    <div class="mt-4">
                        {{ $procedimientos->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>