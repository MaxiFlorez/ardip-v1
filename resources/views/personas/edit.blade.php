<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Editar Persona') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('personas.update', $persona) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- DNI -->
                        <div class="mb-4">
                            <label for="dni" class="block text-sm font-medium text-gray-700">DNI</label>
                            <input type="text" name="dni" id="dni" value="{{ old('dni', $persona->dni) }}" 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                   maxlength="8" required>
                            @error('dni')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Nombres -->
                        <div class="mb-4">
                            <label for="nombres" class="block text-sm font-medium text-gray-700">Nombres</label>
                            <input type="text" name="nombres" id="nombres" value="{{ old('nombres', $persona->nombres) }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                   required>
                            @error('nombres')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Apellidos -->
                        <div class="mb-4">
                            <label for="apellidos" class="block text-sm font-medium text-gray-700">Apellidos</label>
                            <input type="text" name="apellidos" id="apellidos" value="{{ old('apellidos', $persona->apellidos) }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                   required>
                            @error('apellidos')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Fecha de Nacimiento -->
                        <div class="mb-4">
                            <label for="fecha_nacimiento" class="block text-sm font-medium text-gray-700">Fecha de Nacimiento</label>
                            <input type="date" name="fecha_nacimiento" id="fecha_nacimiento" 
                                   value="{{ old('fecha_nacimiento', $persona->fecha_nacimiento->format('Y-m-d')) }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                   required>
                            @error('fecha_nacimiento')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Género -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Género</label>
                            <div class="mt-2 space-y-2">
                                <label class="inline-flex items-center">
                                    <input type="radio" name="genero" value="masculino" 
                                           {{ old('genero', $persona->genero) == 'masculino' ? 'checked' : '' }}
                                           class="form-radio">
                                    <span class="ml-2">Masculino</span>
                                </label>
                                <label class="inline-flex items-center ml-6">
                                    <input type="radio" name="genero" value="femenino"
                                           {{ old('genero', $persona->genero) == 'femenino' ? 'checked' : '' }}
                                           class="form-radio">
                                    <span class="ml-2">Femenino</span>
                                </label>
                                <label class="inline-flex items-center ml-6">
                                    <input type="radio" name="genero" value="otro"
                                           {{ old('genero', $persona->genero) == 'otro' ? 'checked' : '' }}
                                           class="form-radio">
                                    <span class="ml-2">Otro</span>
                                </label>
                            </div>
                            @error('genero')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Alias -->
                        <div class="mb-4">
                            <label for="alias" class="block text-sm font-medium text-gray-700">Alias</label>
                            <input type="text" name="alias" id="alias" value="{{ old('alias', $persona->alias) }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            @error('alias')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Nacionalidad -->
                        <div class="mb-4">
                            <label for="nacionalidad" class="block text-sm font-medium text-gray-700">Nacionalidad</label>
                            <input type="text" name="nacionalidad" id="nacionalidad" 
                                   value="{{ old('nacionalidad', $persona->nacionalidad) }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            @error('nacionalidad')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Estado Civil -->
                        <div class="mb-4">
                            <label for="estado_civil" class="block text-sm font-medium text-gray-700">Estado Civil</label>
                            <select name="estado_civil" id="estado_civil"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                <option value="">Seleccionar...</option>
                                <option value="soltero" {{ old('estado_civil', $persona->estado_civil) == 'soltero' ? 'selected' : '' }}>Soltero/a</option>
                                <option value="casado" {{ old('estado_civil', $persona->estado_civil) == 'casado' ? 'selected' : '' }}>Casado/a</option>
                                <option value="divorciado" {{ old('estado_civil', $persona->estado_civil) == 'divorciado' ? 'selected' : '' }}>Divorciado/a</option>
                                <option value="viudo" {{ old('estado_civil', $persona->estado_ci