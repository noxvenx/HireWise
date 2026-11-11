<header class="sticky top-0 z-30 border-b border-slate-200/80 bg-white/95 backdrop-blur-md">

    <div class="flex min-h-[76px] items-center justify-between gap-4 px-4 sm:px-6 lg:px-8">

        {{-- Left: Mobile Menu + Page Title --}}
        <div class="flex min-w-0 items-center gap-3">

            <button
                id="sidebar-toggle"
                type="button"
                aria-label="Buka navigasi"
                aria-controls="app-sidebar"
                aria-expanded="false"
                class="inline-flex h-10 w-10 shrink-0 items-center justify-center rounded-xl border border-slate-200 text-slate-600 transition hover:bg-slate-100 md:hidden">

                <svg class="h-5 w-5" fill="none" stroke="currentColor"
                     viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>

            <div class="min-w-0">
                <h2 class="truncate text-lg font-bold tracking-tight text-slate-800 sm:text-xl">
                    @yield('page-title', 'Dashboard')
                </h2>

                <p class="mt-0.5 hidden text-xs text-slate-500 sm:block">
                    HireWise Recruitment Management System
                </p>
            </div>
        </div>

        {{-- Right: User Information --}}
        <div class="flex shrink-0 items-center gap-3 sm:gap-4">

            <div class="hidden h-9 w-9 items-center justify-center rounded-full bg-blue-100 text-sm font-bold text-blue-700 sm:flex">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </div>

            <div class="hidden text-right sm:block">
                <p class="max-w-[160px] truncate text-sm font-semibold text-slate-800">
                    {{ auth()->user()->name }}
                </p>

                <span class="mt-0.5 inline-flex rounded-full bg-blue-50 px-2 py-0.5 text-[10px] font-semibold text-blue-700">
                    {{ auth()->user()->isSuperAdmin() ? 'SUPER ADMIN' : 'ADMIN HRD' }}
                </span>
            </div>

            <div class="h-8 w-px bg-slate-200"></div>

            <form method="POST" action="{{ route('logout') }}">
                @csrf

                <button
                    type="submit"
                    class="inline-flex items-center gap-2 rounded-xl border border-red-100 bg-red-50 px-3 py-2 text-xs font-semibold text-red-600 transition hover:border-red-200 hover:bg-red-100 sm:px-4 sm:text-sm">

                    <svg class="h-4 w-4" fill="none" stroke="currentColor"
                         viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4M10 17l5-5-5-5m5 5H3"/>
                    </svg>

                    <span>Logout</span>
                </button>
            </form>

        </div>

    </div>
</header>
