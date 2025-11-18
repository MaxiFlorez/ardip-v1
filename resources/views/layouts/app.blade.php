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
    <body class="font-sans antialiased">
        <!-- Top Navigation with fixed bar and hamburger for mobile -->
        @include('layouts.navigation')

        <!-- Mobile overlay (appears when sidebar is open) -->
        <div id="app-overlay" class="fixed inset-0 bg-black bg-opacity-50 z-40 hidden lg:hidden" aria-hidden="true"></div>

        <div class="min-h-screen flex bg-gray-100">
            <!-- Responsive Sidebar -->
            <aside id="app-sidebar" class="fixed inset-y-0 left-0 w-64 bg-gray-900 text-white shadow-lg z-50 transform -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-in-out overflow-y-auto max-h-screen pt-16 lg:pt-16" aria-hidden="true">
                @include('layouts.sidebar')
            </aside>

            <!-- Main content -->
            <div class="flex-1 flex flex-col pt-16 lg:ml-64 min-w-0">
                @isset($header)
                    <header class="bg-white border-b border-gray-100">
                        <div class="py-4 px-4 md:py-6 md:px-6 lg:px-8">
                            {{ $header }}
                        </div>
                    </header>
                @endisset

                <main class="flex-1 p-4 md:p-6 pb-24 md:pb-28">
                    {{ $slot }}
                </main>

                <footer class="bg-gray-800 text-gray-100 border-t border-gray-700 py-3 px-4 text-sm flex flex-col md:flex-row items-center justify-center gap-2 text-center">
                    <span>© 2025 - Flores, Maximiliano - Sistema ARDIP V1</span>
                </footer>
            </div>
        </div>

        <script>
        (function(){
            const sidebar = document.getElementById('app-sidebar');
            const overlay = document.getElementById('app-overlay');
            const body = document.body;
            const burger = document.getElementById('hamburger-btn');
            const closeBtn = document.getElementById('sidebar-close-btn');

            function openSidebar(){
                sidebar.classList.remove('-translate-x-full');
                overlay.classList.remove('hidden');
                body.classList.add('overflow-hidden');
                sidebar.setAttribute('aria-hidden','false');
            }
            function closeSidebar(){
                sidebar.classList.add('-translate-x-full');
                overlay.classList.add('hidden');
                body.classList.remove('overflow-hidden');
                sidebar.setAttribute('aria-hidden','true');
            }
            function isMobile(){ return window.innerWidth < 1024; }

            // Toggle with hamburger
            if (burger){ burger.addEventListener('click', (e)=>{ e.preventDefault(); openSidebar(); }); }
            // Close button inside sidebar (mobile only)
            if (closeBtn){ closeBtn.addEventListener('click', (e)=>{ e.preventDefault(); closeSidebar(); }); }
            // Overlay click closes
            if (overlay){ overlay.addEventListener('click', closeSidebar); }
            // Close on link click in mobile
            sidebar?.querySelectorAll('a').forEach(a => {
                a.addEventListener('click', () => { if (isMobile()) closeSidebar(); });
            });
            // Resize handler
            window.addEventListener('resize', () => {
                if (!isMobile()){
                    // Desktop: keep sidebar visible, hide overlay
                    sidebar.classList.remove('-translate-x-full');
                    overlay.classList.add('hidden');
                    body.classList.remove('overflow-hidden');
                } else {
                    // Mobile: ensure hidden by default
                    sidebar.classList.add('-translate-x-full');
                    overlay.classList.add('hidden');
                    body.classList.remove('overflow-hidden');
                }
            });
        })();
        </script>
        @stack('scripts')
    </body>
</html>