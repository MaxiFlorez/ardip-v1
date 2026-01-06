<footer class="bg-gray-50 border-t border-gray-200 mt-auto">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="py-6 flex flex-col sm:flex-row justify-between items-center gap-4 text-sm text-gray-600">
            <!-- Izquierda -->
            <div class="flex items-center gap-3">
                <span class="font-semibold text-gray-900">ARDIP</span>
                <span class="text-xs text-gray-400">Sistema de Investigaciones</span>
            </div>

            <!-- Centro -->
            <div class="text-xs text-gray-500">
                © {{ date('Y') }} Todos los derechos reservados
            </div>

            <!-- Derecha: Enlaces -->
            <div class="flex items-center gap-4">
                <a href="mailto:soporte@ardip.local" 
                   class="inline-flex items-center gap-1 text-primary-600 hover:text-primary-700 text-sm font-medium">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span>Soporte</span>
                </a>
            </div>
        </div>
    </div>
</footer>
