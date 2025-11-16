<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased" x-data="{ 
        sidebarOpen: window.innerWidth >= 1024,
        init() {
            this.$watch('sidebarOpen', (newVal) => {
                localStorage.setItem('sidebar-open', newVal);
            });
            const saved = localStorage.getItem('sidebar-open');
            if (saved !== null) {
                this.sidebarOpen = saved === 'true';
            }
        }
    }" @window:resize.debounce="if(window.innerWidth < 1024) sidebarOpen = false">
        <!-- Top Navigation; left offset responds to sidebar state -->
        @include('layouts.navigation')

        <div class="min-h-screen flex bg-gray-100">
            <!-- Overlay for small screens when sidebar open -->
            <div x-show="sidebarOpen" class="fixed inset-0 bg-black/50 z-30 lg:hidden transition-opacity duration-300 ease-in-out" @click="sidebarOpen = false" x-cloak></div>

            <!-- Collapsible Sidebar -->
            <aside class="w-64 bg-gray-900 text-white shadow-lg fixed inset-y-0 z-40 transform transition-transform duration-300 ease-in-out"
                   :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">
                @include('layouts.sidebar')
            </aside>

            <!-- Main content; left margin responds to sidebar state on large screens -->
            <div class="flex-1 flex flex-col pt-16 transition-all duration-300 ease-in-out" :class="sidebarOpen ? 'lg:ml-64' : 'ml-0'">
                @isset($header)
                    <header class="bg-white border-b border-gray-100">
                        <div class="py-6 px-6 lg:px-8">
                            {{ $header }}
                        </div>
                    </header>
                @endisset

                <main class="flex-1 p-6 pb-28">
                    {{ $slot }}
                </main>

                <footer class="fixed bottom-0 left-0 right-0 bg-gray-800 text-gray-100 border-t border-gray-700 py-2 px-4 text-sm text-center z-[1000] transition-all duration-300 ease-in-out"
                    :class="sidebarOpen ? 'lg:ml-64' : 'ml-0'">
                    © 2025 - Flores, Maximiliano - Sistema ARDIP V1
                </footer>
            </div>
        </div>
        
    </body>
</html>