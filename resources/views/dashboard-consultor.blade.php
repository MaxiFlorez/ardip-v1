<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Panel de Consultas - ARDIP
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold mb-4">Accesos Rápidos</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <a href="{{ route('personas.index') }}" class="block p-6 bg-blue-50 hover:bg-blue-100 rounded-lg">
                        <div class="text-blue-600 text-3xl mb-2">👤</div>
                        <h4 class="font-semibold">Personas</h4>
                        <p class="text-sm text-gray-600">Consultar fichas de personas</p>
                    </a>

                    <a href="{{ route('procedimientos.index') }}" class="block p-6 bg-green-50 hover:bg-green-100 rounded-lg">
                        <div class="text-green-600 text-3xl mb-2">📋</div>
                        <h4 class="font-semibold">Procedimientos</h4>
                        <p class="text-sm text-gray-600">Ver procedimientos realizados</p>
                    </a>

                    <a href="{{ route('domicilios.index') }}" class="block p-6 bg-yellow-50 hover:bg-yellow-100 rounded-lg">
                        <div class="text-yellow-600 text-3xl mb-2">🏠</div>
                        <h4 class="font-semibold">Domicilios</h4>
                        <p class="text-sm text-gray-600">Buscar domicilios registrados</p>
                    </a>

                    <a href="{{ route('documentos.index') }}" class="block p-6 bg-purple-50 hover:bg-purple-100 rounded-lg">
                        <div class="text-purple-600 text-3xl mb-2">📚</div>
                        <h4 class="font-semibold">Documentos</h4>
                        <p class="text-sm text-gray-600">Biblioteca digital</p>
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
