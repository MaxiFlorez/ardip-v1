<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <h2 class="font-semibold text-xl md:text-2xl text-gray-800 dark:text-gray-200 leading-tight">
                📚 Biblioteca Digital
            </h2>

            @can('operativo-escritura')
                <a href="{{ route('documentos.create') }}" class="inline-flex items-center px-4 py-2 bg-primary-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-primary-700 active:bg-primary-900 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    ➕ Subir Documento
                </a>
            @endcan
        </div>
    </x-slot>

    <div class="py-6 md:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if($documentos->count() === 0)
                <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg p-6">
                    <p class="text-center text-gray-500 dark:text-gray-400">No hay documentos disponibles.</p>
                </div>
            @else
                <x-tabla :headers="['Título', 'Descripción', 'Fecha Subida', 'Acciones']">
                    @foreach($documentos as $doc)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition duration-150">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">{{ $doc->titulo }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ \Illuminate\Support\Str::limit($doc->descripcion, 80) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $doc->created_at->format('d/m/Y H:i') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-3">
                                <a href="{{ route('documentos.download', $doc) }}" class="text-success-600 hover:text-success-900 dark:text-success-400 dark:hover:text-success-300" title="Descargar">
                                    📥
                                </a>
                                @can('panel-carga')
                                    <form action="{{ route('documentos.destroy', $doc) }}" method="POST" class="inline" onsubmit="return confirm('¿Confirma que desea eliminar este documento?');">
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
                        @foreach($documentos as $doc)
                            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 border border-gray-200 dark:border-gray-600">
                                <div class="mb-3">
                                    <h3 class="text-base font-semibold text-gray-900 dark:text-gray-100">{{ $doc->titulo }}</h3>
                                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ \Illuminate\Support\Str::limit($doc->descripcion, 80) }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">{{ $doc->created_at->format('d/m/Y H:i') }}</p>
                                </div>
                                <div class="flex gap-2 flex-wrap">
                                    <a href="{{ route('documentos.download', $doc) }}" class="flex-1 text-center px-3 py-2 bg-success-600 text-white text-xs rounded hover:bg-success-700 transition">
                                        📥 Descargar
                                    </a>
                                    @can('panel-carga')
                                        <form action="{{ route('documentos.destroy', $doc) }}" method="POST" class="flex-1" onsubmit="return confirm('¿Confirma que desea eliminar este documento?');">
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

                @if ($documentos->hasPages())
                    <div class="mt-4">
                        {{ $documentos->links() }}
                    </div>
                @endif
            @endif
        </div>
    </div>
</x-app-layout>
