@extends('layouts.app')

@section('title', 'Candidate Management')
@section('page-title', 'Candidate Management')

@section('content')

<div class="space-y-6">

    {{-- Page Heading --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <div class="flex items-center gap-2">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-50 text-blue-600">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2m16 0v-2a4 4 0 0 0-3-3.87M14 3.13a4 4 0 0 1 0 7.75M10 7a4 4 0 1 1-8 0 4 4 0 0 1 8 0Z"/>
                    </svg>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-slate-800">Candidate Management</h2>
                    <p class="mt-0.5 text-sm text-slate-500">
                        Kelola data master seluruh kandidat HireWise.
                    </p>
                </div>
            </div>
        </div>

        <a href="{{ route('candidates.create') }}"
           class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700 sm:w-auto">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" d="M12 5v14m-7-7h14"/>
            </svg>
            Tambah Kandidat
        </a>
    </div>

    {{-- Summary --}}
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-500">Total Kandidat</p>
                    <h3 class="mt-2 text-3xl font-bold tracking-tight text-slate-800">
                        {{ $candidates->total() }}
                    </h3>
                    <p class="mt-1 text-xs text-slate-400">Data master kandidat terdaftar</p>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-blue-50 text-blue-600">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2m16 0v-2a4 4 0 0 0-3-3.87M14 3.13a4 4 0 0 1 0 7.75M10 7a4 4 0 1 1-8 0 4 4 0 0 1 8 0Z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    {{-- Candidate List --}}
    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

        {{-- Search Header --}}
        <div class="border-b border-slate-100 p-5 sm:px-6">
            <div class="mb-4">
                <h3 class="text-sm font-bold text-slate-800">Daftar Kandidat</h3>
                <p class="mt-1 text-xs text-slate-500">
                    Cari dan kelola informasi kandidat yang tersimpan.
                </p>
            </div>

            <form action="{{ route('candidates.index') }}" method="GET"
                  class="flex flex-col gap-3 sm:flex-row">

                <div class="relative flex-1">
                    <svg class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <circle cx="11" cy="11" r="7"/>
                        <path stroke-linecap="round" d="m16 16 4 4"/>
                    </svg>

                    <input
                        type="text"
                        name="search"
                        value="{{ $search }}"
                        placeholder="Cari kode, nama, email, atau telepon..."
                        class="w-full rounded-xl border border-slate-300 py-2.5 pl-10 pr-4 text-sm text-slate-700 outline-none transition placeholder:text-slate-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                    >
                </div>

                <div class="flex gap-2">
                    <button type="submit"
                            class="inline-flex flex-1 items-center justify-center gap-2 rounded-xl bg-slate-800 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-slate-700 sm:flex-none">
                        Cari
                    </button>

                    @if($search)
                        <a href="{{ route('candidates.index') }}"
                           class="inline-flex items-center justify-center rounded-xl border border-slate-300 px-4 py-2.5 text-sm font-medium text-slate-600 transition hover:bg-slate-50">
                            Reset
                        </a>
                    @endif
                </div>
            </form>
        </div>

        {{-- DESKTOP TABLE --}}
        <div class="hidden lg:block">
            @if($candidates->isNotEmpty())
                <table class="w-full table-fixed text-left text-sm">
                    <thead class="bg-slate-50 text-[11px] font-bold uppercase tracking-wider text-slate-500">
                        <tr>
                            <th class="w-[16%] px-4 py-4">Kode Kandidat</th>
                            <th class="w-[22%] px-4 py-4">Nama Kandidat</th>
                            <th class="w-[25%] px-4 py-4">Kontak</th>
                            <th class="w-[20%] px-4 py-4">Pendidikan</th>
                            <th class="w-[17%] px-4 py-4 text-center">Aksi</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-100">
                        @foreach($candidates as $candidate)
                            <tr class="transition hover:bg-slate-50/80">

                                <td class="break-words px-4 py-4 font-bold text-blue-700">
                                    {{ $candidate->candidate_code }}
                                </td>

                                <td class="break-words px-4 py-4">
                                    <p class="font-semibold text-slate-800">
                                        {{ $candidate->full_name }}
                                    </p>
                                    <p class="mt-1 text-xs text-slate-500">
                                        {{ $candidate->gender === 'male' ? 'Laki-laki' : ($candidate->gender === 'female' ? 'Perempuan' : '-') }}
                                    </p>
                                </td>

                                <td class="px-4 py-4">
                                    <p class="break-all text-slate-700">
                                        {{ $candidate->email ?: '-' }}
                                    </p>
                                    <p class="mt-1 break-words text-xs text-slate-500">
                                        {{ $candidate->phone ?: '-' }}
                                    </p>
                                </td>

                                <td class="break-words px-4 py-4">
                                    <p class="text-slate-700">
                                        {{ $candidate->education ?: '-' }}
                                    </p>
                                    <p class="mt-1 text-xs text-slate-500">
                                        {{ $candidate->institution ?: '-' }}
                                    </p>
                                </td>

                                <td class="px-3 py-4">
                                    <div class="flex flex-wrap items-center justify-center gap-1.5">
                                        <a href="{{ route('candidates.show', $candidate) }}"
                                           class="rounded-lg bg-slate-100 px-2.5 py-1.5 text-[11px] font-semibold text-slate-600 transition hover:bg-slate-200">
                                            Detail
                                        </a>

                                        <a href="{{ route('candidates.edit', $candidate) }}"
                                           class="rounded-lg bg-blue-50 px-2.5 py-1.5 text-[11px] font-semibold text-blue-600 transition hover:bg-blue-100">
                                            Edit
                                        </a>

                                        <button
                                            type="button"
                                            data-delete-url="{{ route('candidates.destroy', $candidate) }}"
                                            data-delete-name="{{ $candidate->full_name }}"
                                            class="rounded-lg bg-red-50 px-2.5 py-1.5 text-[11px] font-semibold text-red-600 transition hover:bg-red-100">
                                            Hapus
                                        </button>
                                    </div>
                                </td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="px-6 py-16 text-center">
                    <p class="text-sm font-semibold text-slate-700">Belum ada data kandidat</p>
                    <p class="mt-1 text-xs text-slate-500">
                        Tambahkan kandidat untuk mulai mengelola data rekrutmen.
                    </p>
                </div>
            @endif
        </div>

        {{-- TABLET & MOBILE ACCORDION --}}
        <div class="space-y-3 p-4 lg:hidden">
            @forelse($candidates as $candidate)
                <div class="overflow-hidden rounded-xl border border-slate-200 bg-white">
                    <details class="group">
                        <summary class="flex cursor-pointer list-none items-center justify-between gap-3 p-4">

                            <div class="min-w-0 flex-1">
                                <p class="text-xs font-bold text-blue-700">
                                    {{ $candidate->candidate_code }}
                                </p>

                                <p class="mt-2 truncate text-sm font-bold text-slate-800">
                                    {{ $candidate->full_name }}
                                </p>

                                <p class="mt-1 truncate text-xs text-slate-500">
                                    {{ $candidate->email ?: ($candidate->phone ?: '-') }}
                                </p>
                            </div>

                            <svg class="h-5 w-5 shrink-0 text-slate-400 transition-transform group-open:rotate-180"
                                 fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m6 9 6 6 6-6"/>
                            </svg>

                        </summary>

                        <div class="border-t border-slate-100 bg-slate-50/70 p-4">

                            <div class="grid grid-cols-2 gap-4 text-sm">

                                <div>
                                    <p class="text-[11px] text-slate-400">Jenis Kelamin</p>
                                    <p class="mt-1 font-semibold text-slate-700">
                                        {{ $candidate->gender === 'male' ? 'Laki-laki' : ($candidate->gender === 'female' ? 'Perempuan' : '-') }}
                                    </p>
                                </div>

                                <div>
                                    <p class="text-[11px] text-slate-400">Pendidikan</p>
                                    <p class="mt-1 font-semibold text-slate-700">
                                        {{ $candidate->education ?: '-' }}
                                    </p>
                                </div>

                                <div class="col-span-2">
                                    <p class="text-[11px] text-slate-400">Email</p>
                                    <p class="mt-1 break-all font-semibold text-slate-700">
                                        {{ $candidate->email ?: '-' }}
                                    </p>
                                </div>

                                <div>
                                    <p class="text-[11px] text-slate-400">Telepon</p>
                                    <p class="mt-1 break-words font-semibold text-slate-700">
                                        {{ $candidate->phone ?: '-' }}
                                    </p>
                                </div>

                                <div>
                                    <p class="text-[11px] text-slate-400">Institusi</p>
                                    <p class="mt-1 break-words font-semibold text-slate-700">
                                        {{ $candidate->institution ?: '-' }}
                                    </p>
                                </div>

                            </div>

                            <div class="mt-4 grid grid-cols-3 gap-2 border-t border-slate-200 pt-4">

                                <a href="{{ route('candidates.show', $candidate) }}"
                                   class="rounded-lg bg-slate-200 px-2 py-2 text-center text-xs font-semibold text-slate-700 transition hover:bg-slate-300">
                                    Detail
                                </a>

                                <a href="{{ route('candidates.edit', $candidate) }}"
                                   class="rounded-lg bg-blue-50 px-2 py-2 text-center text-xs font-semibold text-blue-600 transition hover:bg-blue-100">
                                    Edit
                                </a>

                                <button
                                    type="button"
                                    data-delete-url="{{ route('candidates.destroy', $candidate) }}"
                                    data-delete-name="{{ $candidate->full_name }}"
                                    class="rounded-lg bg-red-50 px-2 py-2 text-center text-xs font-semibold text-red-600 transition hover:bg-red-100">
                                    Hapus
                                </button>

                            </div>
                        </div>
                    </details>
                </div>
            @empty
                <div class="py-12 text-center">
                    <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-slate-100 text-slate-400">
                        <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.6">
                            <circle cx="10" cy="10" r="7"/>
                            <path stroke-linecap="round" d="m15 15 5 5"/>
                        </svg>
                    </div>

                    <p class="mt-4 text-sm font-semibold text-slate-700">
                        Belum ada data kandidat
                    </p>
                    <p class="mt-1 text-xs text-slate-500">
                        Tambahkan kandidat untuk mulai mengelola data rekrutmen.
                    </p>
                </div>
            @endforelse
        </div>

        {{-- Shared Pagination --}}
        <div class="border-t border-slate-100 px-5 py-4 sm:px-6">
            {{ $candidates->links() }}
        </div>

    </div>
</div>

@endsection
