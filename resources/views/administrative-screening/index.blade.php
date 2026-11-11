@extends('layouts.app')

@section('title', 'Administrative Screening')
@section('page-title', 'Administrative Screening')

@section('content')

<div class="space-y-6">

    {{-- Page Heading --}}
    <div class="flex items-center gap-3">
        <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-blue-50 text-blue-600">
            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5-5H4a1 1 0 0 0-1 1v14a1 1 0 0 0 1 1h16a1 1 0 0 0 1-1V6a1 1 0 0 0-1-1Z"/>
            </svg>
        </div>
        <div>
            <h2 class="text-xl font-bold text-slate-800">Administrative Screening</h2>
            <p class="mt-0.5 text-sm text-slate-500">
                Periksa kelengkapan administrasi dan tentukan kelayakan kandidat.
            </p>
        </div>
    </div>

    {{-- Summary --}}
    @php
        $totalPending = \App\Models\RecruitmentApplication::where('screening_status', 'pending')->count();
        $totalPassed = \App\Models\RecruitmentApplication::where('screening_status', 'passed')->count();
        $totalFailed = \App\Models\RecruitmentApplication::where('screening_status', 'failed')->count();
    @endphp

    <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">

        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-500">Pending</p>
                    <p class="mt-2 text-3xl font-bold text-amber-600">{{ $totalPending }}</p>
                    <p class="mt-1 text-xs text-slate-400">Menunggu pemeriksaan</p>
                </div>
                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-amber-50 text-amber-600">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                        <circle cx="12" cy="12" r="9"/>
                        <path stroke-linecap="round" d="M12 7v5l3 2"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-500">Passed</p>
                    <p class="mt-2 text-3xl font-bold text-emerald-600">{{ $totalPassed }}</p>
                    <p class="mt-1 text-xs text-slate-400">Lolos screening</p>
                </div>
                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m5 12 4 4L19 6"/>
                        <circle cx="12" cy="12" r="9"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-500">Failed</p>
                    <p class="mt-2 text-3xl font-bold text-red-600">{{ $totalFailed }}</p>
                    <p class="mt-1 text-xs text-slate-400">Tidak lolos screening</p>
                </div>
                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-red-50 text-red-600">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                        <path stroke-linecap="round" d="m9 9 6 6m0-6-6 6"/>
                        <circle cx="12" cy="12" r="9"/>
                    </svg>
                </div>
            </div>
        </div>

    </div>

    {{-- Filter --}}
    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">

        <div class="mb-5">
            <h3 class="text-sm font-bold text-slate-800">Filter Kandidat</h3>
            <p class="mt-1 text-xs text-slate-500">
                Saring data berdasarkan batch, status, atau informasi kandidat.
            </p>
        </div>

        <form action="{{ route('administrative-screening.index') }}"
              method="GET"
              class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-4">

            <div>
                <label class="mb-2 block text-xs font-semibold text-slate-600">
                    Recruitment Batch
                </label>
                <select name="batch"
                        class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-700 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
                    <option value="">Semua Batch</option>
                    @foreach($batches as $batch)
                        <option value="{{ $batch->id }}"
                            {{ request('batch') == $batch->id ? 'selected' : '' }}>
                            {{ $batch->batch_code }} - {{ $batch->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="mb-2 block text-xs font-semibold text-slate-600">
                    Status Screening
                </label>
                <select name="status"
                        class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-700 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
                    <option value="">Semua Status</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="passed" {{ request('status') == 'passed' ? 'selected' : '' }}>Passed</option>
                    <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Failed</option>
                </select>
            </div>

            <div>
                <label class="mb-2 block text-xs font-semibold text-slate-600">
                    Cari Kandidat
                </label>
                <input type="text"
                       name="search"
                       value="{{ request('search') }}"
                       placeholder="Nama atau kode kandidat..."
                       class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm text-slate-700 outline-none transition placeholder:text-slate-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
            </div>

            <div class="flex items-end gap-2">
                <button type="submit"
                        class="inline-flex flex-1 items-center justify-center rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-blue-700">
                    Filter
                </button>

                <a href="{{ route('administrative-screening.index') }}"
                   class="inline-flex items-center justify-center rounded-xl border border-slate-300 px-4 py-2.5 text-sm font-semibold text-slate-600 transition hover:bg-slate-50">
                    Reset
                </a>
            </div>

        </form>
    </div>

    
{{-- Candidate List --}}
<div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

    <div class="border-b border-slate-100 px-5 py-4 sm:px-6">
        <h3 class="text-base font-bold text-slate-800">Daftar Kandidat</h3>
        <p class="mt-1 text-xs text-slate-500">
            Total {{ $applications->total() }} data lamaran.
        </p>
    </div>

    {{-- DESKTOP TABLE --}}
    <div class="hidden lg:block">
        @if($applications->isNotEmpty())

            <table class="w-full table-fixed text-left text-sm">

                <thead class="bg-slate-50 text-[10px] font-bold uppercase tracking-wider text-slate-500">
                    <tr>
                        <th class="w-[14%] px-3 py-4 xl:px-5">Kode Lamaran</th>
                        <th class="w-[21%] px-3 py-4 xl:px-5">Kandidat</th>
                        <th class="w-[17%] px-3 py-4 xl:px-5">Recruitment Batch</th>
                        <th class="w-[14%] px-3 py-4 xl:px-5">Status Saat Ini</th>
                        <th class="w-[23%] px-3 py-4 xl:px-5">Hasil Screening</th>
                        <th class="w-[11%] px-3 py-4 xl:px-5">Aksi</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100">

                    @foreach($applications as $application)

                        @php
                            $statusConfig = [
                                'pending' => [
                                    'label' => 'Pending',
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

                            $currentStatus = $statusConfig[$application->screening_status] ?? [
                                'label' => ucfirst($application->screening_status ?? 'Unknown'),
                                'badge' => 'bg-slate-100 text-slate-600 ring-slate-200',
                                'dot' => 'bg-slate-400',
                            ];
                        @endphp

                        <tr class="transition hover:bg-slate-50/80">

                            <td class="break-words px-3 py-4 xl:px-5">
                                <p class="font-bold text-blue-700">
                                    {{ $application->application_code }}
                                </p>
                                <p class="mt-1 text-xs text-slate-400">
                                    {{ $application->applied_at?->format('d/m/Y') ?? '-' }}
                                </p>
                            </td>

                            <td class="break-words px-3 py-4 xl:px-5">
                                <p class="font-semibold text-slate-800">
                                    {{ $application->candidate->full_name }}
                                </p>
                                <p class="mt-1 text-xs text-slate-500">
                                    {{ $application->candidate->candidate_code }}
                                </p>
                                <p class="mt-1 break-all text-xs text-slate-500">
                                    {{ $application->candidate->email ?? '-' }}
                                </p>
                            </td>

                            <td class="break-words px-3 py-4 xl:px-5">
                                <p class="font-semibold text-slate-800">
                                    {{ $application->recruitmentBatch->name }}
                                </p>
                                <p class="mt-1 text-xs text-slate-500">
                                    {{ $application->recruitmentBatch->batch_code }}
                                </p>
                            </td>

                            <td class="px-3 py-4 xl:px-5">
                                <span class="inline-flex items-center gap-1.5 rounded-full px-2 py-1 text-[10px] font-bold ring-1 ring-inset {{ $currentStatus['badge'] }}">
                                    <span class="h-1.5 w-1.5 rounded-full {{ $currentStatus['dot'] }}"></span>
                                    {{ $currentStatus['label'] }}
                                </span>

                                @if($application->screened_at)
                                    <p class="mt-2 text-[11px] text-slate-400">
                                        {{ $application->screened_at->format('d/m/Y H:i') }}
                                    </p>
                                    <p class="mt-1 text-[11px] text-slate-400">
                                        Oleh: {{ $application->screener?->name ?? '-' }}
                                    </p>
                                @endif
                            </td>

                            <td class="px-3 py-4 xl:px-5">
                                <form id="screening-desktop-{{ $application->id }}"
                                      action="{{ route('administrative-screening.update', $application) }}"
                                      method="POST"
                                      class="space-y-2">
                                    @csrf
                                    @method('PATCH')

                                    <select name="screening_status"
                                            required
                                            class="w-full rounded-lg border border-slate-300 bg-white px-2 py-2 text-xs text-slate-700 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
                                        <option value="pending" {{ $application->screening_status == 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="passed" {{ $application->screening_status == 'passed' ? 'selected' : '' }}>Passed</option>
                                        <option value="failed" {{ $application->screening_status == 'failed' ? 'selected' : '' }}>Failed</option>
                                    </select>

                                    <textarea name="screening_notes"
                                              rows="2"
                                              class="w-full rounded-lg border border-slate-300 px-2 py-2 text-xs text-slate-700 outline-none placeholder:text-slate-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                                              placeholder="Catatan pemeriksaan...">{{ $application->screening_notes }}</textarea>
                                </form>
                            </td>

                            <td class="px-3 py-4 xl:px-5">
                                <button type="submit"
                                        form="screening-desktop-{{ $application->id }}"
                                        class="w-full rounded-lg bg-blue-600 px-2 py-2.5 text-[11px] font-semibold text-white transition hover:bg-blue-700">
                                    Simpan
                                </button>
                            </td>

                        </tr>

                    @endforeach

                </tbody>
            </table>

        @else
            <div class="p-10 text-center text-sm text-slate-500">
                Belum ada kandidat yang perlu diperiksa.
            </div>
        @endif
    </div>

    {{-- MOBILE & TABLET ACCORDION --}}
    <div class="lg:hidden">

        @forelse($applications as $application)

            @php
                $statusConfig = [
                    'pending' => [
                        'label' => 'Pending',
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

                $currentStatus = $statusConfig[$application->screening_status] ?? [
                    'label' => ucfirst($application->screening_status ?? 'Unknown'),
                    'badge' => 'bg-slate-100 text-slate-600 ring-slate-200',
                    'dot' => 'bg-slate-400',
                ];
            @endphp

            <div class="border-b border-slate-100 p-4 last:border-b-0">

                <details class="group rounded-xl border border-slate-200 bg-white">

                    <summary class="flex cursor-pointer list-none items-center justify-between gap-3 p-4 [&::-webkit-details-marker]:hidden">

                        <div class="min-w-0 flex-1">
                            <p class="truncate text-sm font-bold text-blue-700">
                                {{ $application->application_code }}
                            </p>

                            <p class="mt-1 truncate text-sm font-semibold text-slate-800">
                                {{ $application->candidate->full_name }}
                            </p>

                            <p class="mt-1 text-xs text-slate-500">
                                {{ $application->candidate->candidate_code }}
                            </p>

                            <span class="mt-2 inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-[10px] font-bold ring-1 ring-inset {{ $currentStatus['badge'] }}">
                                <span class="h-1.5 w-1.5 rounded-full {{ $currentStatus['dot'] }}"></span>
                                {{ $currentStatus['label'] }}
                            </span>
                        </div>

                        <div class="flex shrink-0 items-center gap-2 text-slate-400">
                            <span class="text-xs font-medium text-blue-600 group-open:hidden">Detail</span>
                            <span class="hidden text-xs font-medium text-blue-600 group-open:inline">Tutup</span>

                            <svg class="h-5 w-5 transition-transform group-open:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m6 9 6 6 6-6"/>
                            </svg>
                        </div>

                    </summary>

                    <div class="space-y-4 border-t border-slate-100 p-4">

                        {{-- Candidate Information --}}
                        <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">

                            <div>
                                <p class="text-[11px] font-semibold uppercase tracking-wide text-slate-400">Email</p>
                                <p class="mt-1 break-all text-sm text-slate-700">
                                    {{ $application->candidate->email ?? '-' }}
                                </p>
                            </div>

                            <div>
                                <p class="text-[11px] font-semibold uppercase tracking-wide text-slate-400">Tanggal Lamaran</p>
                                <p class="mt-1 text-sm text-slate-700">
                                    {{ $application->applied_at?->format('d/m/Y') ?? '-' }}
                                </p>
                            </div>

                            <div class="sm:col-span-2">
                                <p class="text-[11px] font-semibold uppercase tracking-wide text-slate-400">Recruitment Batch</p>
                                <p class="mt-1 text-sm font-semibold text-slate-800">
                                    {{ $application->recruitmentBatch->name }}
                                </p>
                                <p class="text-xs text-slate-500">
                                    {{ $application->recruitmentBatch->batch_code }}
                                </p>
                            </div>

                            @if($application->screened_at)
                                <div>
                                    <p class="text-[11px] font-semibold uppercase tracking-wide text-slate-400">Terakhir Diperiksa</p>
                                    <p class="mt-1 text-sm text-slate-700">
                                        {{ $application->screened_at->format('d/m/Y H:i') }}
                                    </p>
                                </div>

                                <div>
                                    <p class="text-[11px] font-semibold uppercase tracking-wide text-slate-400">Diperiksa Oleh</p>
                                    <p class="mt-1 text-sm text-slate-700">
                                        {{ $application->screener?->name ?? '-' }}
                                    </p>
                                </div>
                            @endif

                        </div>

                        {{-- Screening Form --}}
                        <div class="rounded-xl bg-slate-50 p-4">

                            <div class="mb-3">
                                <h4 class="text-sm font-bold text-slate-800">Hasil Screening</h4>
                                <p class="mt-1 text-xs text-slate-500">
                                    Tentukan status dan tambahkan catatan pemeriksaan.
                                </p>
                            </div>

                            <form action="{{ route('administrative-screening.update', $application) }}"
                                  method="POST"
                                  class="space-y-3">
                                @csrf
                                @method('PATCH')

                                <div>
                                    <label class="mb-1.5 block text-xs font-semibold text-slate-600">
                                        Status Screening
                                    </label>

                                    <select name="screening_status"
                                            required
                                            class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-700 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
                                        <option value="pending" {{ $application->screening_status == 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="passed" {{ $application->screening_status == 'passed' ? 'selected' : '' }}>Passed</option>
                                        <option value="failed" {{ $application->screening_status == 'failed' ? 'selected' : '' }}>Failed</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="mb-1.5 block text-xs font-semibold text-slate-600">
                                        Catatan Pemeriksaan
                                    </label>

                                    <textarea name="screening_notes"
                                              rows="3"
                                              class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-700 outline-none placeholder:text-slate-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                                              placeholder="Catatan hasil pemeriksaan...">{{ $application->screening_notes }}</textarea>
                                </div>

                                <button type="submit"
                                        class="inline-flex w-full items-center justify-center rounded-xl bg-blue-600 px-4 py-3 text-sm font-semibold text-white transition hover:bg-blue-700">
                                    Simpan Hasil Screening
                                </button>

                            </form>
                        </div>

                    </div>

                </details>

            </div>

        @empty
            <div class="px-6 py-16 text-center">
                <p class="text-sm font-semibold text-slate-700">
                    Belum ada kandidat yang perlu diperiksa.
                </p>
                <p class="mt-1 text-xs text-slate-500">
                    Data lamaran akan muncul di sini setelah kandidat terdaftar.
                </p>
            </div>
        @endforelse

    </div>

    {{-- Pagination --}}
    <div class="border-t border-slate-100 px-5 py-4 sm:px-6">
        {{ $applications->links() }}
    </div>

</div>

@endsection

