<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Subir Documento</h2>
    </x-slot>

    <div class="max-w-3xl mx-auto py-6 sm:px-6 lg:px-8">
        <div class="bg-white shadow sm:rounded-lg p-6">
            <form action="{{ route('documentos.store') }}" method="POST" enctype="multipart/form-data" class="mt-6 space-y-4">
                @csrf

                <div>
                    <label class="block text-sm font-medium text-gray-700">Título</label>
                    <input type="text" name="titulo" value="{{ old('titulo') }}" required class="mt-1 block w-full border-gray-300 rounded-md" />
                    @error('titulo')<p class="text-red-500 text-sm">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Descripción</label>
                    <textarea name="descripcion" class="mt-1 block w-full border-gray-300 rounded-md">{{ old('descripcion') }}</textarea>
                    @error('descripcion')<p class="text-red-500 text-sm">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Archivo (PDF, DOC, DOCX)</label>
                    <input type="file" name="archivo" accept=".pdf,.doc,.docx" required class="mt-1 block w-full" />
                    @error('archivo')<p class="text-red-500 text-sm">{{ $message }}</p>@enderror
                </div>

                <div class="flex items-center gap-3">
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Subir Documento</button>
                    <a href="{{ route('documentos.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 rounded">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
