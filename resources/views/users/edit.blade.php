
@extends('layouts.app')

@section('title', 'Edit Admin HRD')
@section('page-title', 'Edit Admin HRD')

@section('content')
<div class="max-w-8xl mx-auto space-y-6">

    <div>
        <h2 class="text-xl font-bold text-slate-800">Edit Akun Admin HRD</h2>
        <p class="text-sm text-slate-500 mt-1">
            Perbarui informasi akun dan status akses pengguna.
        </p>
    </div>

    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">

        <form action="{{ route('users.update', $user) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="space-y-5">

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">
                        Nama Lengkap
                    </label>
                    <input
                        type="text"
                        name="name"
                        value="{{ old('name', $user->name) }}"
                        required
                        class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                    >
                    @error('name')
                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">
                        Email
                    </label>
                    <input
                        type="email"
                        name="email"
                        value="{{ old('email', $user->email) }}"
                        required
                        class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                    >
                    @error('email')
                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="border-t border-slate-100 pt-5">
                    <h3 class="text-sm font-semibold text-slate-800">
                        Ubah Password
                    </h3>
                    <p class="text-xs text-slate-500 mt-1">
                        Kosongkan jika tidak ingin mengubah password.
                    </p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">
                        Password Baru
                    </label>
                    <input
                        type="password"
                        name="password"
                        autocomplete="new-password"
                        class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                    >
                    @error('password')
                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">
                        Konfirmasi Password Baru
                    </label>
                    <input
                        type="password"
                        name="password_confirmation"
                        autocomplete="new-password"
                        class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                    >
                </div>

                <div class="border-t border-slate-100 pt-5">
                    <label class="block text-sm font-medium text-slate-700 mb-2">
                        Status Akun
                    </label>

                    <input type="hidden" name="is_active" value="0">

                    <label class="inline-flex items-center gap-2 cursor-pointer">
                        <input
                            type="checkbox"
                            name="is_active"
                            value="1"
                            {{ old('is_active', $user->is_active) ? 'checked' : '' }}
                            class="rounded border-slate-300 text-blue-600 focus:ring-blue-500"
                        >
                        <span class="text-sm text-slate-700">
                            Akun Aktif
                        </span>
                    </label>

                    @error('is_active')
                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

            </div>

            <div class="flex items-center justify-end gap-3 border-t border-slate-100 mt-6 pt-5">
                <a
                    href="{{ route('users.index') }}"
                    class="px-4 py-2 rounded-lg text-sm font-medium text-slate-600 hover:bg-slate-100"
                >
                    Batal
                </a>

                <button
                    type="submit"
                    class="px-4 py-2 rounded-lg bg-blue-600 text-white text-sm font-medium hover:bg-blue-700"
                >
                    Simpan Perubahan
                </button>
            </div>

        </form>
    </div>
</div>
@endsection
