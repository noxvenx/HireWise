@extends('layouts.app')

@section('title', 'Detail Kandidat')
@section('page-title', 'Detail Kandidat')

@section('content')

<div class="max-w-8xl mx-auto space-y-6">

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">

        <div>
            <a href="{{ route('candidates.index') }}"
               class="text-sm text-blue-600 hover:text-blue-800">
                ← Kembali ke Candidate Management
            </a>

            <h2 class="text-xl font-bold text-slate-800 mt-3">
                Detail Kandidat
            </h2>
            <p class="text-sm text-slate-500 mt-1">
                Informasi lengkap kandidat HireWise.
            </p>
        </div>

        <a href="{{ route('candidates.edit', $candidate) }}"
           class="inline-flex items-center justify-center px-4 py-2.5 rounded-lg bg-blue-600 text-white text-sm font-semibold hover:bg-blue-700">
            Edit Kandidat
        </a>

    </div>

    {{-- Profile --}}
    <div class="bg-white border border-slate-200 rounded-xl shadow-sm p-6">

        <div class="flex items-center gap-4">
            <div class="w-14 h-14 rounded-xl bg-blue-100 text-blue-700 flex items-center justify-center text-xl font-bold">
                {{ strtoupper(substr($candidate->full_name, 0, 1)) }}
            </div>

            <div>
                <h3 class="text-lg font-bold text-slate-800">
                    {{ $candidate->full_name }}
                </h3>
                <p class="text-sm text-blue-600 font-medium">
                    {{ $candidate->candidate_code }}
                </p>
            </div>
        </div>

    </div>

    {{-- Personal Information --}}
    <div class="bg-white border border-slate-200 rounded-xl shadow-sm p-6">

        <h3 class="font-semibold text-slate-800 mb-5">
            Informasi Identitas
        </h3>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-y-5 gap-x-8">

            <div>
                <p class="text-xs text-slate-500">Email</p>
                <p class="text-sm font-medium text-slate-800 mt-1">
                    {{ $candidate->email ?: '-' }}
                </p>
            </div>

            <div>
                <p class="text-xs text-slate-500">Nomor Telepon</p>
                <p class="text-sm font-medium text-slate-800 mt-1">
                    {{ $candidate->phone ?: '-' }}
                </p>
            </div>

            <div>
                <p class="text-xs text-slate-500">Jenis Kelamin</p>
                <p class="text-sm font-medium text-slate-800 mt-1">
                    {{ $candidate->gender === 'male' ? 'Laki-laki' : ($candidate->gender === 'female' ? 'Perempuan' : '-') }}
                </p>
            </div>

            <div>
                <p class="text-xs text-slate-500">Tanggal Lahir</p>
                <p class="text-sm font-medium text-slate-800 mt-1">
                    {{ $candidate->birth_date?->format('d/m/Y') ?? '-' }}
                </p>
            </div>

            <div class="md:col-span-2">
                <p class="text-xs text-slate-500">Alamat</p>
                <p class="text-sm font-medium text-slate-800 mt-1">
                    {{ $candidate->address ?: '-' }}
                </p>
            </div>

        </div>

    </div>

    {{-- Education --}}
    <div class="bg-white border border-slate-200 rounded-xl shadow-sm p-6">

        <h3 class="font-semibold text-slate-800 mb-5">
            Informasi Pendidikan
        </h3>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-y-5 gap-x-8">

            <div>
                <p class="text-xs text-slate-500">Pendidikan Terakhir</p>
                <p class="text-sm font-medium text-slate-800 mt-1">
                    {{ $candidate->education ?: '-' }}
                </p>
            </div>

            <div>
                <p class="text-xs text-slate-500">Tahun Lulus</p>
                <p class="text-sm font-medium text-slate-800 mt-1">
                    {{ $candidate->graduation_year ?: '-' }}
                </p>
            </div>

            <div>
                <p class="text-xs text-slate-500">Institusi</p>
                <p class="text-sm font-medium text-slate-800 mt-1">
                    {{ $candidate->institution ?: '-' }}
                </p>
            </div>

            <div>
                <p class="text-xs text-slate-500">Jurusan</p>
                <p class="text-sm font-medium text-slate-800 mt-1">
                    {{ $candidate->major ?: '-' }}
                </p>
            </div>

        </div>

    </div>

    {{-- HRD Notes --}}
    <div class="bg-white border border-slate-200 rounded-xl shadow-sm p-6">

        <h3 class="font-semibold text-slate-800 mb-3">
            Catatan HRD
        </h3>

        <p class="text-sm text-slate-600 whitespace-pre-line">
            {{ $candidate->notes ?: 'Belum ada catatan.' }}
        </p>

    </div>

    <div class="text-xs text-slate-400">
        Dibuat: {{ $candidate->created_at->format('d/m/Y H:i') }}
        <span class="mx-2">•</span>
        Diperbarui: {{ $candidate->updated_at->format('d/m/Y H:i') }}
    </div>

</div>

@endsection
