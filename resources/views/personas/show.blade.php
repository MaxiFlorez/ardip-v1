<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detalle de Persona') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    
                    <!-- Foto y datos principales -->
                    <div class="flex items-start space-x-6 mb-6">
                        <!-- Foto -->
                        <div class="flex-shrink-0">
                            @if($persona->foto)
                                <img src="{{ asset('storage/' . $persona->foto) }}" 
                                     alt="Foto de {{ $persona->nombres }}"
                                     class="w-40 h-40 object-cover rounded-lg border-2 border-gray-300">
                            @else
                                <div class="w-40 h-40 bg-gray-200 rounded-lg flex items-center justify-center border-2 border-gray-300">
                                    <svg class="w-20 h-20 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            @endif
                        </div>

                        <!-- Datos principales -->
                        <div class="flex-grow">
                            <h3 class="text-2xl font-bold text-gray-900">
                                {{ $persona->apellidos }}, {{ $persona->nombres }}
                            </h3>
                            @if($persona->alias)
                                <p class="text-lg text-gray-600 italic">"{{ $persona->alias }}"</p>
                            @endif
                            <div class="mt-4 grid grid-cols-2 gap-4">
                                <div>
                                    <span class="text-sm text-gray-500">DNI:</span>
                                    <p class="text-lg font-semibold">{{ $persona->dni }}</p>
                                </div>
                                <div>
                                    <span class="text-sm text-gray-500">Edad:</span>
                                    <p class="text-lg font-semibold">{{ $persona->edad }} años</p>
                                </div>
                                <div>
                                    <span class="text-sm text-gray-500">Género:</span>
                                    <p class="text-lg">{{ ucfirst($persona->genero) }}</p>
                                </div>
                                <div>
                                    <span class="text-sm text-gray-500">Nacionalidad:</span>
                                    <p class="text-lg">{{ $persona->nacionalidad }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Línea divisoria -->
                    <div class="border-t border-gray-200 my-6"></div>

                    <!-- Información adicional -->
                    <div class="grid grid-cols-2 gap-6 mb-6">
                        <div>
                            <h4 class="text-sm font-medium text-gray-500 mb-2">Fecha de Nacimiento</h4>
                            <p class="text-base">{{ $persona->fecha_nacimiento->format('d/m/Y') }}</p>
                        </div>
                        @if($persona->estado_civil)
                            <div>
                                <h4 class="text-sm font-medium text-gray-500 mb-2">Estado Civil</h4>
                                <p class="text-base">{{ ucfirst($persona->estado_civil) }}</p>
                            </div>
                        @endif
                    </div>

                    @if($persona->observaciones)
                        <div class="mb-6">
                            <h4 class="text-sm font-medium text-gray-500 mb-2">Observaciones</h4>
                            <p class="text-base text-gray-700 whitespace-pre-line">{{ $persona->observaciones }}</p>
                        </div>
                    @endif

                    <!-- Domicilio Legal -->
                    <div class="border-t border-gray-200 pt-6">
                        <h4 class="text-lg font-semibold text-gray-900 mb-2">Domicilio Legal</h4>
                        @php $domLeg = optional($persona->domicilio); @endphp
                        @if($domLeg)
                            <p class="text-base text-gray-800">{{ $domLeg->direccion_completa }}</p>
                        @else
                            <p class="text-sm text-gray-500">Sin domicilio legal registrado.</p>
                        @endif
                    </div>

                    <!-- Procedimientos asociados -->
                    @if($persona->procedimientos->count() > 0)
                        <div class="border-t border-gray-200 pt-6">
                            <h4 class="text-lg font-semibold text-gray-900 mb-4">Procedimientos Asociados ({{ $persona->procedimientos->count() }})</h4>
                            <div class="space-y-3">
                                @foreach($persona->procedimientos as $proc)
                                    <div class="bg-gray-50 p-4 rounded-lg">
                                        <div class="flex justify-between items-start">
                                            <div>
                                                <p class="font-semibold text-gray-900">{{ $proc->legajo_fiscal }}</p>
                                                <p class="text-sm text-gray-600">{{ $proc->caratula }}</p>
                                                <p class="text-sm text-gray-500 mt-1">
                                                    Fecha: {{ $proc->fecha_procedimiento->format('d/m/Y') }}
                                                </p>
                                            </div>
                                            <div class="text-right">
                                                <span class="inline-block px-3 py-1 text-sm rounded-full
                                                    @if($proc->pivot->situacion_procesal == 'detenido') bg-red-100 text-red-800
                                                    @elseif($proc->pivot->situacion_procesal == 'notificado') bg-blue-100 text-blue-800
                                                    @elseif($proc->pivot->situacion_procesal == 'no_hallado') bg-yellow-100 text-yellow-800
                                                    @else bg-gray-100 text-gray-800
                                                    @endif">
                                                    {{ ucfirst(str_replace('_', ' ', $proc->pivot->situacion_procesal)) }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <div class="border-t border-gray-200 pt-6">
                            <p class="text-gray-500 text-center py-4">No hay procedimientos asociados a esta persona.</p>
                        </div>
                    @endif

                    <!-- Botones de acción -->
                    <div class="flex justify-end space-x-3 mt-8 pt-6 border-t border-gray-200">
                        <a href="{{ route('personas.index') }}" 
                           class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                            Volver al listado
                        </a>
                        <a href="{{ route('personas.edit', $persona) }}" 
                           class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Editar
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>