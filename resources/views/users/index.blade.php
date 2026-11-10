@extends('layouts.app')

@section('title', 'User Management')

@section('page-title', 'User Management')

@section('content')

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 p-4 rounded-lg mb-6">
            {{ session('success') }}
        </div>
    @endif

    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 mb-6">

        <div>
            <h1 class="text-2xl font-bold text-slate-800">
                Daftar Pengguna
            </h1>

            <p class="text-sm text-slate-500 mt-1">
                Kelola akun pengguna HireWise.
            </p>
        </div>

        <a href="{{ route('users.create') }}"
           class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-3 rounded-lg font-medium text-center">
            + Tambah Admin HRD
        </a>

    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden">

        <div class="overflow-x-auto">

            <table class="w-full text-sm text-left">

                <thead class="bg-slate-50 text-slate-600">
                    <tr>
                        <th class="px-6 py-4">No.</th>
                        <th class="px-6 py-4">Nama</th>
                        <th class="px-6 py-4">Email</th>
                        <th class="px-6 py-4">Role</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100">

                    @forelse($users as $user)

                        <tr class="hover:bg-slate-50">

                            <td class="px-6 py-4">
                                {{ $users->firstItem() + $loop->index }}
                            </td>

                            <td class="px-6 py-4 font-medium text-slate-800">
                                {{ $user->name }}
                            </td>

                            <td class="px-6 py-4 text-slate-600">
                                {{ $user->email }}
                            </td>

                            <td class="px-6 py-4">
                                {{ $user->role === 'super_admin' ? 'Super Admin' : 'Admin HRD' }}
                            </td>

                            <td class="px-6 py-4">
                                @if($user->is_active)
                                    <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs">
                                        Aktif
                                    </span>
                                @else
                                    <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-xs">
                                        Nonaktif
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right whitespace-nowrap">
                                <div class="flex items-center justify-end gap-2">

                                    <a
                                        href="{{ route('users.edit', $user) }}"
                                        class="px-3 py-1.5 rounded-lg text-xs font-medium text-blue-600 bg-blue-50 hover:bg-blue-100"
                                    >
                                        Edit
                                    </a>

                                    @if(!$user->isSuperAdmin() && $user->id !== auth()->id())
                                        <button
                                            type="button"
                                            data-delete-url="{{ route('users.destroy', $user) }}"
                                            data-delete-name="{{ $user->name }}"
                                            class="px-3 py-1.5 rounded-lg text-xs font-medium text-red-600 bg-red-50 hover:bg-red-100"
                                        >
                                            Hapus
                                        </button>
                                    @endif

                                </div>
                            </td>


                        </tr>

                    @empty

                        <tr>
                            <td colspan="5" class="text-center px-6 py-10 text-slate-400">
                                Belum ada pengguna.
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

        <div class="px-6 py-4 border-t border-slate-100">
            {{ $users->links() }}
        </div>

    </div>

@endsection