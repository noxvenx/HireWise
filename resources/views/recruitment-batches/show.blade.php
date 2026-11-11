@extends('layouts.app')

@section('title', 'Detail Recruitment Batch')
@section('page-title', 'Detail Recruitment Batch')

@section('content')

<div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <h1 class="text-2xl font-bold text-slate-800">
            Detail Recruitment Batch
        </h1>
        <p class="text-sm text-slate-500 mt-1">
            Informasi batch dan daftar kandidat yang mengikuti rekrutmen.
        </p>
    </div>

    <div class="flex gap-2">
        <a href="{{ route('recruitment-batches.criteria.index', $recruitmentBatch) }}"
            class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg">
            Kelola Kriteria
        </a>

        <a href="{{ route('recruitment-batches.edit', $recruitmentBatch) }}"
           class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
            Edit Batch
        </a>

        <a href="{{ route('recruitment-batches.index') }}"
           class="bg-slate-200 hover:bg-slate-300 text-slate-700 px-4 py-2 rounded-lg">
            Kembali
        </a>
    </div>
</div>

{{-- INFORMASI BATCH --}}
<div class="bg-white rounded-xl shadow-sm border border-slate-100 p-6 mb-6">
    <h2 class="text-lg font-semibold text-slate-800 mb-4">
        Informasi Recruitment
    </h2>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
        <div>
            <p class="text-sm text-slate-500">Kode Batch</p>
            <p class="font-semibold">{{ $recruitmentBatch->batch_code }}</p>
        </div>

        <div>
            <p class="text-sm text-slate-500">Nama Batch</p>
            <p class="font-semibold">{{ $recruitmentBatch->name }}</p>
        </div>

        <div>
            <p class="text-sm text-slate-500">Posisi</p>
            <p class="font-semibold">{{ $recruitmentBatch->position }}</p>
        </div>

        <div>
            <p class="text-sm text-slate-500">Kuota</p>
            <p class="font-semibold">{{ $recruitmentBatch->quota ?? '-' }}</p>
        </div>

        <div>
            <p class="text-sm text-slate-500">Periode</p>
            <p class="font-semibold">
                {{ $recruitmentBatch->start_date?->format('d/m/Y') ?? '-' }}
                -
                {{ $recruitmentBatch->end_date?->format('d/m/Y') ?? '-' }}
            </p>
        </div>

        <div>
            <p class="text-sm text-slate-500">Status</p>
            <p class="font-semibold">{{ ucfirst($recruitmentBatch->status) }}</p>
        </div>

        <div class="md:col-span-2 lg:col-span-3">
            <p class="text-sm text-slate-500">Deskripsi</p>
            <p>{{ $recruitmentBatch->description ?: '-' }}</p>
        </div>
    </div>
</div>

{{-- TAMBAH KANDIDAT --}}
<div class="bg-white rounded-xl shadow-sm border border-slate-100 p-6 mb-6">
    <h2 class="text-lg font-semibold text-slate-800 mb-1">
        Tambah Kandidat
    </h2>

    <p class="text-sm text-slate-500 mb-5">
        Pilih kandidat dari master data untuk dimasukkan ke batch ini.
    </p>

    @if($availableCandidates->isNotEmpty())
        <form action="{{ route('recruitment-batches.applications.store', $recruitmentBatch) }}"
              method="POST">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-slate-700 mb-2">
                        Pilih Kandidat
                    </label>

                    <select name="candidate_id"
                            required
                            class="w-full rounded-lg border border-slate-300 px-3 py-2.5 focus:ring-2 focus:ring-blue-500">
                        <option value="">-- Pilih Kandidat --</option>

                        @foreach($availableCandidates as $candidate)
                            <option value="{{ $candidate->id }}"
                                {{ old('candidate_id') == $candidate->id ? 'selected' : '' }}>
                                {{ $candidate->candidate_code }} -
                                {{ $candidate->full_name }}
                            </option>
                        @endforeach
                    </select>

                    @error('candidate_id')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">
                        Sumber Kandidat
                    </label>

                    <select name="source"
                            class="w-full rounded-lg border border-slate-300 px-3 py-2.5">
                        <option value="">-- Pilih Sumber --</option>
                        <option value="Internal">Internal</option>
                        <option value="Referral">Referral</option>
                        <option value="Job Fair">Job Fair</option>
                        <option value="Other">Lainnya</option>
                    </select>
                </div>
            </div>

            <div class="mt-4">
                <label class="block text-sm font-medium text-slate-700 mb-2">
                    Catatan
                </label>

                <textarea name="notes"
                          rows="2"
                          class="w-full rounded-lg border border-slate-300 px-3 py-2.5"
                          placeholder="Catatan tambahan (opsional)">{{ old('notes') }}</textarea>
            </div>

            <div class="mt-4">
                <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-lg font-medium">
                    + Tambahkan Kandidat
                </button>
            </div>
        </form>
    @else
        <div class="bg-slate-50 border border-slate-200 rounded-lg p-4 text-sm text-slate-600">
            Semua kandidat yang tersedia sudah terdaftar di batch ini,
            atau belum ada data kandidat.
        </div>
    @endif
</div>

{{-- DAFTAR KANDIDAT --}}
<div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden">
    <div class="p-6 border-b border-slate-100">
        <h2 class="text-lg font-semibold text-slate-800">
            Daftar Kandidat
        </h2>
        <p class="text-sm text-slate-500 mt-1">
            Total {{ $recruitmentBatch->applications->count() }} kandidat terdaftar.
        </p>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead class="bg-slate-50 text-slate-600">
                <tr>
                    <th class="px-6 py-4">No</th>
                    <th class="px-6 py-4">Kode Lamaran</th>
                    <th class="px-6 py-4">Nama Kandidat</th>
                    <th class="px-6 py-4">Email</th>
                    <th class="px-6 py-4">Tanggal Daftar</th>
                    <th class="px-6 py-4">Sumber</th>
                    <th class="px-6 py-4">Status Screening</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-slate-100">
                @forelse($recruitmentBatch->applications as $index => $application)
                    <tr class="hover:bg-slate-50">
                        <td class="px-6 py-4">{{ $index + 1 }}</td>

                        <td class="px-6 py-4 font-medium">
                            {{ $application->application_code }}
                        </td>

                        <td class="px-6 py-4">
                            {{ $application->candidate->full_name }}
                        </td>

                        <td class="px-6 py-4">
                            {{ $application->candidate->email ?? '-' }}
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ $application->applied_at?->format('d/m/Y') ?? '-' }}
                        </td>

                        <td class="px-6 py-4">
                            {{ $application->source ?? '-' }}
                        </td>

                        <td class="px-6 py-4">
                            <span class="px-3 py-1 rounded-full text-xs bg-amber-100 text-amber-700">
                                Belum Screening
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center px-6 py-10 text-slate-400">
                            Belum ada kandidat yang terdaftar di batch ini.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection