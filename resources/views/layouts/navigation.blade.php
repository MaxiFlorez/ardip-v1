<nav class="fixed top-0 left-0 right-0 z-40 bg-gray-900 text-gray-100 transition-all duration-300 ease-in-out" :class="sidebarOpen ? 'lg:ml-64' : 'ml-0'">
    <!-- Top Navigation Fixed aligned to sidebar -->
    <div class="px-4 sm:px-6 lg:px-8" x-data="{ open: false }">
        <div class="flex justify-between h-16">
            <div class="flex items-center gap-2">
                <!-- Sidebar Toggle (Hamburger) -->
                <button @click="sidebarOpen = !sidebarOpen" class="inline-flex items-center justify-center p-2 rounded-md text-gray-200 hover:text-white hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M3 6h18M3 12h18M3 18h18" />
                    </svg>
                </button>

                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="text-gray-100">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-100" />
                    </a>
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-200 bg-gray-900 hover:bg-gray-800 hover:text-white focus:outline-none transition ease-in-out duration-150">
                            <div class="text-right">
                                <div class="text-gray-100">{{ Auth::user()->name }}</div>
                                <div class="text-xs text-gray-400">{{ Auth::user()->brigada->nombre ?? 'Sin Brigada Asignada' }}</div>
                            </div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4 text-gray-200" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <div class="px-4 py-2 border-b border-gray-700 bg-gray-900">
                            <div class="text-sm text-gray-200">Bienvenido, <span class="font-medium text-white">{{ Auth::user()->name }}</span></div>
                            <div class="text-xs text-gray-400 mt-1">{{ Auth::user()->brigada->nombre ?? 'Sin Brigada Asignada' }}</div>
                        </div>
                        <x-dropdown-link class="text-gray-900 hover:bg-gray-100 hover:text-gray-900" :href="route('profile.edit')">
                            {{ __('Perfil') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link class="text-gray-900 hover:bg-gray-100 hover:text-gray-900" :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Salir') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-gray-900 text-gray-100">
        <!-- Responsive Settings Options (solo usuario) -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-100">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-300">{{ Auth::user()->email }}</div>
                <div class="font-medium text-sm text-blue-400 mt-1">{{ Auth::user()->brigada->nombre ?? 'Sin Brigada Asignada' }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link class="text-white hover:bg-gray-800 hover:text-white" :href="route('profile.edit')">
                    {{ __('Perfil') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link class="text-white hover:bg-gray-800 hover:text-white" :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Salir') }}
                    </x-responsive-nav-link>
                </form>
                <div class="px-4 pt-2"></div>
            </div>
        </div>
    </div>
</nav>
