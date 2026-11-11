@extends('layouts.app')

@section('title', 'Detail Kandidat')
@section('page-title', 'Detail Kandidat')

@section('content')

<div class="mx-auto max-w-8xl space-y-6">

    {{-- Page Heading --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <a href="{{ route('candidates.index') }}"
               class="inline-flex items-center gap-2 text-sm font-medium text-blue-600 transition hover:text-blue-800">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m15 18-6-6 6-6"/>
                </svg>
                Kembali ke Candidate Management
            </a>

            <h2 class="mt-3 text-xl font-bold text-slate-800">
                Detail Kandidat
            </h2>
            <p class="mt-1 text-sm text-slate-500">
                Informasi lengkap kandidat HireWise.
            </p>
        </div>

        <a href="{{ route('candidates.edit', $candidate) }}"
           class="inline-flex items-center justify-center gap-2 rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="m16.5 3.5 4 4L9 19l-5 1 1-5L16.5 3.5Z"/>
            </svg>
            Edit Kandidat
        </a>
    </div>

    {{-- Profile --}}
    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="h-2 bg-blue-600"></div>

        <div class="flex flex-col gap-4 p-5 sm:flex-row sm:items-center sm:p-6">
            <div class="flex h-16 w-16 shrink-0 items-center justify-center rounded-2xl bg-blue-50 text-2xl font-bold text-blue-700 ring-1 ring-blue-100">
                {{ strtoupper(substr($candidate->full_name, 0, 1)) }}
            </div>

            <div class="min-w-0">
                <h3 class="text-xl font-bold text-slate-800">
                    {{ $candidate->full_name }}
                </h3>
                <p class="mt-1 text-sm font-semibold text-blue-600">
                    {{ $candidate->candidate_code }}
                </p>
                <p class="mt-2 text-xs text-slate-500">
                    Data master kandidat HireWise
                </p>
            </div>
        </div>
    </div>

    {{-- Personal Information --}}
    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

        <div class="border-b border-slate-100 px-5 py-4 sm:px-6">
            <div class="flex items-center gap-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-50 text-blue-600">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                        <circle cx="12" cy="8" r="4"/>
                        <path stroke-linecap="round" d="M4 21a8 8 0 0 1 16 0"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-base font-bold text-slate-800">Informasi Identitas</h3>
                    <p class="mt-0.5 text-xs text-slate-500">Data pribadi dan kontak kandidat.</p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-x-8 gap-y-6 p-5 sm:grid-cols-2 sm:p-6">

            <div>
                <p class="text-xs font-medium text-slate-500">Email</p>
                <p class="mt-2 break-words text-sm font-semibold text-slate-800">
                    {{ $candidate->email ?: '-' }}
                </p>
            </div>

            <div>
                <p class="text-xs font-medium text-slate-500">Nomor Telepon</p>
                <p class="mt-2 text-sm font-semibold text-slate-800">
                    {{ $candidate->phone ?: '-' }}
                </p>
            </div>

            <div>
                <p class="text-xs font-medium text-slate-500">Jenis Kelamin</p>
                <p class="mt-2 text-sm font-semibold text-slate-800">
                    {{ $candidate->gender === 'male' ? 'Laki-laki' : ($candidate->gender === 'female' ? 'Perempuan' : '-') }}
                </p>
            </div>

            <div>
                <p class="text-xs font-medium text-slate-500">Tanggal Lahir</p>
                <p class="mt-2 text-sm font-semibold text-slate-800">
                    {{ $candidate->birth_date?->format('d/m/Y') ?? '-' }}
                </p>
            </div>

            <div class="sm:col-span-2">
                <p class="text-xs font-medium text-slate-500">Alamat</p>
                <p class="mt-2 whitespace-pre-line text-sm font-semibold leading-relaxed text-slate-800">
                    {{ $candidate->address ?: '-' }}
                </p>
            </div>

        </div>
    </div>

    {{-- Education --}}
    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

        <div class="border-b border-slate-100 px-5 py-4 sm:px-6">
            <div class="flex items-center gap-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2 9l10-5 10 5-10 5L2 9Z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 11v5c3.5 3 8.5 3 12 0v-5M22 9v6"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-base font-bold text-slate-800">Informasi Pendidikan</h3>
                    <p class="mt-0.5 text-xs text-slate-500">Riwayat pendidikan terakhir kandidat.</p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-x-8 gap-y-6 p-5 sm:grid-cols-2 sm:p-6">

            <div>
                <p class="text-xs font-medium text-slate-500">Pendidikan Terakhir</p>
                <p class="mt-2 text-sm font-semibold text-slate-800">
                    {{ $candidate->education ?: '-' }}
                </p>
            </div>

            <div>
                <p class="text-xs font-medium text-slate-500">Tahun Lulus</p>
                <p class="mt-2 text-sm font-semibold text-slate-800">
                    {{ $candidate->graduation_year ?: '-' }}
                </p>
            </div>

            <div>
                <p class="text-xs font-medium text-slate-500">Institusi</p>
                <p class="mt-2 text-sm font-semibold text-slate-800">
                    {{ $candidate->institution ?: '-' }}
                </p>
            </div>

            <div>
                <p class="text-xs font-medium text-slate-500">Jurusan</p>
                <p class="mt-2 text-sm font-semibold text-slate-800">
                    {{ $candidate->major ?: '-' }}
                </p>
            </div>

        </div>
    </div>

    {{-- HRD Notes --}}
    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

        <div class="border-b border-slate-100 px-5 py-4 sm:px-6">
            <div class="flex items-center gap-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-amber-50 text-amber-600">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 6h13M8 12h13M8 18h13M3 6h.01M3 12h.01M3 18h.01"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-base font-bold text-slate-800">Catatan HRD</h3>
                    <p class="mt-0.5 text-xs text-slate-500">Catatan tambahan mengenai kandidat.</p>
                </div>
            </div>
        </div>

        <div class="p-5 sm:p-6">
            <div class="rounded-xl bg-slate-50 p-4 text-sm leading-relaxed text-slate-600">
                <p class="whitespace-pre-line">{{ $candidate->notes ?: 'Belum ada catatan.' }}</p>
            </div>
        </div>
    </div>

    {{-- Timestamps --}}
    <div class="flex flex-col gap-2 rounded-xl border border-slate-200 bg-white px-5 py-4 text-xs text-slate-500 sm:flex-row sm:items-center sm:justify-between">
        <p>
            Dibuat:
            <span class="font-semibold text-slate-700">{{ $candidate->created_at->format('d/m/Y H:i') }}</span>
        </p>
        <p>
            Diperbarui:
            <span class="font-semibold text-slate-700">{{ $candidate->updated_at->format('d/m/Y H:i') }}</span>
        </p>
    </div>

</div>

@endsection
