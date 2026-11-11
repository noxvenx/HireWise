@extends('layouts.app')

@section('title', 'Detail Recruitment Batch')
@section('page-title', 'Detail Recruitment Batch')

@section('content')

@php
    $statusStyle = [
        'draft' => 'bg-slate-100 text-slate-600 ring-slate-200',
        'active' => 'bg-emerald-50 text-emerald-700 ring-emerald-200',
        'closed' => 'bg-amber-50 text-amber-700 ring-amber-200',
        'completed' => 'bg-blue-50 text-blue-700 ring-blue-200',
    ];

    $screeningStyles = [
        'pending' => [
            'label' => 'Belum Screening',
            'badge' => 'bg-amber-50 text-amber-700 ring-amber-200',
            'dot' => 'bg-amber-500',
        ],
        'passed' => [
            'label' => 'Passed',
            'badge' => 'bg-emerald-50 text-emerald-700 ring-emerald-200',
            'dot' => 'bg-emerald-500',
        ],
        'failed' => [
            'label' => 'Failed',
            'badge' => 'bg-red-50 text-red-700 ring-red-200',
            'dot' => 'bg-red-500',
        ],
    ];
@endphp

{{-- Page Header --}}
<div class="mb-6 flex flex-col gap-4 xl:flex-row xl:items-center xl:justify-between">
    <div>
        <div class="flex items-center gap-2">
            <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-blue-100 text-blue-700">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                    <rect x="3" y="5" width="18" height="16" rx="2"/>
                    <path stroke-linecap="round" d="M7 3v4m10-4v4M3 10h18"/>
                </svg>
            </span>
            <h1 class="text-2xl font-bold tracking-tight text-slate-800">
                Detail Recruitment Batch
            </h1>
        </div>

        <p class="mt-2 text-sm text-slate-500">
            Informasi batch dan daftar kandidat yang mengikuti rekrutmen.
        </p>
    </div>

    <div class="grid grid-cols-1 gap-2 sm:grid-cols-3">
        <a href="{{ route('recruitment-batches.criteria.index', $recruitmentBatch) }}"
           class="inline-flex items-center justify-center gap-2 rounded-xl border border-indigo-200 bg-indigo-50 px-4 py-2.5 text-sm font-semibold text-indigo-700 transition hover:bg-indigo-100">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
            Kelola Kriteria
        </a>

        <a href="{{ route('recruitment-batches.edit', $recruitmentBatch) }}"
           class="inline-flex items-center justify-center gap-2 rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                <path stroke-linecap="round" stroke-linejoin="round" d="m16 4 4 4M4 20l4-.8L19 8a2.8 2.8 0 0 0-4-4L4 16v4z"/>
            </svg>
            Edit Batch
        </a>

        <a href="{{ route('recruitment-batches.index') }}"
           class="inline-flex items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-600 transition hover:bg-slate-50">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7 7-7M3 12h18"/>
            </svg>
            Kembali
        </a>
    </div>
</div>

