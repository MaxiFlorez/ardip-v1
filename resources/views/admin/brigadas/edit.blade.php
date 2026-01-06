<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            ✏️ Editar Brigada
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('admin.brigadas.update', $brigada) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-6">
                            <label for="nombre" class="block text-sm font-medium text-gray-700 mb-2">
                                Nombre de la Brigada <span class="text-danger-500">*</span>
                            </label>
                            <input 
                                type="text" 
                                name="nombre" 
                                id="nombre" 
                                value="{{ old('nombre', $brigada->nombre) }}"
                                class="w-full border-gray-300 rounded-md shadow-sm focus:border-primary-500 focus:ring-primary-500 @error('nombre') border-danger-500 @enderror"
                                required
                            >
                            @error('nombre')
                                <p class="mt-1 text-sm text-danger-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center justify-between">
                            <x-secondary-button onclick="window.history.back()">
                                ← Volver
                            </x-secondary-button>
                            <x-primary-button type="submit">
                                💾 Actualizar Brigada
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>







