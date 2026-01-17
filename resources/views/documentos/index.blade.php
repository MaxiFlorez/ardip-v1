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

        <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg p-6">
            <div class="flex items-center justify-between">
                <div></div>

                @if($documentos->count() === 0)
                    <p class="text-gray-500 dark:text-gray-400">No hay documentos disponibles.</p>
                @else
                    <x-tabla :headers="['Título', 'Descripción', 'Fecha Subida', 'Acciones']">
                        @foreach($documentos as $doc)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition duration-150">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">{{ $doc->titulo }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ \Illuminate\Support\Str::limit($doc->descripcion, 80) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $doc->created_at->format('d/m/Y H:i') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex justify-end items-center space-x-3">
                                        <a href="{{ route('documentos.download', $doc) }}" class="text-success-600 hover:text-success-900 dark:text-success-400 dark:hover:text-success-300 text-xl" title="Descargar">
                                            📥
                                        </a>

                                        @can('panel-carga')
                                            <form action="{{ route('documentos.destroy', $doc) }}" method="POST" class="inline" onsubmit="return confirm('¿Confirma que desea eliminar este documento?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-danger-600 hover:text-danger-900 dark:text-danger-400 dark:hover:text-danger-300 text-xl" title="Eliminar">
                                                    🗑️
                                                </button>
                                            </form>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </x-tabla>

                    <div class="mt-4">
                        {{ $documentos->links() }}
                    </div>
                @endif
            </div>
        </div>
        </div>
    </div>
</x-app-layout>
