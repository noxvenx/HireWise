<header class="bg-white border-b border-slate-200 px-5 md:px-8 py-4 flex justify-between items-center">

    <div>
        <h2 class="text-lg font-bold text-slate-800">
            @yield('page-title', 'Dashboard')
        </h2>

        <p class="text-xs text-slate-500 mt-1">
            HireWise Recruitment Management System
        </p>
    </div>

    <div class="flex items-center gap-3">

        <div class="text-right hidden sm:block">
            <p class="text-sm font-semibold text-slate-800">
                {{ auth()->user()->name }}
            </p>

            <p class="text-xs text-slate-500">
                {{ auth()->user()->isSuperAdmin() ? 'Super Admin' : 'Admin HRD' }}
            </p>
        </div>

        <form method="POST" action="{{ route('logout') }}">
            @csrf

            <button type="submit"
                class="bg-red-50 hover:bg-red-100 text-red-600 px-3 py-2 rounded-lg text-sm font-medium">
                Logout
            </button>
        </form>

    </div>

</header>
