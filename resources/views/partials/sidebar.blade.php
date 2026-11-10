<aside class="w-64 h-screen sticky top-0 flex flex-col bg-slate-900 text-white shrink-0">

    <!-- Header / Logo Area -->
    <!-- Padding p-5 diubah jadi p-4, ukuran logo diperkecil jadi w-8 h-8 -->
    <div class="p-4 shrink-0">
        <div class="flex items-center gap-3 px-2">
            <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center font-bold text-lg">
                H
            </div>
            <div>
                <h1 class="text-lg font-bold leading-none">HireWise</h1>
                <p class="text-[11px] text-slate-400 mt-1">Recruitment System</p>
            </div>
        </div>
    </div>

    <!-- Menu Navigasi -->
    <!-- space-y-2 diubah jadi space-y-1, dan font default dijadikan text-sm -->
    <nav class="flex-1 overflow-y-auto px-4 py-2 space-y-1 text-sm">

        <!-- Margin mb-3 diubah jadi mb-1 -->
        <p class="text-[11px] uppercase tracking-wider text-slate-500 px-3 mb-1">
            Main Menu
        </p>

        <!-- Padding tombol py-3 diubah menjadi py-2 -->
        <a href="{{ route('dashboard') }}"
           class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('dashboard') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-800' }}">
            Dashboard
        </a>

        <!-- Jarak antar kategori pt-6 diubah drastis menjadi pt-3 -->
        <p class="text-[11px] uppercase tracking-wider text-slate-500 px-3 pt-3 mb-1">
            Recruitment
        </p>

        <a href="{{ route('recruitment-batches.index') }}"
           class="block px-3 py-2 rounded-lg {{ request()->routeIs('recruitment-batches.*') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-800' }}">
            Recruitment Batch
        </a>

        <a href="#" class="block px-3 py-2 rounded-lg text-slate-300 hover:bg-slate-800">
            Candidates
        </a>

        <a href="#" class="block px-3 py-2 rounded-lg text-slate-300 hover:bg-slate-800">
            Administrative Screening
        </a>

        <p class="text-[11px] uppercase tracking-wider text-slate-500 px-3 pt-3 mb-1">
            Assessment
        </p>

        <a href="#" class="block px-3 py-2 rounded-lg text-slate-300 hover:bg-slate-800">
            Criteria Management
        </a>

        <a href="#" class="block px-3 py-2 rounded-lg text-slate-300 hover:bg-slate-800">
            Candidate Assessment
        </a>

        <a href="#" class="block px-3 py-2 rounded-lg text-slate-300 hover:bg-slate-800">
            SAW Analysis
        </a>

        <a href="#" class="block px-3 py-2 rounded-lg text-slate-300 hover:bg-slate-800">
            HRD Decisions
        </a>

        <p class="text-[11px] uppercase tracking-wider text-slate-500 px-3 pt-3 mb-1">
            Administration
        </p>

        @if(auth()->user()->isSuperAdmin())
            <a href="{{ route('users.index') }}"
               class="block px-3 py-2 rounded-lg {{ request()->routeIs('users.*') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-800' }}">
                User Management
            </a>
        @endif

        <a href="#" class="block px-3 py-2 rounded-lg text-slate-300 hover:bg-slate-800">
            Reports
        </a>

    </nav>

    <!-- Footer System -->
    <div class="shrink-0 p-4 border-t border-slate-800 mt-auto">
        <p class="text-[11px] text-slate-500">HireWise System</p>
        <p class="text-[11px] text-slate-400">Version 1.0</p>
    </div>

</aside>