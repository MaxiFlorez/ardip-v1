<div class="p-4" @click.outside="if(window.innerWidth < 1024) sidebarOpen = false">
    <a href="{{ route('dashboard') }}" class="flex items-center justify-center mb-6">
        <span class="text-2xl font-bold text-white tracking-wide">ARDIP</span>
    </a>

    <nav class="flex flex-col space-y-1">
        @php
            $linkBase = 'group flex items-center gap-3 rounded px-3 py-2 text-sm font-medium transition-colors cursor-pointer';
        @endphp

        <!-- Lista de Procedimientos (Primero) -->
        @php $active = request()->routeIs('procedimientos.*'); @endphp
        <a href="{{ route('procedimientos.index') }}" wire:navigate
           class="{{ $linkBase }} {{ $active ? 'bg-gray-800 text-white border-l-4 border-blue-500' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
            <!-- Heroicon: Folder / Document Text -->
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-5 w-5">
                <path d="M2.25 6.75A2.25 2.25 0 0 1 4.5 4.5h4.94c.6 0 1.17.24 1.59.66l1.22 1.22c.42.42.99.66 1.59.66h3.66a2.25 2.25 0 0 1 2.25 2.25v8.25A2.25 2.25 0 0 1 17.25 20.25H6.75A2.25 2.25 0 0 1 4.5 18V6.75H2.25Z"/>
            </svg>
            <span>{{ __('Lista de Procedimientos') }}</span>
        </a>

        <!-- Nueva Carga -->
        @php $active = request()->routeIs('carga.create'); @endphp
        <a href="{{ route('carga.create') }}" wire:navigate
           class="{{ $linkBase }} {{ $active ? 'bg-gray-800 text-white border-l-4 border-blue-500' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
            <!-- Heroicon: Plus Circle -->
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-5 w-5">
                <path fill-rule="evenodd" d="M12 2.25a9.75 9.75 0 1 0 0 19.5 9.75 9.75 0 0 0 0-19.5Zm.75 5.25a.75.75 0 0 0-1.5 0v3.75H7.5a.75.75 0 0 0 0 1.5h3.75v3.75a.75.75 0 0 0 1.5 0V12.75H16.5a.75.75 0 0 0 0-1.5h-3.75V7.5Z" clip-rule="evenodd" />
            </svg>
            <span>{{ __('Nueva Carga') }}</span>
        </a>

        <!-- Vinculados (Personas) -->
        @php $active = request()->routeIs('personas.*'); @endphp
        <a href="{{ route('personas.index') }}" wire:navigate
           class="{{ $linkBase }} {{ $active ? 'bg-gray-800 text-white border-l-4 border-blue-500' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
            <!-- Heroicon: Users -->
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-5 w-5">
                <path d="M7.5 6a3 3 0 1 1 6 0 3 3 0 0 1-6 0ZM2.25 20.1a7.5 7.5 0 0 1 14.5 0 .9.9 0 0 1-.9.9H3.15a.9.9 0 0 1-.9-.9Zm14.19-7.6a3 3 0 1 0-1.69-5.78 5 5 0 0 1 0 5.78Zm1.31 8.5a.9.9 0 0 1-.9-.9 9 9 0 0 0-4.29-7.65 7.5 7.5 0 0 1 8.49 7.65.9.9 0 0 1-.9.9h-2.41Z"/>
            </svg>
            <span>{{ __('Vinculados') }}</span>
        </a>

        <!-- Localizaciones (Domicilios) -->
        @php $active = request()->routeIs('domicilios.*'); @endphp
        <a href="{{ route('domicilios.index') }}" wire:navigate
           class="{{ $linkBase }} {{ $active ? 'bg-gray-800 text-white border-l-4 border-blue-500' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
            <!-- Heroicon: Map Pin -->
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-5 w-5">
                <path fill-rule="evenodd" d="M12 2.25a7.5 7.5 0 0 0-7.5 7.5c0 5.123 5.4 10.107 6.97 11.5a.75.75 0 0 0 1.06 0c1.57-1.393 6.97-6.377 6.97-11.5a7.5 7.5 0 0 0-7.5-7.5Zm0 10.125a2.625 2.625 0 1 1 0-5.25 2.625 2.625 0 0 1 0 5.25Z" clip-rule="evenodd" />
            </svg>
            <span>{{ __('Localizaciones') }}</span>
        </a>
    </nav>
</div>