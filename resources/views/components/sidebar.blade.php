<aside class="fixed inset-y-0 left-0 z-40 hidden w-72 flex-col bg-slate-900 text-white lg:flex">

    {{-- Brand --}}
    <div class="flex h-20 items-center gap-3 border-b border-slate-800 px-7">
        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-indigo-600">
            <span class="text-xl font-bold">H</span>
        </div>

        <div>
            <h1 class="text-xl font-bold tracking-wide">HireWise</h1>
            <p class="text-xs text-slate-400">Recruitment Management</p>
        </div>
    </div>

    {{-- Navigation --}}
    <nav class="flex-1 space-y-7 overflow-y-auto px-4 py-7">

        <div>
            <p class="mb-3 px-3 text-xs font-semibold uppercase tracking-wider text-slate-500">
                Overview
            </p>

            <a href="{{ route('dashboard') }}"
               class="flex items-center gap-3 rounded-lg bg-indigo-600 px-3 py-3 text-sm font-medium text-white">
                <span>◈</span>
                Dashboard
            </a>
        </div>

        <div>
            <p class="mb-3 px-3 text-xs font-semibold uppercase tracking-wider text-slate-500">
                Recruitment
            </p>

            <div class="space-y-1">
                <a href="#" class="flex items-center gap-3 rounded-lg px-3 py-3 text-sm text-slate-300 hover:bg-slate-800 hover:text-white">
                    <span>▣</span> Recruitment Batch
                </a>

                <a href="#" class="flex items-center gap-3 rounded-lg px-3 py-3 text-sm text-slate-300 hover:bg-slate-800 hover:text-white">
                    <span>♙</span> Candidates
                </a>

                <a href="#" class="flex items-center gap-3 rounded-lg px-3 py-3 text-sm text-slate-300 hover:bg-slate-800 hover:text-white">
                    <span>✓</span> Screening
                </a>

                <a href="#" class="flex items-center gap-3 rounded-lg px-3 py-3 text-sm text-slate-300 hover:bg-slate-800 hover:text-white">
                    <span>⇧</span> Import History
                </a>
            </div>
        </div>

        <div>
            <p class="mb-3 px-3 text-xs font-semibold uppercase tracking-wider text-slate-500">
                Decision Support
            </p>

            <div class="space-y-1">
                <a href="#" class="flex items-center gap-3 rounded-lg px-3 py-3 text-sm text-slate-300 hover:bg-slate-800 hover:text-white">
                    <span>⚙</span> Criteria & Weights
                </a>

                <a href="#" class="flex items-center gap-3 rounded-lg px-3 py-3 text-sm text-slate-300 hover:bg-slate-800 hover:text-white">
                    <span>▤</span> Assessment
                </a>

                <a href="#" class="flex items-center gap-3 rounded-lg px-3 py-3 text-sm text-slate-300 hover:bg-slate-800 hover:text-white">
                    <span>∑</span> SAW Calculation
                </a>

                <a href="#" class="flex items-center gap-3 rounded-lg px-3 py-3 text-sm text-slate-300 hover:bg-slate-800 hover:text-white">
                    <span>♜</span> Candidate Ranking
                </a>

                <a href="#" class="flex items-center gap-3 rounded-lg px-3 py-3 text-sm text-slate-300 hover:bg-slate-800 hover:text-white">
                    <span>☑</span> Decisions
                </a>
            </div>
        </div>

        <div>
            <p class="mb-3 px-3 text-xs font-semibold uppercase tracking-wider text-slate-500">
                System
            </p>

            <a href="#" class="flex items-center gap-3 rounded-lg px-3 py-3 text-sm text-slate-300 hover:bg-slate-800 hover:text-white">
                <span>▤</span> Reports
            </a>

            <a href="#" class="flex items-center gap-3 rounded-lg px-3 py-3 text-sm text-slate-300 hover:bg-slate-800 hover:text-white">
                <span>◷</span> Activity Logs
            </a>
        </div>

    </nav>

    {{-- Sidebar Footer --}}
    <div class="border-t border-slate-800 p-5">
        <p class="text-xs text-slate-400">HireWise v1.0</p>
        <p class="mt-1 text-xs text-slate-500">Internal Recruitment System</p>
    </div>

</aside>
