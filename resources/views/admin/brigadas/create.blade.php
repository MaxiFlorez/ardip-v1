<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            ➕ Nueva Brigada
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('admin.brigadas.store') }}" method="POST">
                        @csrf

                        <div class="mb-6">
                            <label for="nombre" class="block text-sm font-medium text-gray-700 mb-2">
                                Nombre de la Brigada <span class="text-danger-500">*</span>
                            </label>
                            <input 
                                type="text" 
                                name="nombre" 
                                id="nombre" 
                                value="{{ old('nombre') }}"
                                class="w-full border-gray-300 rounded-md shadow-sm focus:border-primary-500 focus:ring-primary-500 @error('nombre') border-danger-500 @enderror"
                                placeholder="Ej: Brigada Norte"
                                required
                            >
                            @error('nombre')
                                <p class="mt-1 text-sm text-danger-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center justify-between">
                            <a href="{{ route('admin.brigadas.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                ← Volver
                            </a>
                            <button type="submit" class="bg-secondary-600 hover:bg-secondary-700 text-white font-bold py-2 px-4 rounded">
                                💾 Crear Brigada
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>







