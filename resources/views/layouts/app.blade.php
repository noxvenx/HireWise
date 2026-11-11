<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'Dashboard') - HireWise</title>

    <style>
    .sidebar-nav {
        scrollbar-width: none;
        -ms-overflow-style: none;
    }

    .sidebar-nav::-webkit-scrollbar {
        display: none;
    }
    </style>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-slate-50 text-slate-800 antialiased">

    <div class="min-h-screen md:flex">

        {{-- Mobile Sidebar Overlay --}}
        <button
            id="sidebar-overlay"
            type="button"
            aria-label="Tutup navigasi"
            class="fixed inset-0 z-40 hidden bg-slate-950/50 backdrop-blur-sm md:hidden">
        </button>

        {{-- Sidebar --}}
        @include('partials.sidebar')

        {{-- Main Content --}}
        <div class="min-w-0 flex-1">

            @include('partials.header')

            <main class="p-4 sm:p-6 lg:p-8">

                {{-- Success Notification --}}
                @if(session('success'))
                    <div class="mb-6 flex items-start gap-3 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700 shadow-sm">
                        <span class="font-bold">✓</span>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                {{-- Error Notification --}}
                @if(session('error'))
                    <div class="mb-6 flex items-start gap-3 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700 shadow-sm">
                        <span class="font-bold">!</span>
                        <span>{{ session('error') }}</span>
                    </div>
                @endif

                @yield('content')

            </main>
        </div>
    </div>

    {{-- Global Delete Confirmation Modal --}}
    @include('partials.delete-modal')

    {{-- Mobile Sidebar Script --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const sidebar = document.getElementById('app-sidebar');
            const overlay = document.getElementById('sidebar-overlay');
            const openButton = document.getElementById('sidebar-toggle');
            const closeButton = document.getElementById('sidebar-close');

            if (!sidebar || !overlay || !openButton) return;

            function openSidebar() {
                sidebar.classList.remove('-translate-x-full');
                overlay.classList.remove('hidden');
                document.body.classList.add('overflow-hidden');
                openButton.setAttribute('aria-expanded', 'true');
            }

            function closeSidebar() {
                sidebar.classList.add('-translate-x-full');
                overlay.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
                openButton.setAttribute('aria-expanded', 'false');
            }

            openButton.addEventListener('click', openSidebar);
            overlay.addEventListener('click', closeSidebar);

            if (closeButton) {
                closeButton.addEventListener('click', closeSidebar);
            }

            sidebar.querySelectorAll('[data-sidebar-link]').forEach(function (link) {
                link.addEventListener('click', function () {
                    if (window.innerWidth < 768) closeSidebar();
                });
            });

            window.addEventListener('resize', function () {
                if (window.innerWidth >= 768) {
                    closeSidebar();
                }
            });

            document.addEventListener('keydown', function (event) {
                if (event.key === 'Escape') closeSidebar();
            });
        });
    </script>

</body>
</html>