<nav x-data="{ open: false }" class="sticky top-0 z-50 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 shadow-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            
            <!-- Logo y Navegación Izquierda -->
            <div class="flex items-center gap-8">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('home') }}" class="hover:opacity-80 transition-opacity duration-200">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800 dark:text-gray-100" />
                    </a>
                </div>

                <!-- Navegación Desktop - Links Principales -->
                <div class="hidden md:flex md:items-center md:gap-1">
                    
                    <!-- Dashboard (📊) - Solo admin-dashboard -->
                    @can('admin-dashboard')
                        <a href="{{ route('dashboard') }}" 
                           class="px-3 py-2 rounded-lg text-sm font-medium transition-all duration-200 flex items-center gap-2
                                  {{ request()->routeIs('dashboard') 
                                     ? 'bg-indigo-100 dark:bg-indigo-900/50 text-indigo-700 dark:text-indigo-200' 
                                     : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                            <span>Dashboard</span>
                        </a>
                    @endcan

                    <!-- Divisor Visual -->
                    @canany('admin-dashboard', 'acceso-operativo', 'super-admin')
                        @if (Auth::user()->can('admin-dashboard') && Auth::user()->can('super-admin'))
                            <div class="hidden lg:block w-px h-6 bg-gray-300 dark:bg-gray-600 mx-1"></div>
                        @endif
                    @endcanany

                    <!-- Procedimientos (📋) - Solo operativo -->
                    @can('acceso-operativo')
                        <a href="{{ route('procedimientos.index') }}" 
                           class="px-3 py-2 rounded-lg text-sm font-medium transition-all duration-200 flex items-center gap-2
                                  {{ request()->routeIs('procedimientos.*') 
                                     ? 'bg-blue-100 dark:bg-blue-900/50 text-blue-700 dark:text-blue-200' 
                                     : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <span>Procedimientos</span>
                        </a>
                    @endcan

                    <!-- Personas (👥) - Solo operativo -->
                    @can('acceso-operativo')
                        <a href="{{ route('personas.index') }}" 
                           class="px-3 py-2 rounded-lg text-sm font-medium transition-all duration-200 flex items-center gap-2
                                  {{ request()->routeIs('personas.*') 
                                     ? 'bg-green-100 dark:bg-green-900/50 text-green-700 dark:text-green-200' 
                                     : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 12H9m6 0a6 6 0 11-12 0 6 6 0 0112 0z"></path>
                            </svg>
                            <span>Personas</span>
                        </a>
                    @endcan

                    <!-- Documentos (📚) - Solo operativo -->
                    @can('acceso-operativo')
                        <a href="{{ route('documentos.index') }}" 
                           class="px-3 py-2 rounded-lg text-sm font-medium transition-all duration-200 flex items-center gap-2
                                  {{ request()->routeIs('documentos.*') 
                                     ? 'bg-purple-100 dark:bg-purple-900/50 text-purple-700 dark:text-purple-200' 
                                     : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C6.5 6.253 2 10.998 2 17s4.5 10.747 10 10.747c5.5 0 10-4.998 10-10.747S17.5 6.253 12 6.253z"></path>
                            </svg>
                            <span>Biblioteca</span>
                        </a>
                    @endcan

                    <!-- Divisor Visual -->
                    @can('super-admin')
                        <div class="hidden lg:block w-px h-6 bg-gray-300 dark:bg-gray-600 mx-1"></div>
                    @endcan

                    <!-- Gestión Usuarios (⚙️) - Solo super-admin -->
                    @can('super-admin')
                        <a href="{{ route('admin.users.index') }}" 
                           class="px-3 py-2 rounded-lg text-sm font-medium transition-all duration-200 flex items-center gap-2
                                  {{ request()->routeIs('admin.users.*') 
                                     ? 'bg-orange-100 dark:bg-orange-900/50 text-orange-700 dark:text-orange-200' 
                                     : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path>
                            </svg>
                            <span>Usuarios</span>
                        </a>
                    @endcan

                    <!-- Brigadas (🛡️) - Solo super-admin -->
                    @can('super-admin')
                        <a href="{{ route('admin.brigadas.index') }}" 
                           class="px-3 py-2 rounded-lg text-sm font-medium transition-all duration-200 flex items-center gap-2
                                  {{ request()->routeIs('admin.brigadas.*') 
                                     ? 'bg-red-100 dark:bg-red-900/50 text-red-700 dark:text-red-200' 
                                     : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m7 0a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span>Brigadas</span>
                        </a>
                    @endcan

                    <!-- UFIs (🏛️) - Solo super-admin -->
                    @can('super-admin')
                        <a href="{{ route('admin.ufis.index') }}" 
                           class="px-3 py-2 rounded-lg text-sm font-medium transition-all duration-200 flex items-center gap-2
                                  {{ request()->routeIs('admin.ufis.*') 
                                     ? 'bg-cyan-100 dark:bg-cyan-900/50 text-cyan-700 dark:text-cyan-200' 
                                     : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                            <span>UFIs</span>
                        </a>
                    @endcan
                </div>
            </div>

            <!-- Derecha: Perfil y Hamburguesa -->
            <div class="flex items-center gap-4">
                
                <!-- Dropdown Perfil - Desktop -->
                <div class="hidden md:flex md:items-center">
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center px-3 py-2 border border-gray-300 dark:border-gray-600 text-sm leading-4 font-medium rounded-lg text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                <div class="truncate">{{ Auth::user()->name }}</div>
                                <div class="ms-1">
                                    <svg class="fill-current h-4 w-4 transition-transform duration-200" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <x-dropdown-link :href="route('profile.edit')" class="flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                {{ __('Perfil') }}
                            </x-dropdown-link>
                            
                            <div class="border-t border-gray-200 dark:border-gray-600"></div>
                            
                            <form method="POST" action="{{ route('logout') }}" id="logout-form-desktop">
                                @csrf
                                <x-dropdown-link :href="route('logout')"
                                        onclick="event.preventDefault(); document.getElementById('logout-form-desktop').submit();"
                                        class="flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                    </svg>
                                    {{ __('Cerrar Sesión') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                </div>

                <!-- Hamburguesa - Mobile -->
                <button @click="open = !open" class="md:hidden inline-flex items-center justify-center p-2 rounded-md text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-700 transition duration-200 ease-in-out">
                    <svg class="h-6 w-6" :class="{'hidden': open, 'block': !open}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                    <svg class="h-6 w-6" :class="{'block': open, 'hidden': !open}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Menú Móvil -->
    <div :class="{'block': open, 'hidden': !open}" 
         class="hidden md:hidden bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700 transition-all duration-300 ease-in-out"
         x-show="open" 
         @click.away="open = false">
        
        <div class="px-2 pt-2 pb-3 space-y-1">
            
            <!-- Dashboard Móvil -->
            @can('admin-dashboard')
                <a href="{{ route('dashboard') }}"
                   @click="open = false"
                   class="block px-3 py-2 rounded-lg text-base font-medium transition-all duration-200 flex items-center gap-2
                          {{ request()->routeIs('dashboard') 
                             ? 'bg-indigo-100 dark:bg-indigo-900/50 text-indigo-700 dark:text-indigo-200' 
                             : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                    Dashboard
                </a>
            @endcan

            <!-- Procedimientos Móvil -->
            @can('acceso-operativo')
                <a href="{{ route('procedimientos.index') }}"
                   @click="open = false"
                   class="block px-3 py-2 rounded-lg text-base font-medium transition-all duration-200 flex items-center gap-2
                          {{ request()->routeIs('procedimientos.*') 
                             ? 'bg-blue-100 dark:bg-blue-900/50 text-blue-700 dark:text-blue-200' 
                             : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Procedimientos
                </a>
            @endcan

            <!-- Personas Móvil -->
            @can('acceso-operativo')
                <a href="{{ route('personas.index') }}"
                   @click="open = false"
                   class="block px-3 py-2 rounded-lg text-base font-medium transition-all duration-200 flex items-center gap-2
                          {{ request()->routeIs('personas.*') 
                             ? 'bg-green-100 dark:bg-green-900/50 text-green-700 dark:text-green-200' 
                             : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 12H9m6 0a6 6 0 11-12 0 6 6 0 0112 0z"></path>
                    </svg>
                    Personas
                </a>
            @endcan

            <!-- Documentos Móvil -->
            @can('acceso-operativo')
                <a href="{{ route('documentos.index') }}"
                   @click="open = false"
                   class="block px-3 py-2 rounded-lg text-base font-medium transition-all duration-200 flex items-center gap-2
                          {{ request()->routeIs('documentos.*') 
                             ? 'bg-purple-100 dark:bg-purple-900/50 text-purple-700 dark:text-purple-200' 
                             : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C6.5 6.253 2 10.998 2 17s4.5 10.747 10 10.747c5.5 0 10-4.998 10-10.747S17.5 6.253 12 6.253z"></path>
                    </svg>
                    Biblioteca Digital
                </a>
            @endcan

            <!-- Separador si hay admin -->
            @can('super-admin')
                <div class="my-2 h-px bg-gray-200 dark:bg-gray-700"></div>
            @endcan

            <!-- Usuarios Móvil -->
            @can('super-admin')
                <a href="{{ route('admin.users.index') }}"
                   @click="open = false"
                   class="block px-3 py-2 rounded-lg text-base font-medium transition-all duration-200 flex items-center gap-2
                          {{ request()->routeIs('admin.users.*') 
                             ? 'bg-orange-100 dark:bg-orange-900/50 text-orange-700 dark:text-orange-200' 
                             : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path>
                    </svg>
                    Gestión Usuarios
                </a>
            @endcan

            <!-- Brigadas Móvil -->
            @can('super-admin')
                <a href="{{ route('admin.brigadas.index') }}"
                   @click="open = false"
                   class="block px-3 py-2 rounded-lg text-base font-medium transition-all duration-200 flex items-center gap-2
                          {{ request()->routeIs('admin.brigadas.*') 
                             ? 'bg-red-100 dark:bg-red-900/50 text-red-700 dark:text-red-200' 
                             : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m7 0a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Brigadas
                </a>
            @endcan

            <!-- UFIs Móvil -->
            @can('super-admin')
                <a href="{{ route('admin.ufis.index') }}"
                   @click="open = false"
                   class="block px-3 py-2 rounded-lg text-base font-medium transition-all duration-200 flex items-center gap-2
                          {{ request()->routeIs('admin.ufis.*') 
                             ? 'bg-cyan-100 dark:bg-cyan-900/50 text-cyan-700 dark:text-cyan-200' 
                             : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                    UFIs
                </a>
            @endcan
        </div>

        <!-- Perfil Móvil -->
        <div class="border-t border-gray-200 dark:border-gray-700 px-4 py-4">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 rounded-full bg-indigo-600 flex items-center justify-center">
                    <span class="text-white font-bold text-sm">{{ substr(Auth::user()->name, 0, 1) }}</span>
                </div>
                <div class="min-w-0">
                    <p class="text-sm font-semibold text-gray-900 dark:text-white truncate">{{ Auth::user()->name }}</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400 truncate">{{ Auth::user()->email }}</p>
                </div>
            </div>

            <div class="space-y-1">
                <a href="{{ route('profile.edit') }}"
                   @click="open = false"
                   class="block w-full text-left px-3 py-2 rounded-lg text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    Perfil
                </a>

                <form method="POST" action="{{ route('logout') }}" id="logout-form-mobile">
                    @csrf
                    <a href="{{ route('logout') }}"
                       onclick="event.preventDefault(); document.getElementById('logout-form-mobile').submit();"
                       @click="open = false"
                       class="block w-full text-left px-3 py-2 rounded-lg text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                        </svg>
                        Cerrar Sesión
                    </a>
                </form>
            </div>
        </div>
    </div>
</nav>
