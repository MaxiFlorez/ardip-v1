<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-lg md:text-xl lg:text-2xl text-gray-800 leading-tight">
            {{ __('Gestión de Domicilios') }}
        </h2>
    </x-slot>

    <div class="py-8 md:py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                {{-- Bloque para mostrar mensajes de éxito (si existen) --}}
                @if (session('success'))
                    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mx-6 mt-6 rounded-md" role="alert">
                        <p class="font-bold">Éxito</p>
                        <p>{{ session('success') }}</p>
                    </div>
                @endif
                <div class="p-4 md:p-6 lg:p-8">
                    <!-- Tabla en desktop/tablet -->
                    <div class="hidden md:block overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 text-sm md:text-base">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dirección</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Departamento</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Provincia</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($domicilios as $dom)
                                    <tr>
                                        <td class="px-4 py-2 text-sm text-gray-900 break-words">{{ $dom->direccion_completa }}</td>
                                        <td class="px-4 py-2 text-sm text-gray-700">{{ optional($dom->departamento)->nombre }}</td>
                                        <td class="px-4 py-2 text-sm text-gray-700">{{ optional($dom->provincia)->nombre }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="px-4 py-6 text-center text-sm text-gray-500">No hay domicilios para mostrar.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Cards en móvil -->
                    <div class="block md:hidden space-y-4">
                        @forelse($domicilios as $dom)
                            <div class="rounded-lg border border-gray-200 p-4">
                                <div class="text-sm font-semibold text-gray-900 break-words">{{ $dom->direccion_completa }}</div>
                                <div class="mt-1 text-xs text-gray-600"><span class="font-medium">Departamento:</span> {{ optional($dom->departamento)->nombre ?? '—' }}</div>
                                <div class="mt-1 text-xs text-gray-600"><span class="font-medium">Provincia:</span> {{ optional($dom->provincia)->nombre ?? '—' }}</div>
                            </div>
                        @empty
                            <p class="text-center text-sm text-gray-500">No hay domicilios para mostrar.</p>
                        @endforelse
                    </div>
                    <div class="mt-4">
                        {{ $domicilios->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>