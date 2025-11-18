<nav class="fixed top-0 left-0 right-0 z-[60] bg-gray-900 text-gray-100 lg:ml-64">
    <div class="px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center gap-3">
                <!-- Hamburger (mobile only) -->
                <button id="hamburger-btn" class="lg:hidden inline-flex items-center justify-center p-2 rounded-md text-gray-100 hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-500" aria-label="Abrir menú" aria-controls="app-sidebar" aria-expanded="false">
                    <!-- icon: three lines -->
                    <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M3 6h18M3 12h18M3 18h18" />
                    </svg>
                </button>

                <!-- Logo / Título -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="text-gray-100 flex items-center gap-2">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-100" />
                        <span class="hidden sm:inline text-sm text-gray-300">ARDIP</span>
                    </a>
                </div>
            </div>

            <!-- Enlaces directos: Perfil / Salir -->
            <div class="flex items-center gap-4">
                <div class="hidden sm:flex flex-col text-right">
                    <span class="text-gray-100 text-sm leading-4">{{ Auth::user()->name }}</span>
                    <span class="text-xs text-gray-400">{{ Auth::user()->brigada->nombre ?? 'Sin Brigada Asignada' }}</span>
                </div>

                <a href="{{ route('profile.edit') }}" class="px-3 py-2 text-sm font-medium rounded-md text-gray-100 hover:bg-gray-800">{{ __('Perfil') }}</a>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="px-3 py-2 text-sm font-medium rounded-md text-gray-100 hover:bg-gray-800">{{ __('Salir') }}</button>
                </form>
            </div>
        </div>
    </div>
</nav>