{{-- Batch Information --}}
<div class="mb-6 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
    <div class="border-b border-slate-100 px-5 py-4 sm:px-6">
        <div class="flex items-center gap-3">
            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-50 text-blue-600">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                    <rect x="3" y="5" width="18" height="16" rx="2"/>
                    <path stroke-linecap="round" d="M7 3v4m10-4v4M3 10h18"/>
                </svg>
            </div>
            <div>
                <h2 class="text-base font-bold text-slate-800">Informasi Recruitment</h2>
                <p class="mt-0.5 text-xs text-slate-500">Detail dan periode pelaksanaan batch.</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-4 p-5 sm:grid-cols-2 sm:p-6 lg:grid-cols-3">
        <div class="rounded-xl bg-slate-50 p-4">
            <p class="text-xs font-medium text-slate-500">Kode Batch</p>
            <p class="mt-2 break-words text-sm font-bold text-blue-700">{{ $recruitmentBatch->batch_code }}</p>
        </div>

        <div class="rounded-xl bg-slate-50 p-4">
            <p class="text-xs font-medium text-slate-500">Nama Batch</p>
            <p class="mt-2 break-words text-sm font-bold text-slate-800">{{ $recruitmentBatch->name }}</p>
        </div>

        <div class="rounded-xl bg-slate-50 p-4">
            <p class="text-xs font-medium text-slate-500">Posisi</p>
            <p class="mt-2 break-words text-sm font-bold text-slate-800">{{ $recruitmentBatch->position }}</p>
        </div>

        <div class="rounded-xl bg-slate-50 p-4">
            <p class="text-xs font-medium text-slate-500">Kuota Penerimaan</p>
            <p class="mt-2 text-sm font-bold text-slate-800">{{ $recruitmentBatch->quota ?? '-' }}</p>
        </div>

        <div class="rounded-xl bg-slate-50 p-4">
            <p class="text-xs font-medium text-slate-500">Periode Mulai</p>
            <p class="mt-2 text-sm font-bold text-slate-800">{{ $recruitmentBatch->start_date?->format('d/m/Y') ?? '-' }}</p>
        </div>

        <div class="rounded-xl bg-slate-50 p-4">
            <p class="text-xs font-medium text-slate-500">Periode Selesai</p>
            <p class="mt-2 text-sm font-bold text-slate-800">{{ $recruitmentBatch->end_date?->format('d/m/Y') ?? '-' }}</p>
        </div>

        <div class="sm:col-span-2 lg:col-span-3">
            <div class="flex flex-wrap items-center justify-between gap-3 rounded-xl border border-slate-200 p-4">
                <div>
                    <p class="text-xs font-medium text-slate-500">Status Recruitment</p>
                    <p class="mt-1 text-sm font-semibold text-slate-800">Status batch saat ini</p>
                </div>

                <span class="inline-flex rounded-full px-3 py-1.5 text-xs font-bold ring-1 ring-inset {{ $statusStyle[$recruitmentBatch->status] ?? 'bg-slate-100 text-slate-600 ring-slate-200' }}">
                    {{ ucfirst($recruitmentBatch->status) }}
                </span>
            </div>
        </div>

        <div class="sm:col-span-2 lg:col-span-3">
            <p class="mb-2 text-xs font-medium text-slate-500">Deskripsi</p>
            <div class="rounded-xl border border-slate-200 bg-white p-4 text-sm leading-relaxed text-slate-600">
                {{ $recruitmentBatch->description ?: '-' }}
            </div>
        </div>
    </div>
</div>

