<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Listado de Personas') }}
            </h2>
            <a href="{{ route('procedimientos.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded">
                {{ __('Nueva Carga') }}
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-4 md:p-6 text-gray-900">
                    @if(isset($personas) && $personas->count())
                        <!-- Tabla en md+ -->
                        <div class="hidden md:block overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 text-sm md:text-base">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">DNI</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Apellidos</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombres</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Alias</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Edad</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($personas as $persona)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $persona->dni }}</td>
                                            <td class="px-6 py-4 text-sm text-gray-900 break-words">{{ $persona->apellidos }}</td>
                                            <td class="px-6 py-4 text-sm text-gray-900 break-words">{{ $persona->nombres }}</td>
                                            <td class="px-6 py-4 text-sm text-gray-500 break-words">{{ $persona->alias ?? '-' }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $persona->edad }} {{ __('años') }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                                <a href="{{ route('personas.show', $persona) }}" class="text-blue-600 hover:text-blue-900">{{ __('Ver') }}</a>
                                                <a href="{{ route('personas.edit', $persona) }}" class="text-indigo-600 hover:text-indigo-900">{{ __('Editar') }}</a>
                                                <form action="{{ route('personas.destroy', $persona) }}" method="POST" class="inline" onsubmit="return confirm('¿Estás seguro de eliminar esta persona?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900">{{ __('Eliminar') }}</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Cards en móvil -->
                        <div class="block md:hidden space-y-4">
                            @foreach($personas as $persona)
                                <div class="rounded-lg border border-gray-200 p-4">
                                    <div class="flex items-center justify-between">
                                        <div class="text-sm font-semibold text-gray-900">DNI: {{ $persona->dni }}</div>
                                        <div class="text-xs text-gray-500">{{ $persona->edad }} {{ __('años') }}</div>
                                    </div>
                                    <div class="mt-1 text-sm text-gray-900 break-words">{{ $persona->apellidos }}, {{ $persona->nombres }}</div>
                                    <div class="mt-1 text-xs text-gray-600 break-words"><span class="font-medium">Alias:</span> {{ $persona->alias ?? '—' }}</div>
                                    <div class="mt-3 grid grid-cols-2 gap-2">
                                        <a href="{{ route('personas.show', $persona) }}" class="text-center bg-indigo-600 hover:bg-indigo-700 text-white py-2 rounded text-sm">Ver</a>
                                        <a href="{{ route('personas.edit', $persona) }}" class="text-center bg-gray-100 hover:bg-gray-200 text-gray-800 py-2 rounded text-sm">Editar</a>
                                        <form action="{{ route('personas.destroy', $persona) }}" method="POST" class="col-span-2" onsubmit="return confirm('¿Eliminar persona?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="w-full text-center bg-red-600 hover:bg-red-700 text-white py-2 rounded text-sm">Eliminar</button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500">{{ __('No hay personas registradas.') }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
 
 

 

 

 
 
 






