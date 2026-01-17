<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <h2 class="font-semibold text-xl md:text-2xl text-gray-800 dark:text-gray-200 leading-tight">
                📄 Subir Documento
            </h2>
        </div>
    </x-slot>

    <div class="py-6 md:py-12">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    
                    {{-- Información --}}
                    <div class="mb-8 p-4 bg-primary-50 dark:bg-primary-900/30 border-l-4 border-primary-500 rounded">
                        <p class="text-sm text-primary-800 dark:text-primary-200">
                            <strong>ℹ️ Información:</strong> Puedes subir documentos en formato PDF, DOC o DOCX. El tamaño máximo es de 10MB.
                        </p>
                    </div>

                    <form action="{{ route('documentos.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                        @csrf

                        {{-- Título --}}
                        <div>
                            <label for="titulo" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                📌 Título del Documento
                            </label>
                            <input 
                                type="text" 
                                id="titulo"
                                name="titulo" 
                                value="{{ old('titulo') }}" 
                                placeholder="Ej: Resolución Administrativa 2026"
                                required 
                                class="w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-primary-500 dark:focus:border-primary-600 focus:ring-primary-500 dark:focus:ring-primary-600"
                            />
                            @error('titulo')
                                <p class="mt-1 text-sm text-danger-600 dark:text-danger-400">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Descripción --}}
                        <div>
                            <label for="descripcion" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                📝 Descripción (Opcional)
                            </label>
                            <textarea 
                                id="descripcion"
                                name="descripcion" 
                                rows="4"
                                placeholder="Descripción del documento..."
                                class="w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-primary-500 dark:focus:border-primary-600 focus:ring-primary-500 dark:focus:ring-primary-600"
                            >{{ old('descripcion') }}</textarea>
                            @error('descripcion')
                                <p class="mt-1 text-sm text-danger-600 dark:text-danger-400">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Archivo --}}
                        <div>
                            <label for="archivo" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                📎 Archivo
                            </label>
                            <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-dashed border-gray-300 dark:border-gray-700 rounded-md hover:border-primary-500 dark:hover:border-primary-600 transition cursor-pointer">
                                <div class="space-y-1 text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400 dark:text-gray-600" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                        <path d="M28 8H12a4 4 0 00-4 4v20a4 4 0 004 4h24a4 4 0 004-4V20m-8-12l-4-4m0 0l-4 4m4-4v12" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    <div class="flex text-sm text-gray-600 dark:text-gray-400">
                                        <label for="archivo" class="relative cursor-pointer rounded-md font-medium text-primary-600 dark:text-primary-400 hover:text-primary-700 dark:hover:text-primary-300">
                                            <span>Haz clic para seleccionar</span>
                                            <input 
                                                id="archivo" 
                                                name="archivo" 
                                                type="file" 
                                                accept=".pdf,.doc,.docx" 
                                                required 
                                                class="sr-only"
                                            />
                                        </label>
                                        <p class="pl-1">o arrastra el archivo aquí</p>
                                    </div>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                                        PDF, DOC o DOCX hasta 10MB
                                    </p>
                                </div>
                            </div>
                            @error('archivo')
                                <p class="mt-1 text-sm text-danger-600 dark:text-danger-400">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Botones de Acción --}}
                        <div class="flex items-center gap-3 pt-6 border-t border-gray-200 dark:border-gray-700">
                            <button 
                                type="submit" 
                                class="inline-flex items-center px-4 py-2 bg-success-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-success-700 active:bg-success-900 focus:outline-none focus:ring-2 focus:ring-success-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150"
                            >
                                ✅ Subir Documento
                            </button>
                            <a 
                                href="{{ url()->previous() }}" 
                                class="inline-flex items-center px-4 py-2 bg-gray-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-600 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150"
                            >
                                ❌ Cancelar
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