{{-- Add Candidate --}}
<div class="mb-6 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
    <div class="border-b border-slate-100 px-5 py-4 sm:px-6">
        <div class="flex items-center gap-3">
            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14m-7-7h14"/>
                </svg>
            </div>
            <div>
                <h2 class="text-base font-bold text-slate-800">Tambah Kandidat</h2>
                <p class="mt-0.5 text-xs text-slate-500">Pilih kandidat dari master data untuk dimasukkan ke batch ini.</p>
            </div>
        </div>
    </div>

    <div class="p-5 sm:p-6">
        @if($availableCandidates->isNotEmpty())
            <form action="{{ route('recruitment-batches.applications.store', $recruitmentBatch) }}" method="POST">
                @csrf

                <div class="grid grid-cols-1 gap-5 md:grid-cols-3">
                    <div class="md:col-span-2">
                        <label class="mb-2 block text-sm font-semibold text-slate-700">
                            Pilih Kandidat <span class="text-red-500">*</span>
                        </label>

                        <select name="candidate_id" required
                                class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-700 shadow-sm outline-none transition hover:border-slate-400 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10">
                            <option value="">-- Pilih Kandidat --</option>
                            @foreach($availableCandidates as $candidate)
                                <option value="{{ $candidate->id }}" {{ old('candidate_id') == $candidate->id ? 'selected' : '' }}>
                                    {{ $candidate->candidate_code }} - {{ $candidate->full_name }}
                                </option>
                            @endforeach
                        </select>

                        @error('candidate_id')
                            <p class="mt-2 text-xs font-medium text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-semibold text-slate-700">Sumber Kandidat</label>
                        <select name="source"
                                class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-700 shadow-sm outline-none transition hover:border-slate-400 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10">
                            <option value="">-- Pilih Sumber --</option>
                            <option value="Internal" {{ old('source') == 'Internal' ? 'selected' : '' }}>Internal</option>
                            <option value="Referral" {{ old('source') == 'Referral' ? 'selected' : '' }}>Referral</option>
                            <option value="Job Fair" {{ old('source') == 'Job Fair' ? 'selected' : '' }}>Job Fair</option>
                            <option value="Other" {{ old('source') == 'Other' ? 'selected' : '' }}>Lainnya</option>
                        </select>
                    </div>
                </div>

                <div class="mt-5">
                    <label class="mb-2 block text-sm font-semibold text-slate-700">Catatan</label>
                    <textarea name="notes" rows="3"
                              class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm text-slate-700 shadow-sm outline-none transition placeholder:text-slate-400 hover:border-slate-400 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10"
                              placeholder="Catatan tambahan (opsional)">{{ old('notes') }}</textarea>
                </div>

                <div class="mt-5 flex flex-col gap-3 border-t border-slate-100 pt-5 sm:flex-row sm:items-center sm:justify-between">
                    <p class="text-xs text-slate-500">Pastikan kandidat yang dipilih sudah sesuai.</p>
                    <button type="submit"
                            class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 sm:w-auto">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14m-7-7h14"/>
                        </svg>
                        Tambahkan Kandidat
                    </button>
                </div>
            </form>
        @else
            <div class="flex items-start gap-3 rounded-xl border border-amber-200 bg-amber-50 p-4">
                <svg class="mt-0.5 h-5 w-5 shrink-0 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                    <circle cx="12" cy="12" r="9"/>
                    <path stroke-linecap="round" d="M12 8v4m0 4h.01"/>
                </svg>
                <div>
                    <p class="text-sm font-semibold text-amber-800">Tidak ada kandidat tersedia</p>
                    <p class="mt-1 text-xs leading-relaxed text-amber-700">
                        Semua kandidat yang tersedia sudah terdaftar di batch ini, atau belum ada data kandidat.
                    </p>
                </div>
            </div>
        @endif
    </div>
</div>

