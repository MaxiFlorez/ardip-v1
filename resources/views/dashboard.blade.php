<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl md:text-2xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Tablero de Comando') }}
        </h2>
    </x-slot>

    <div class="py-6 md:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- Barra de Filtros --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-4 md:p-6 text-gray-900 dark:text-gray-100">
                    <form method="GET" action="{{ route('dashboard') }}" class="space-y-4 md:space-y-0 md:flex md:flex-wrap md:gap-4 md:items-end">
                        
                        {{-- Select de Periodo --}}
                        <div class="flex-1 md:flex-shrink-0 md:w-auto">
                            <label for="periodo" class="block font-medium text-sm text-gray-700 dark:text-gray-300 mb-1">Período</label>
                            <select name="periodo" id="periodo" class="w-full md:w-48 rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 text-sm">
                                <option value="semana" @selected($periodoActual == 'semana')>Semana</option>
                                <option value="mes" @selected($periodoActual == 'mes')>Mes</option>
                                <option value="anio" @selected($periodoActual == 'anio')>Año</option>
                            </select>
                        </div>

                        {{-- Select de Brigada --}}
                        <div class="flex-1 md:flex-shrink-0 md:w-auto">
                            <label for="brigada_id" class="block font-medium text-sm text-gray-700 dark:text-gray-300 mb-1">Brigada</label>
                            <select name="brigada_id" id="brigada_id" class="w-full md:w-48 rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 text-sm">
                                <option value="">Todas las Brigadas</option>
                                @foreach ($brigadas as $brigada)
                                    <option value="{{ $brigada->id }}" @selected($brigadaActual == $brigada->id)>
                                        {{ $brigada->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        {{-- Botones --}}
                        <div class="flex items-center gap-2 flex-wrap md:flex-nowrap">
                            <x-primary-button type="submit" class="w-full md:w-auto">
                                🔍 Filtrar
                            </x-primary-button>
                             <a href="{{ route('dashboard') }}" class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 transition duration-150 px-3 py-2">
                                Limpiar
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Grid de KPIs Responsive --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6">
                
                {{-- Card: Total Procedimientos --}}
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition duration-200">
                    <div class="p-4 md:p-6">
                        <h3 class="text-base md:text-lg font-medium text-gray-500 dark:text-gray-400">Total Procedimientos</h3>
                        <p class="mt-2 text-3xl md:text-4xl font-semibold text-gray-900 dark:text-gray-100">
                            {{ $totalProcedimientos }}
                        </p>
                        <p class="mt-2 text-xs md:text-sm text-gray-500 dark:text-gray-400">
                            Período: {{ ucfirst($periodoActual) }}
                        </p>
                    </div>
                </div>

                {{-- Card: Total Detenidos --}}
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition duration-200">
                    <div class="p-4 md:p-6">
                        <h3 class="text-base md:text-lg font-medium text-gray-500 dark:text-gray-400">Total Detenidos</h3>
                        <p class="mt-2 text-3xl md:text-4xl font-semibold text-gray-900 dark:text-gray-100">
                            {{ $totalDetenidos }}
                        </p>
                        <p class="mt-2 text-xs md:text-sm text-gray-500 dark:text-gray-400">
                            Período: {{ ucfirst($periodoActual) }}
                        </p>
                    </div>
                </div>

                {{-- Card: Allanamientos Positivos --}}
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg border-2 border-green-500 dark:border-green-400 hover:shadow-md transition duration-200">
                    <div class="p-4 md:p-6">
                        <h3 class="text-base md:text-lg font-medium text-gray-500 dark:text-gray-400">Allanamientos Positivos</h3>
                        <p class="mt-2 text-3xl md:text-4xl font-semibold text-green-600 dark:text-green-400">
                            {{ $totalPositivos }}
                        </p>
                        <p class="mt-2 text-xs md:text-sm text-gray-500 dark:text-gray-400">
                            Período: {{ ucfirst($periodoActual) }}
                        </p>
                    </div>
                </div>

            </div>

        </div>
    </div>
</x-app-layout>
