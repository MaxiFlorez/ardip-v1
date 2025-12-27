<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Panel de Control') }}
            </h2>
            <form method="GET" action="{{ route('dashboard') }}" class="flex items-center gap-2">
                <input type="number" name="brigada_id" value="{{ request('brigada_id') }}" placeholder="Brigada ID"
                       class="border rounded px-3 py-2 w-32" />
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Filtrar</button>
                <a href="{{ route('dashboard') }}" class="text-gray-600">Limpiar</a>
            </form>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
                <!-- Card: Total Procedimientos -->
                <div class="bg-white p-6 rounded-lg shadow">
                    <h3 class="text-gray-500 text-sm">Procedimientos esta semana</h3>
                    <p class="text-4xl font-bold text-blue-600">{{ $stats['total_procedimientos'] }}</p>
                </div>
                <!-- Card: Detenidos -->
                <div class="bg-white p-6 rounded-lg shadow">
                    <h3 class="text-gray-500 text-sm">Personas Detenidas</h3>
                    <p class="text-4xl font-bold text-red-600">{{ $stats['total_detenidos'] }}</p>
                </div>
                <!-- Card: Secuestros -->
                <div class="bg-white p-6 rounded-lg shadow">
                    <h3 class="text-gray-500 text-sm">Secuestros Positivos</h3>
                    <p class="text-4xl font-bold text-green-600">{{ $stats['secuestros_positivos'] }}</p>
                </div>
                <!-- Card: Detenciones -->
                <div class="bg-white p-6 rounded-lg shadow">
                    <h3 class="text-gray-500 text-sm">Detenciones Positivas</h3>
                    <p class="text-4xl font-bold text-yellow-600">{{ $stats['detenciones_positivas'] }}</p>
                </div>
            </div>

            <!-- Tabla por Brigada -->
            <div class="mt-8 bg-white p-6 rounded-lg shadow">
                <h2 class="text-xl font-bold mb-4">Actividad por Brigada</h2>
                <table class="w-full">
                    <thead>
                        <tr class="border-b">
                            <th class="text-left py-2">Brigada</th>
                            <th class="text-center">Procedimientos</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($porBrigada as $item)
                            <tr class="border-b">
                                <td class="py-2">{{ optional($item->brigada)->nombre ?? 'N/D' }}</td>
                                <td class="text-center font-bold">{{ $item->total }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