{{-- Candidate List --}}
<div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
    <div class="flex flex-col gap-2 border-b border-slate-100 px-5 py-4 sm:flex-row sm:items-center sm:justify-between sm:px-6">
        <div>
            <h2 class="text-base font-bold text-slate-800">Daftar Kandidat</h2>
            <p class="mt-1 text-xs text-slate-500">Kandidat yang terdaftar dalam batch rekrutmen ini.</p>
        </div>

        <div class="inline-flex w-fit items-center gap-2 rounded-xl bg-blue-50 px-3 py-2">
            <span class="text-xs font-medium text-blue-600">Total Kandidat</span>
            <span class="text-sm font-bold text-blue-700">{{ $recruitmentBatch->applications->count() }}</span>
        </div>
    </div>

    {{-- DESKTOP TABLE --}}
    <div class="hidden lg:block">
        @if($recruitmentBatch->applications->isNotEmpty())
            <table class="w-full table-fixed text-left text-sm">
                <thead class="bg-slate-50 text-[11px] font-bold uppercase tracking-wider text-slate-500">
                    <tr>
                        <th class="w-[6%] px-3 py-4">No</th>
                        <th class="w-[16%] px-3 py-4">Kode Lamaran</th>
                        <th class="w-[20%] px-3 py-4">Nama Kandidat</th>
                        <th class="w-[22%] px-3 py-4">Email</th>
                        <th class="w-[13%] px-3 py-4">Tanggal Daftar</th>
                        <th class="w-[10%] px-3 py-4">Sumber</th>
                        <th class="w-[13%] px-3 py-4">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($recruitmentBatch->applications as $index => $application)
                        @php
                            $screening = $screeningStyles[$application->screening_status] ?? [
                                'label' => ucfirst($application->screening_status ?? 'Unknown'),
                                'badge' => 'bg-slate-100 text-slate-600 ring-slate-200',
                                'dot' => 'bg-slate-400',
                            ];
                        @endphp

                        <tr class="transition hover:bg-slate-50/80">
                            <td class="px-3 py-4 text-slate-500">{{ $index + 1 }}</td>
                            <td class="break-words px-3 py-4 font-semibold text-blue-700">{{ $application->application_code }}</td>
                            <td class="break-words px-3 py-4 font-semibold text-slate-800">{{ $application->candidate->full_name }}</td>
                            <td class="break-all px-3 py-4 text-slate-500">{{ $application->candidate->email ?? '-' }}</td>
                            <td class="px-3 py-4 text-xs text-slate-600">{{ $application->applied_at?->format('d/m/Y') ?? '-' }}</td>
                            <td class="break-words px-3 py-4 text-slate-600">{{ $application->source ?? '-' }}</td>
                            <td class="px-3 py-4">
                                <span class="inline-flex items-center gap-1.5 rounded-full px-2 py-1 text-[10px] font-bold ring-1 ring-inset {{ $screening['badge'] }}">
                                    <span class="h-1.5 w-1.5 rounded-full {{ $screening['dot'] }}"></span>
                                    {{ $screening['label'] }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="px-6 py-16 text-center">
                <p class="text-sm font-semibold text-slate-700">Belum ada kandidat</p>
                <p class="mt-1 text-xs text-slate-500">Tambahkan kandidat ke batch ini untuk memulai proses rekrutmen.</p>
            </div>
        @endif
    </div>

    {{-- TABLET & MOBILE ACCORDION --}}
    <div class="space-y-3 p-4 lg:hidden">
        @forelse($recruitmentBatch->applications as $index => $application)
            @php
                $screening = $screeningStyles[$application->screening_status] ?? [
                    'label' => ucfirst($application->screening_status ?? 'Unknown'),
                    'badge' => 'bg-slate-100 text-slate-600 ring-slate-200',
                    'dot' => 'bg-slate-400',
                ];
            @endphp

            <div class="overflow-hidden rounded-xl border border-slate-200 bg-white">
                <details class="group">
                    <summary class="flex cursor-pointer list-none items-center justify-between gap-3 p-4">
                        <div class="min-w-0 flex-1">
                            <div class="flex flex-wrap items-center gap-2">
                                <span class="text-xs font-bold text-blue-700">
                                    #{{ $index + 1 }} · {{ $application->application_code }}
                                </span>
                                <span class="inline-flex items-center gap-1 rounded-full px-2 py-0.5 text-[10px] font-bold ring-1 ring-inset {{ $screening['badge'] }}">
                                    <span class="h-1.5 w-1.5 rounded-full {{ $screening['dot'] }}"></span>
                                    {{ $screening['label'] }}
                                </span>
                            </div>

                            <p class="mt-2 truncate text-sm font-bold text-slate-800">
                                {{ $application->candidate->full_name }}
                            </p>
                            <p class="mt-1 truncate text-xs text-slate-500">
                                {{ $application->candidate->email ?? '-' }}
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
                                <p class="text-[11px] text-slate-400">Tanggal Daftar</p>
                                <p class="mt-1 font-semibold text-slate-700">
                                    {{ $application->applied_at?->format('d/m/Y') ?? '-' }}
                                </p>
                            </div>
                            <div>
                                <p class="text-[11px] text-slate-400">Sumber</p>
                                <p class="mt-1 font-semibold text-slate-700">
                                    {{ $application->source ?? '-' }}
                                </p>
                            </div>
                            <div class="col-span-2">
                                <p class="text-[11px] text-slate-400">Email Kandidat</p>
                                <p class="mt-1 break-all font-semibold text-slate-700">
                                    {{ $application->candidate->email ?? '-' }}
                                </p>
                            </div>
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
                <p class="mt-4 text-sm font-semibold text-slate-700">Belum ada kandidat</p>
                <p class="mt-1 text-xs text-slate-500">Tambahkan kandidat ke batch ini untuk memulai proses rekrutmen.</p>
            </div>
        @endforelse
    </div>
</div>

@endsection
