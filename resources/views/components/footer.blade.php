<footer class="bg-gray-900 text-gray-300 border-t border-gray-800 mt-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="py-8 flex flex-col sm:flex-row justify-between items-center gap-6">
            <!-- Izquierda: Branding -->
            <div class="flex items-center gap-2">
                <div class="text-xl font-bold text-white">ARDIP</div>
                <span class="text-xs text-gray-400">v1.0</span>
            </div>

            <!-- Centro: Info -->
            <div class="text-center text-xs text-gray-400 space-y-1">
                <div>© {{ date('Y') }} Sistema de Investigaciones Policiales</div>
                <div class="text-gray-500">Todos los derechos reservados</div>
            </div>

            <!-- Derecha: Soporte -->
            <div class="flex items-center gap-4">
                <a href="mailto:soporte@ardip.local" 
                   class="inline-flex items-center gap-2 px-3 py-2 bg-gray-800 hover:bg-gray-700 rounded-lg transition-colors text-xs font-medium group">
                    <svg class="w-4 h-4 group-hover:text-blue-400 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                    <span>Soporte</span>
                </a>
                <a href="#" 
                   class="inline-flex items-center gap-1 text-gray-400 hover:text-gray-200 transition-colors text-xs">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 5v8a2 2 0 01-2 2h-5l-5 4v-4H4a2 2 0 01-2-2V5a2 2 0 012-2h12a2 2 0 012 2zm-11-1a1 1 0 11-2 0 1 1 0 012 0z" clip-rule="evenodd"></path>
                    </svg>
                    <span>Ayuda</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Línea de estado -->
    <div class="bg-gray-950 border-t border-gray-800 py-2">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center text-xs text-gray-500">
                <span>✓ Sistema operativo</span>
                <span>{{ now()->format('d/m/Y H:i') }}</span>
            </div>
        </div>
    </div>
</footer>
