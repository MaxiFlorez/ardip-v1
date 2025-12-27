<aside class="w-64 bg-white border-r border-gray-200 min-h-screen">
    <div class="p-4">
        <h3 class="text-xs font-semibold text-gray-500 uppercase">Secciones</h3>
    </div>

    <!-- Sección General (panel-consulta) -->
    @can('panel-consulta')
        <div class="px-4 mt-2">
            <h4 class="text-xs text-gray-400 uppercase">General</h4>
            <ul class="mt-2 space-y-1">
                <li>
                    <a href="{{ url('/procedimientos') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Lista de Procedimientos</a>
                </li>
                <li>
                    <a href="{{ url('/localizaciones') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Localizaciones</a>
                </li>
                <li class="{{ request()->routeIs('documentos.*') ? 'bg-blue-800 border-l-4 border-blue-300' : 'hover:bg-gray-700' }}">
                    <a href="{{ route('documentos.index') }}" class="flex items-center p-3 space-x-3 rounded-md">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 19a2 2 0 01-2-2V7a2 2 0 012-2h4l2 2h4a2 2 0 012 2v1M5 19h14a2 2 0 002-2v-5a2 2 0 00-2-2H9a2 2 0 00-2 2v5a2 2 0 01-2 2z" />
                        </svg>
                        <span class="text-gray-100">Biblioteca Digital</span>
                    </a>
                </li>
            </ul>
        </div>
    @endcan

    <!-- Sección Operativa (panel-carga) -->
    @can('panel-carga')
        <div class="px-4 mt-4">
            <div class="px-4 mt-4 text-xs text-gray-400 uppercase">Operaciones</div>
            <ul class="mt-2 space-y-1">
                <li>
                    <a href="{{ url('/procedimientos/create') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Nueva Carga</a>
                </li>
                <li>
                    <a href="{{ url('/vinculados') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Vinculados</a>
                </li>
            </ul>
        </div>
    @endcan

    <!-- Sección Administración (gestion-usuarios) -->
    @can('gestion-usuarios')
        <div class="px-4 mt-6">
            <div class="px-4 text-xs text-gray-400 uppercase">Administración</div>
            <ul class="mt-2 space-y-1">
                <li>
                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Gestión de Usuarios</a>
                </li>
                <li>
                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Métricas Globales</a>
                </li>
            </ul>
        </div>
    @endcan
</aside>
