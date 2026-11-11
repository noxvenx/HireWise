@extends('layouts.app')

@section('title', 'User Management')

@section('page-title', 'User Management')

@section('content')

    <div class="space-y-6">

        {{-- Flash Message --}}
        @if(session('success'))
            <div class="rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
                {{ session('success') }}
            </div>
        @endif

        {{-- Header --}}
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

            <div>
                <h1 class="text-2xl font-bold text-slate-800">
                    Daftar Pengguna
                </h1>

                <p class="mt-1 text-sm text-slate-500">
                    Kelola akun pengguna HireWise.
                </p>
            </div>

            <a href="{{ route('users.create') }}"
               class="inline-flex items-center justify-center rounded-lg bg-blue-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-blue-700">
                + Tambah Admin HRD
            </a>

        </div>

        {{-- User List --}}
        <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">

            {{-- Desktop Table --}}
            <div class="hidden lg:block">

                <table class="w-full table-fixed text-left text-sm">

                    <thead class="bg-slate-50 text-xs uppercase text-slate-500">
                        <tr>
                            <th class="w-[8%] px-5 py-4">No.</th>
                            <th class="w-[23%] px-5 py-4">Nama</th>
                            <th class="w-[29%] px-5 py-4">Email</th>
                            <th class="w-[15%] px-5 py-4">Role</th>
                            <th class="w-[12%] px-5 py-4">Status</th>
                            <th class="w-[13%] px-5 py-4 text-right">Aksi</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-100">

                        @forelse($users as $user)

                            <tr class="transition hover:bg-slate-50">

                                <td class="px-5 py-4 text-slate-500">
                                    {{ $users->firstItem() + $loop->index }}
                                </td>

                                <td class="break-words px-5 py-4 font-medium text-slate-800">
                                    {{ $user->name }}
                                </td>

                                <td class="break-all px-5 py-4 text-slate-600">
                                    {{ $user->email }}
                                </td>

                                <td class="px-5 py-4 text-slate-700">
                                    {{ $user->role === 'super_admin' ? 'Super Admin' : 'Admin HRD' }}
                                </td>

                                <td class="px-5 py-4">
                                    @if($user->is_active)
                                        <span class="inline-flex rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-700">
                                            Aktif
                                        </span>
                                    @else
                                        <span class="inline-flex rounded-full bg-red-100 px-3 py-1 text-xs font-semibold text-red-700">
                                            Nonaktif
                                        </span>
                                    @endif
                                </td>

                                <td class="px-5 py-4">
                                    <div class="flex items-center justify-end gap-2">

                                        <a href="{{ route('users.edit', $user) }}"
                                           class="rounded-lg bg-blue-50 px-3 py-1.5 text-xs font-semibold text-blue-600 transition hover:bg-blue-100">
                                            Edit
                                        </a>

                                        @if(!$user->isSuperAdmin() && $user->id !== auth()->id())
                                            <button
                                                type="button"
                                                data-delete-url="{{ route('users.destroy', $user) }}"
                                                data-delete-name="{{ $user->name }}"
                                                class="rounded-lg bg-red-50 px-3 py-1.5 text-xs font-semibold text-red-600 transition hover:bg-red-100">
                                                Hapus
                                            </button>
                                        @endif

                                    </div>
                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="6" class="px-5 py-12 text-center text-sm text-slate-400">
                                    Belum ada pengguna.
                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

            {{-- Mobile / Tablet Cards --}}
            <div class="space-y-3 p-4 lg:hidden">

                @forelse($users as $user)

                    <div class="rounded-xl border border-slate-200 bg-white p-4">

                        {{-- User Identity --}}
                        <div class="flex items-start justify-between gap-3">

                            <div class="min-w-0 flex-1">
                                <h3 class="break-words font-semibold text-slate-800">
                                    {{ $user->name }}
                                </h3>

                                <p class="mt-1 break-all text-sm text-slate-500">
                                    {{ $user->email }}
                                </p>
                            </div>

                            @if($user->is_active)
                                <span class="shrink-0 rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-700">
                                    Aktif
                                </span>
                            @else
                                <span class="shrink-0 rounded-full bg-red-100 px-3 py-1 text-xs font-semibold text-red-700">
                                    Nonaktif
                                </span>
                            @endif

                        </div>

                        {{-- User Information --}}
                        <div class="mt-4 grid grid-cols-2 gap-3 border-t border-slate-100 pt-3">

                            <div>
                                <p class="text-xs text-slate-400">No.</p>
                                <p class="mt-1 text-sm font-medium text-slate-700">
                                    {{ $users->firstItem() + $loop->index }}
                                </p>
                            </div>

                            <div>
                                <p class="text-xs text-slate-400">Role</p>
                                <p class="mt-1 text-sm font-medium text-slate-700">
                                    {{ $user->role === 'super_admin' ? 'Super Admin' : 'Admin HRD' }}
                                </p>
                            </div>

                        </div>

                        {{-- Actions --}}
                        <div class="mt-4 flex gap-2 border-t border-slate-100 pt-3">

                            <a href="{{ route('users.edit', $user) }}"
                               class="flex-1 rounded-lg bg-blue-50 px-4 py-2.5 text-center text-sm font-semibold text-blue-600 transition hover:bg-blue-100">
                                Edit
                            </a>

                            @if(!$user->isSuperAdmin() && $user->id !== auth()->id())
                                <button
                                    type="button"
                                    data-delete-url="{{ route('users.destroy', $user) }}"
                                    data-delete-name="{{ $user->name }}"
                                    class="flex-1 rounded-lg bg-red-50 px-4 py-2.5 text-sm font-semibold text-red-600 transition hover:bg-red-100">
                                    Hapus
                                </button>
                            @endif

                        </div>

                    </div>

                @empty

                    <div class="rounded-lg border border-dashed border-slate-300 px-4 py-10 text-center text-sm text-slate-400">
                        Belum ada pengguna.
                    </div>

                @endforelse

            </div>

            {{-- Pagination --}}
            @if($users->hasPages())
                <div class="border-t border-slate-100 px-4 py-4 sm:px-6">
                    {{ $users->links() }}
                </div>
            @endif

        </div>

    </div>

@endsection
