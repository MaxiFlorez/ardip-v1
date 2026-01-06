<nav x-data="{ open: false }" class="sticky top-0 z-50 bg-white border-b border-gray-100 shadow-sm">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            <div class="flex items-center gap-4">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="hover:opacity-75 transition-opacity duration-200">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                    </a>
                </div>

                <!-- Navigation Links - Desktop -->
                <div class="hidden md:flex md:space-x-1">
                    {{-- Dashboard: Solo admin (excluido super_admin puro) --}}
                    @can('admin')
                        @if(!Auth::user()->isSuperAdmin() || Auth::user()->roles()->count() > 1)
                            <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                                {{ __('Dashboard') }}
                            </x-nav-link>
                        @endif
                    @endcan

                    {{-- Procedimientos: Solo operativo (excluido super_admin puro) --}}
                    @can('acceso-operativo')
                        <x-nav-link :href="route('procedimientos.index')" :active="request()->routeIs('procedimientos.*')">
                            {{ __('Procedimientos') }}
                        </x-nav-link>
                    @endcan

                    {{-- Personas: Solo operativo (excluido super_admin puro) --}}
                    @can('acceso-operativo')
                        <x-nav-link :href="route('personas.index')" :active="request()->routeIs('personas.*')">
                            {{ __('Personas') }}
                        </x-nav-link>
                    @endcan

                    {{-- Documentos: Solo operativo (excluido super_admin puro) --}}
                    @can('acceso-operativo')
                        <x-nav-link :href="route('documentos.index')" :active="request()->routeIs('documentos.*')">
                            {{ __('Biblioteca Digital') }}
                        </x-nav-link>
                    @endcan

                    {{-- Gestión Usuarios: Solo super_admin --}}
                    @can('super-admin')
                        <x-nav-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.*')">
                            {{ __('Gestión Usuarios') }}
                        </x-nav-link>
                    @endcan

                    {{-- Brigadas: Solo super_admin --}}
                    @can('super-admin')
                        <x-nav-link :href="route('admin.brigadas.index')" :active="request()->routeIs('admin.brigadas.*')">
                            {{ __('Brigadas') }}
                        </x-nav-link>
                    @endcan

                    {{-- UFIs: Solo super_admin --}}
                    @can('super-admin')
                        <x-nav-link :href="route('admin.ufis.index')" :active="request()->routeIs('admin.ufis.*')">
                            {{ __('UFIs') }}
                        </x-nav-link>
                    @endcan
                </div>
            </div>

            <!-- Settings Dropdown - Desktop -->
            <div class="hidden md:flex md:items-center md:gap-4">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->name }}</div>
                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4 transition-transform duration-200" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>
                        <form method="POST" action="{{ route('logout') }}" id="logout-form-desktop">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault(); document.getElementById('logout-form-desktop').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger Menu - Mobile -->
            <div class="flex md:hidden items-center gap-2">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-600 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-600 transition duration-200 ease-in-out">
                    <svg class="h-6 w-6" :class="{'hidden': open, 'block': !open}" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                    <svg class="h-6 w-6" :class="{'block': open, 'hidden': !open}" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu - Mobile -->
    <div :class="{'block': open, 'hidden': !open}" class="hidden md:hidden bg-white border-t border-gray-100 transition-all duration-300 ease-in-out" x-show="open" @click.away="open = false">
        <div class="px-2 pt-2 pb-3 space-y-1">
            {{-- Dashboard: Solo admin (excluido super_admin puro) --}}
            @can('admin')
                @if(!Auth::user()->isSuperAdmin() || Auth::user()->roles()->count() > 1)
                    <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-responsive-nav-link>
                @endif
            @endcan

            {{-- Procedimientos: Solo operativo (excluido super_admin puro) --}}
            @can('acceso-operativo')
                <x-responsive-nav-link :href="route('procedimientos.index')" :active="request()->routeIs('procedimientos.*')">
                    {{ __('Procedimientos') }}
                </x-responsive-nav-link>
            @endcan

            {{-- Personas: Solo operativo (excluido super_admin puro) --}}
            @can('acceso-operativo')
                <x-responsive-nav-link :href="route('personas.index')" :active="request()->routeIs('personas.*')">
                    {{ __('Personas') }}
                </x-responsive-nav-link>
            @endcan

            {{-- Documentos: Solo operativo (excluido super_admin puro) --}}
            @can('acceso-operativo')
                <x-responsive-nav-link :href="route('documentos.index')" :active="request()->routeIs('documentos.*')">
                    {{ __('Biblioteca Digital') }}
                </x-responsive-nav-link>
            @endcan

            {{-- Gestión Usuarios: Solo super_admin --}}
            @can('super-admin')
                <x-responsive-nav-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.*')">
                    {{ __('Gestión Usuarios') }}
                </x-responsive-nav-link>
            @endcan

            {{-- Brigadas: Solo super_admin --}}
            @can('super-admin')
                <x-responsive-nav-link :href="route('admin.brigadas.index')" :active="request()->routeIs('admin.brigadas.*')">
                    {{ __('Brigadas') }}
                </x-responsive-nav-link>
            @endcan

            {{-- UFIs: Solo super_admin --}}
            @can('super-admin')
                <x-responsive-nav-link :href="route('admin.ufis.index')" :active="request()->routeIs('admin.ufis.*')">
                    {{ __('UFIs') }}
                </x-responsive-nav-link>
            @endcan
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-3 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500 truncate">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <form method="POST" action="{{ route('logout') }}" id="logout-form-mobile">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault(); document.getElementById('logout-form-mobile').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
