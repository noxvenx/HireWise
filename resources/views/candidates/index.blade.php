@extends('layouts.app')

@section('title', 'Candidate Management')
@section('page-title', 'Candidate Management')

@section('content')

<div class="space-y-6">

    {{-- Page Heading --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-xl font-bold text-slate-800">Candidate Management</h2>
            <p class="text-sm text-slate-500 mt-1">
                Kelola data master seluruh kandidat HireWise.
            </p>
        </div>

        <a href="{{ route('candidates.create') }}"
           class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-lg bg-blue-600 text-white text-sm font-semibold hover:bg-blue-700">
            <span class="text-lg leading-none">+</span>
            Tambah Kandidat
        </a>
    </div>

    {{-- Summary --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div class="bg-white border border-slate-200 rounded-xl p-5 shadow-sm">
            <p class="text-sm text-slate-500">Total Kandidat</p>
            <h3 class="text-2xl font-bold text-slate-800 mt-2">
                {{ $candidates->total() }}
            </h3>
        </div>
    </div>

    {{-- Candidate Table --}}
    <div class="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden">

        <div class="p-5 border-b border-slate-100">
            <form action="{{ route('candidates.index') }}" method="GET"
                  class="flex flex-col sm:flex-row gap-3">

                <input
                    type="text"
                    name="search"
                    value="{{ $search }}"
                    placeholder="Cari kode, nama, email, atau telepon..."
                    class="flex-1 rounded-lg border border-slate-300 px-4 py-2 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                >

                <button type="submit"
                        class="px-5 py-2 rounded-lg bg-slate-800 text-white text-sm font-medium hover:bg-slate-700">
                    Cari
                </button>

                @if($search)
                    <a href="{{ route('candidates.index') }}"
                       class="px-4 py-2 rounded-lg border border-slate-300 text-sm text-slate-600 hover:bg-slate-50 text-center">
                        Reset
                    </a>
                @endif
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-slate-50 text-slate-500 text-xs uppercase">
                    <tr>
                        <th class="px-6 py-4 font-semibold">Kode Kandidat</th>
                        <th class="px-6 py-4 font-semibold">Nama Kandidat</th>
                        <th class="px-6 py-4 font-semibold">Kontak</th>
                        <th class="px-6 py-4 font-semibold">Pendidikan</th>
                        <th class="px-6 py-4 font-semibold text-right">Aksi</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100">

                    @forelse($candidates as $candidate)
                        <tr class="hover:bg-slate-50">

                            <td class="px-6 py-4 font-medium text-blue-700 whitespace-nowrap">
                                {{ $candidate->candidate_code }}
                            </td>

                            <td class="px-6 py-4">
                                <p class="font-semibold text-slate-800">
                                    {{ $candidate->full_name }}
                                </p>
                                <p class="text-xs text-slate-500 mt-1">
                                    {{ $candidate->gender === 'male' ? 'Laki-laki' : ($candidate->gender === 'female' ? 'Perempuan' : '-') }}
                                </p>
                            </td>

                            <td class="px-6 py-4">
                                <p class="text-slate-700">{{ $candidate->email ?: '-' }}</p>
                                <p class="text-xs text-slate-500 mt-1">{{ $candidate->phone ?: '-' }}</p>
                            </td>

                            <td class="px-6 py-4">
                                <p class="text-slate-700">{{ $candidate->education ?: '-' }}</p>
                                <p class="text-xs text-slate-500 mt-1">{{ $candidate->institution ?: '-' }}</p>
                            </td>

                            <td class="px-6 py-4">
                                <div class="flex items-center justify-end gap-2 whitespace-nowrap">

                                    <a href="{{ route('candidates.show', $candidate) }}"
                                       class="px-3 py-1.5 rounded-lg text-xs font-medium text-slate-600 bg-slate-100 hover:bg-slate-200">
                                        Detail
                                    </a>

                                    <a href="{{ route('candidates.edit', $candidate) }}"
                                       class="px-3 py-1.5 rounded-lg text-xs font-medium text-blue-600 bg-blue-50 hover:bg-blue-100">
                                        Edit
                                    </a>

                                    <button
                                        type="button"
                                        data-delete-url="{{ route('candidates.destroy', $candidate) }}"
                                        data-delete-name="{{ $candidate->full_name }}"
                                        class="px-3 py-1.5 rounded-lg text-xs font-medium text-red-600 bg-red-50 hover:bg-red-100">
                                        Hapus
                                    </button>

                                </div>
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <p class="font-medium text-slate-600">Belum ada data kandidat</p>
                                <p class="text-xs text-slate-400 mt-1">
                                    Tambahkan kandidat untuk mulai mengelola data rekrutmen.
                                </p>
                            </td>
                        </tr>
                    @endforelse

                </tbody>
            </table>
        </div>

        <div class="px-6 py-4 border-t border-slate-100">
            {{ $candidates->links() }}
        </div>

    </div>
</div>

@endsection