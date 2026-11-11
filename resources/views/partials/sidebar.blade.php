<aside
    id="app-sidebar"
    class="fixed inset-y-0 left-0 z-50 flex h-screen w-72 shrink-0 -translate-x-full flex-col border-r border-slate-800 bg-slate-950 text-white shadow-2xl transition-transform duration-300 md:sticky md:top-0 md:z-20 md:w-64 md:translate-x-0 md:shadow-none">

    {{-- Logo Area --}}
    <div class="flex h-[76px] shrink-0 items-center justify-between border-b border-slate-800 px-5">

        <a href="{{ route('dashboard') }}" class="flex items-center gap-3">
            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-600 text-xl font-extrabold text-white shadow-lg shadow-blue-900/30">
                H
            </div>

            <div>
                <h1 class="text-lg font-bold tracking-wide text-white">
                    HireWise
                </h1>
                <p class="text-[10px] font-medium tracking-wider text-slate-400">
                    RECRUITMENT SYSTEM
                </p>
            </div>
        </a>

        {{-- Close Button: Mobile --}}
        <button
            id="sidebar-close"
            type="button"
            aria-label="Tutup navigasi"
            class="flex h-9 w-9 items-center justify-center rounded-lg text-slate-400 transition hover:bg-slate-800 hover:text-white md:hidden">

            <svg class="h-5 w-5" fill="none" stroke="currentColor"
                 viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M6 18 18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>

    {{-- Navigation --}}
    <nav class="sidebar-nav flex-1 space-y-6 overflow-y-auto px-4 py-6">

        {{-- Main Menu --}}
        <div>
            <p class="mb-2 px-3 text-[10px] font-bold uppercase tracking-[0.16em] text-slate-500">
                Main Menu
            </p>

            <a href="{{ route('dashboard') }}"
               data-sidebar-link
               class="group flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition
               {{ request()->routeIs('dashboard') ? 'bg-blue-600 text-white shadow-lg shadow-blue-950/30' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">

                <svg class="h-5 w-5 shrink-0 {{ request()->routeIs('dashboard') ? 'text-white' : 'text-slate-500 group-hover:text-white' }}"
                     fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M3 10.5 12 3l9 7.5V21h-6v-6H9v6H3z"/>
                </svg>

                Dashboard
            </a>
        </div>

        {{-- Recruitment --}}
        <div>
            <p class="mb-2 px-3 text-[10px] font-bold uppercase tracking-[0.16em] text-slate-500">
                Recruitment
            </p>

            <div class="space-y-1">

                <a href="{{ route('recruitment-batches.index') }}"
                   data-sidebar-link
                   class="group flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition
                   {{ request()->routeIs('recruitment-batches.*') ? 'bg-blue-600 text-white' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">

                    <svg class="h-5 w-5 shrink-0 {{ request()->routeIs('recruitment-batches.*') ? 'text-white' : 'text-slate-500 group-hover:text-white' }}"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                        <rect x="3" y="5" width="18" height="16" rx="2"/>
                        <path stroke-linecap="round" d="M7 3v4m10-4v4M3 10h18"/>
                    </svg>

                    Recruitment Batch
                </a>

                <a href="{{ route('candidates.index') }}"
                   data-sidebar-link
                   class="group flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition
                   {{ request()->routeIs('candidates.*') ? 'bg-blue-600 text-white' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">

                    <svg class="h-5 w-5 shrink-0 {{ request()->routeIs('candidates.*') ? 'text-white' : 'text-slate-500 group-hover:text-white' }}"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                        <circle cx="12" cy="8" r="4"/>
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M4 21v-2a8 8 0 0 1 16 0v2"/>
                    </svg>

                    Candidates
                </a>

                <a href="{{ route('administrative-screening.index') }}"
                   data-sidebar-link
                   class="group flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition
                   {{ request()->routeIs('administrative-screening.*') ? 'bg-blue-600 text-white' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">

                    <svg class="h-5 w-5 shrink-0 {{ request()->routeIs('administrative-screening.*') ? 'text-white' : 'text-slate-500 group-hover:text-white' }}"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M9 11l2 2 4-4m5-4v14H4V5h16z"/>
                    </svg>

                    Administrative Screening
                </a>

            </div>
        </div>

        {{-- Assessment --}}
        <div>
            <p class="mb-2 px-3 text-[10px] font-bold uppercase tracking-[0.16em] text-slate-500">
                Assessment & Decision
            </p>

            <div class="space-y-1">

                <a href="{{ route('candidate-assessments.index') }}"
                   data-sidebar-link
                   class="group flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition
                   {{ request()->routeIs('candidate-assessments.*') ? 'bg-blue-600 text-white' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">

                    <svg class="h-5 w-5 shrink-0 {{ request()->routeIs('candidate-assessments.*') ? 'text-white' : 'text-slate-500 group-hover:text-white' }}"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M8 4h8l3 3v13H5V4h3zm1 6h6m-6 4h6m-6 4h4"/>
                    </svg>

                    Candidate Assessment
                </a>

                <a href="{{ route('criteria-management.index') }}"
                    data-sidebar-link
                    class="group flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition
                    {{ request()->routeIs('criteria-management.*') ? 'bg-blue-600 text-white' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">

                    <svg class="h-5 w-5 shrink-0 {{ request()->routeIs('criteria-management.*') ? 'text-white' : 'text-slate-500 group-hover:text-white' }}"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M4 6h16M4 12h16M4 18h10"/>
                        <circle cx="18" cy="18" r="2"/>
                    </svg>

                    Criteria Management
                </a>

                <a href="{{ route('saw-analysis.index') }}"
                   data-sidebar-link
                   class="group flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition
                   {{ request()->routeIs('saw-analysis.*') ? 'bg-blue-600 text-white' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">

                    <svg class="h-5 w-5 shrink-0 {{ request()->routeIs('saw-analysis.*') ? 'text-white' : 'text-slate-500 group-hover:text-white' }}"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M4 19V9m5 10V5m5 14v-7m5 7V3"/>
                    </svg>

                    SAW Analysis
                </a>

                <a href="{{ route('hrd-decisions.index') }}"
                   data-sidebar-link
                   class="group flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition
                   {{ request()->routeIs('hrd-decisions.*') ? 'bg-blue-600 text-white' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">

                    <svg class="h-5 w-5 shrink-0 {{ request()->routeIs('hrd-decisions.*') ? 'text-white' : 'text-slate-500 group-hover:text-white' }}"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M12 3 4 7v5c0 5 3.5 8 8 9 4.5-1 8-4 8-9V7l-8-4z"/>
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="m9 12 2 2 4-4"/>
                    </svg>

                    HRD Decisions
                </a>

            </div>
        </div>

        {{-- Administration --}}
        <div>
            <p class="mb-2 px-3 text-[10px] font-bold uppercase tracking-[0.16em] text-slate-500">
                Administration
            </p>

            <div class="space-y-1">

                @if(auth()->user()->isSuperAdmin())
                    <a href="{{ route('users.index') }}"
                       data-sidebar-link
                       class="group flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition
                       {{ request()->routeIs('users.*') ? 'bg-blue-600 text-white' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">

                        <svg class="h-5 w-5 shrink-0 {{ request()->routeIs('users.*') ? 'text-white' : 'text-slate-500 group-hover:text-white' }}"
                             fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                            <circle cx="9" cy="8" r="3"/>
                            <path stroke-linecap="round" d="M3 20v-1a6 6 0 0 1 12 0v1m2-10a3 3 0 1 0 0-6m1 10a5 5 0 0 1 3 5"/>
                        </svg>

                        User Management
                    </a>
                @endif

                <a href="{{ route('reports.index') }}"
                   data-sidebar-link
                   class="group flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition
                   {{ request()->routeIs('reports.*') ? 'bg-blue-600 text-white' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">

                    <svg class="h-5 w-5 shrink-0 {{ request()->routeIs('reports.*') ? 'text-white' : 'text-slate-500 group-hover:text-white' }}"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M6 3h9l4 4v14H6a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2z"/>
                        <path stroke-linecap="round" d="M14 3v5h5M8 13h8m-8 4h8"/>
                    </svg>

                    Reports
                </a>

            </div>
        </div>

    </nav>

    {{-- Sidebar Footer --}}
    <div class="shrink-0 border-t border-slate-800 px-5 py-4">
        <div class="flex items-center gap-3">
            <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-slate-800 text-xs font-bold text-blue-400">
                HW
            </div>

            <div>
                <p class="text-xs font-semibold text-slate-300">
                    HireWise System
                </p>
                <p class="mt-0.5 text-[10px] text-slate-500">
                    Version 1.0
                </p>
            </div>
        </div>
    </div>

</aside>
