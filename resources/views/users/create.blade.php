@extends('layouts.app')

@section('title', 'Tambah Admin HRD')
@section('page-title', 'Tambah Admin HRD')

@section('content')

    <div class="max-w-8xl">

        <div class="mb-6">
            <h1 class="text-2xl font-bold text-slate-800">
                Tambah Admin HRD
            </h1>
            <p class="text-sm text-slate-500 mt-1">
                Buat akun baru untuk pengguna dengan hak akses Admin HRD.
            </p>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-6">

            <form action="{{ route('users.store') }}" method="POST">
                @csrf

                <div class="mb-5">
                    <label class="block text-sm font-medium text-slate-700 mb-2">
                        Nama Lengkap
                    </label>
                    <input
                        type="text"
                        name="name"
                        value="{{ old('name') }}"
                        required
                        class="w-full border border-slate-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:outline-none"
                        placeholder="Masukkan nama lengkap"
                    >
                    @error('name')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-5">
                    <label class="block text-sm font-medium text-slate-700 mb-2">
                        Email
                    </label>
                    <input
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        required
                        class="w-full border border-slate-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:outline-none"
                        placeholder="nama@email.com"
                    >
                    @error('email')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-5">
                    <label class="block text-sm font-medium text-slate-700 mb-2">
                        Password
                    </label>
                    <input
                        type="password"
                        name="password"
                        required
                        class="w-full border border-slate-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:outline-none"
                        placeholder="Minimal 8 karakter"
                    >
                    @error('password')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-slate-700 mb-2">
                        Konfirmasi Password
                    </label>
                    <input
                        type="password"
                        name="password_confirmation"
                        required
                        class="w-full border border-slate-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:outline-none"
                        placeholder="Ulangi password"
                    >
                </div>

                <div class="flex justify-end gap-3">
                    <a href="{{ route('users.index') }}"
                       class="px-5 py-3 rounded-lg border border-slate-300 text-slate-600 hover:bg-slate-50">
                        Batal
                    </a>

                    <button type="submit"
                            class="px-5 py-3 rounded-lg bg-blue-600 text-white font-medium hover:bg-blue-700">
                        Simpan Pengguna
                    </button>
                </div>

            </form>
        </div>
    </div>

@endsection