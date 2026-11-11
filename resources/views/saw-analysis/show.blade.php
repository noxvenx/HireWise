@extends('layouts.app')

@section('title', 'Detail Hasil SAW')
@section('page-title', 'Detail Hasil SAW')

@section('content')

@php
    $results = $sawCalculation->results->sortBy('rank');

    $criteria = $sawCalculation->details
        ->unique('criterion_code')
        ->sortBy('criterion_code');

    $detailsByApplication = $sawCalculation->details
        ->groupBy('recruitment_application_id');
@endphp

<div class="space-y-6">

    {{-- HEADER --}}
    <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-center">
        <div>
            <a href="{{ route('saw-analysis.index', ['batch' => $sawCalculation->recruitment_batch_id]) }}"
               class="text-sm font-semibold text-indigo-600 hover:text-indigo-800">
                ← Kembali ke SAW Analysis
            </a>

            <h1 class="mt-3 text-2xl font-bold text-slate-800">
                Hasil Perhitungan SAW
            </h1>

            <p class="mt-1 text-sm text-slate-500">
                {{ $sawCalculation->recruitmentBatch->name }}
                ({{ $sawCalculation->recruitmentBatch->batch_code }})
            </p>
        </div>

        <span class="w-fit rounded-full bg-indigo-100 px-4 py-2 text-sm font-semibold text-indigo-700">
            Versi {{ $sawCalculation->version }}
        </span>
    </div>

    {{-- INFORMASI PERHITUNGAN --}}
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">

        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-sm text-slate-500">Jumlah Kandidat</p>
            <p class="mt-2 text-3xl font-bold text-slate-800">
                {{ $sawCalculation->candidate_count }}
            </p>
        </div>

        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-sm text-slate-500">Total Bobot</p>
            <p class="mt-2 text-3xl font-bold text-slate-800">
                {{ number_format($sawCalculation->total_weight, 2, ',', '.') }}%
            </p>
        </div>

        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-sm text-slate-500">Tanggal Perhitungan</p>
            <p class="mt-2 text-lg font-bold text-slate-800">
                {{ $sawCalculation->calculated_at?->format('d/m/Y H:i') ?? '-' }}
            </p>
            <p class="mt-1 text-xs text-slate-500">
                Oleh {{ $sawCalculation->calculator?->name ?? '-' }}
            </p>
        </div>

    </div>

    {{-- RANKING KANDIDAT --}}
    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
        <div class="border-b border-slate-200 p-5">
            <h2 class="text-lg font-semibold text-slate-800">
                Ranking Kandidat
            </h2>
            <p class="mt-1 text-sm text-slate-500">
                Hasil akhir berdasarkan nilai preferensi SAW.
            </p>
        </div>

        {{-- DESKTOP TABLE --}}
        <div class="hidden lg:block">
            @if($results->isNotEmpty())
                <table class="w-full table-fixed text-left text-sm">
                    <thead class="bg-slate-50 text-xs uppercase tracking-wide text-slate-500">
                        <tr>
                            <th class="w-[15%] px-5 py-4">Peringkat</th>
                            <th class="w-[25%] px-5 py-4">Kode Lamaran</th>
                            <th class="w-[35%] px-5 py-4">Nama Kandidat</th>
                            <th class="w-[25%] px-5 py-4 text-right">Nilai Preferensi</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-100">
                        @foreach($results as $result)
                            <tr class="transition hover:bg-slate-50">
                                <td class="px-5 py-4">
                                    <span class="inline-flex h-9 w-9 items-center justify-center rounded-full font-bold
                                        {{ $result->rank === 1
                                            ? 'bg-yellow-100 text-yellow-700'
                                            : 'bg-slate-100 text-slate-700' }}">
                                        {{ $result->rank }}
                                    </span>
                                </td>

                                <td class="break-words px-5 py-4 font-medium text-slate-700">
                                    {{ $result->application->application_code }}
                                </td>

                                <td class="break-words px-5 py-4 font-semibold text-slate-800">
                                    {{ $result->application->candidate->full_name }}
                                </td>

                                <td class="px-5 py-4 text-right font-bold text-indigo-700">
                                    {{ number_format($result->preference_score, 8) }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="px-6 py-10 text-center text-sm text-slate-500">
                    Belum ada hasil perhitungan.
                </div>
            @endif
        </div>

        {{-- MOBILE & TABLET --}}
        <div class="space-y-3 p-4 lg:hidden">
            @forelse($results as $result)
                <div class="rounded-xl border border-slate-200 p-4">
                    <div class="flex items-start justify-between gap-3">
                        <div class="flex min-w-0 items-center gap-3">
                            <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full font-bold
                                {{ $result->rank === 1
                                    ? 'bg-yellow-100 text-yellow-700'
                                    : 'bg-slate-100 text-slate-700' }}">
                                {{ $result->rank }}
                            </span>

                            <div class="min-w-0">
                                <p class="break-words font-semibold text-slate-800">
                                    {{ $result->application->candidate->full_name }}
                                </p>
                                <p class="mt-1 break-words text-xs text-slate-500">
                                    {{ $result->application->application_code }}
                                </p>
                            </div>
                        </div>

                        @if($result->rank === 1)
                            <span class="shrink-0 rounded-full bg-yellow-100 px-2.5 py-1 text-xs font-semibold text-yellow-700">
                                Teratas
                            </span>
                        @endif
                    </div>

                    <div class="mt-4 border-t border-slate-100 pt-3">
                        <p class="text-xs text-slate-500">Nilai Preferensi</p>
                        <p class="mt-1 text-lg font-bold text-indigo-700">
                            {{ number_format($result->preference_score, 8) }}
                        </p>
                    </div>
                </div>
            @empty
                <div class="py-8 text-center text-sm text-slate-500">
                    Belum ada hasil perhitungan.
                </div>
            @endforelse
        </div>
    </div>

    {{-- MATRIKS NILAI AWAL --}}
    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
        <div class="border-b border-slate-200 p-5">
            <h2 class="text-lg font-semibold text-slate-800">
                Matriks Nilai Awal
            </h2>
            <p class="mt-1 text-sm text-slate-500">
                Nilai kandidat sebelum proses normalisasi.
            </p>
        </div>

        {{-- DESKTOP TABLE --}}
        <div class="hidden lg:block">
            @if($results->isNotEmpty())
                <table class="w-full table-fixed text-sm">
                    <thead class="bg-slate-50 text-slate-600">
                        <tr>
                            <th class="w-[25%] px-4 py-4 text-left">Kandidat</th>

                            @foreach($criteria as $criterion)
                                <th class="px-2 py-4 text-center">
                                    <span class="font-semibold">
                                        {{ $criterion->criterion_code }}
                                    </span>
                                </th>
                            @endforeach
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-100">
                        @foreach($results as $result)
                            @php
                                $candidateDetails = $detailsByApplication->get(
                                    $result->recruitment_application_id,
                                    collect()
                                );
                            @endphp

                            <tr class="hover:bg-slate-50">
                                <td class="break-words px-4 py-3 font-medium text-slate-800">
                                    {{ $result->application->candidate->full_name }}
                                </td>

                                @foreach($criteria as $criterion)
                                    @php
                                        $detail = $candidateDetails->firstWhere(
                                            'criterion_code',
                                            $criterion->criterion_code
                                        );
                                    @endphp

                                    <td class="px-2 py-3 text-center text-slate-700">
                                        {{ $detail ? number_format($detail->raw_score, 2) : '-' }}
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="px-6 py-10 text-center text-sm text-slate-500">
                    Belum ada data matriks nilai awal.
                </div>
            @endif
        </div>

        {{-- MOBILE & TABLET --}}
        <div class="space-y-3 p-4 lg:hidden">
            @forelse($results as $result)
                @php
                    $candidateDetails = $detailsByApplication->get(
                        $result->recruitment_application_id,
                        collect()
                    );
                @endphp

                <div class="rounded-xl border border-slate-200 p-4">
                    <div class="flex items-start gap-3">
                        <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-slate-100 text-xs font-bold text-slate-700">
                            {{ $result->rank }}
                        </span>

                        <div class="min-w-0">
                            <p class="break-words font-semibold text-slate-800">
                                {{ $result->application->candidate->full_name }}
                            </p>
                            <p class="mt-1 text-xs text-slate-500">
                                {{ $result->application->application_code }}
                            </p>
                        </div>
                    </div>

                    <div class="mt-4 grid grid-cols-2 gap-3">
                        @foreach($criteria as $criterion)
                            @php
                                $detail = $candidateDetails->firstWhere(
                                    'criterion_code',
                                    $criterion->criterion_code
                                );
                            @endphp

                            <div class="rounded-lg bg-slate-50 p-3">
                                <p class="text-xs font-semibold text-slate-500">
                                    {{ $criterion->criterion_code }}
                                </p>
                                <p class="mt-1 break-words text-sm font-medium text-slate-800">
                                    {{ $criterion->criterion_name ?? $criterion->name ?? $criterion->criterion_code }}
                                </p>
                                <p class="mt-2 text-sm font-bold text-indigo-700">
                                    {{ $detail ? number_format($detail->raw_score, 2) : '-' }}
                                </p>
                            </div>
                        @endforeach
                    </div>
                </div>
            @empty
                <div class="py-8 text-center text-sm text-slate-500">
                    Belum ada data matriks nilai awal.
                </div>
            @endforelse
        </div>
    </div>

    {{-- DETAIL NORMALISASI DAN PEMBOBOTAN --}}
    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">

        <div class="flex flex-col justify-between gap-3 border-b border-slate-200 p-5 sm:flex-row sm:items-center">
            <div>
                <h2 class="text-lg font-semibold text-slate-800">
                    Detail Normalisasi dan Pembobotan
                </h2>
                <p class="mt-1 text-sm text-slate-500">
                    Buka kandidat untuk melihat rincian perhitungan setiap kriteria.
                </p>
            </div>

            <div class="flex flex-wrap gap-2">
                <button type="button"
                        onclick="toggleAllDetails(true)"
                        class="rounded-lg border border-slate-300 px-3 py-2 text-xs font-semibold text-slate-700 transition hover:bg-slate-50">
                    Buka Semua
                </button>

                <button type="button"
                        onclick="toggleAllDetails(false)"
                        class="rounded-lg border border-slate-300 px-3 py-2 text-xs font-semibold text-slate-700 transition hover:bg-slate-50">
                    Tutup Semua
                </button>
            </div>
        </div>

        <div class="divide-y divide-slate-100">
            @foreach($results as $result)
                @php
                    $candidateDetails = $detailsByApplication->get(
                        $result->recruitment_application_id,
                        collect()
                    )->sortBy('criterion_code');

                    $detailId = 'candidate-detail-' . $result->id;
                @endphp

                <div class="candidate-detail-item">

                    {{-- ACCORDION HEADER --}}
                    <button type="button"
                            onclick="toggleCandidateDetail('{{ $detailId }}')"
                            aria-expanded="false"
                            aria-controls="{{ $detailId }}"
                            class="flex w-full flex-col justify-between gap-3 px-5 py-4 text-left transition hover:bg-slate-50 sm:flex-row sm:items-center">

                        <div class="flex min-w-0 items-center gap-3">
                            <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full font-bold
                                {{ $result->rank === 1
                                    ? 'bg-yellow-100 text-yellow-700'
                                    : 'bg-slate-100 text-slate-700' }}">
                                {{ $result->rank }}
                            </span>

                            <div class="min-w-0">
                                <p class="break-words font-semibold text-slate-800">
                                    {{ $result->application->candidate->full_name }}
                                </p>
                                <p class="mt-0.5 text-xs text-slate-500">
                                    {{ $result->application->application_code }}
                                    · {{ $candidateDetails->count() }} kriteria penilaian
                                </p>
                            </div>
                        </div>

                        <div class="flex items-center justify-between gap-4 sm:justify-end">
                            <div class="text-left sm:text-right">
                                <p class="text-xs text-slate-500">
                                    Nilai Preferensi
                                </p>
                                <p class="font-bold text-indigo-700">
                                    {{ number_format($result->preference_score, 8) }}
                                </p>
                            </div>

                            <svg id="{{ $detailId }}-icon"
                                 class="h-5 w-5 shrink-0 text-slate-400 transition-transform duration-200"
                                 fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round"
                                      stroke-linejoin="round"
                                      stroke-width="2"
                                      d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
                    </button>

                    {{-- ACCORDION CONTENT --}}
                    <div id="{{ $detailId }}" class="hidden bg-slate-50 px-4 pb-5 sm:px-5">

                        {{-- DESKTOP DETAIL TABLE --}}
                        <div class="hidden lg:block overflow-hidden rounded-lg border border-slate-200 bg-white">
                            <table class="w-full table-fixed text-left text-sm">
                                <thead class="bg-slate-100 text-xs text-slate-600">
                                    <tr>
                                        <th class="w-[25%] px-3 py-3">Kriteria</th>
                                        <th class="w-[13%] px-2 py-3 text-center">Atribut</th>
                                        <th class="w-[13%] px-2 py-3 text-right">Nilai Awal</th>
                                        <th class="w-[13%] px-2 py-3 text-right">Bobot</th>
                                        <th class="w-[18%] px-2 py-3 text-right">Normalisasi</th>
                                        <th class="w-[18%] px-2 py-3 text-right">Nilai Terbobot</th>
                                    </tr>
                                </thead>

                                <tbody class="divide-y divide-slate-100">
                                    @foreach($candidateDetails as $detail)
                                        <tr>
                                            <td class="break-words px-3 py-3">
                                                <p class="font-semibold text-slate-800">
                                                    {{ $detail->criterion_code }}
                                                </p>
                                                <p class="mt-1 text-xs text-slate-500">
                                                    {{ $detail->criterion_name }}
                                                </p>
                                            </td>

                                            <td class="px-2 py-3 text-center">
                                                <span class="inline-flex rounded-full px-2 py-1 text-xs font-medium
                                                    {{ $detail->attribute === 'benefit'
                                                        ? 'bg-green-100 text-green-700'
                                                        : 'bg-amber-100 text-amber-700' }}">
                                                    {{ ucfirst($detail->attribute) }}
                                                </span>
                                            </td>

                                            <td class="px-2 py-3 text-right text-slate-700">
                                                {{ number_format($detail->raw_score, 2) }}
                                            </td>

                                            <td class="px-2 py-3 text-right text-slate-700">
                                                {{ number_format($detail->weight, 2) }}%
                                            </td>

                                            <td class="px-2 py-3 text-right text-slate-700">
                                                {{ number_format($detail->normalized_score, 8) }}
                                            </td>

                                            <td class="px-2 py-3 text-right font-semibold text-indigo-700">
                                                {{ number_format($detail->weighted_score, 8) }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        {{-- MOBILE & TABLET DETAIL CARDS --}}
                        <div class="space-y-3 lg:hidden">
                            @foreach($candidateDetails as $detail)
                                <div class="rounded-xl border border-slate-200 bg-white p-4">
                                    <div class="flex items-start justify-between gap-3">
                                        <div class="min-w-0">
                                            <p class="text-xs font-bold text-indigo-600">
                                                {{ $detail->criterion_code }}
                                            </p>
                                            <p class="mt-1 break-words font-semibold text-slate-800">
                                                {{ $detail->criterion_name }}
                                            </p>
                                        </div>

                                        <span class="shrink-0 rounded-full px-2.5 py-1 text-xs font-semibold
                                            {{ $detail->attribute === 'benefit'
                                                ? 'bg-green-100 text-green-700'
                                                : 'bg-amber-100 text-amber-700' }}">
                                            {{ ucfirst($detail->attribute) }}
                                        </span>
                                    </div>

                                    <div class="mt-4 grid grid-cols-2 gap-3">
                                        <div class="rounded-lg bg-slate-50 p-3">
                                            <p class="text-xs text-slate-500">Nilai Awal</p>
                                            <p class="mt-1 font-semibold text-slate-800">
                                                {{ number_format($detail->raw_score, 2) }}
                                            </p>
                                        </div>

                                        <div class="rounded-lg bg-slate-50 p-3">
                                            <p class="text-xs text-slate-500">Bobot</p>
                                            <p class="mt-1 font-semibold text-slate-800">
                                                {{ number_format($detail->weight, 2) }}%
                                            </p>
                                        </div>

                                        <div class="rounded-lg bg-slate-50 p-3">
                                            <p class="text-xs text-slate-500">Normalisasi</p>
                                            <p class="mt-1 break-all text-sm font-semibold text-slate-800">
                                                {{ number_format($detail->normalized_score, 8) }}
                                            </p>
                                        </div>

                                        <div class="rounded-lg bg-indigo-50 p-3">
                                            <p class="text-xs text-indigo-600">Nilai Terbobot</p>
                                            <p class="mt-1 break-all text-sm font-bold text-indigo-700">
                                                {{ number_format($detail->weighted_score, 8) }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                    </div>
                </div>
            @endforeach
        </div>
    </div>

    {{-- CATATAN --}}
    <div class="rounded-xl border border-blue-200 bg-blue-50 p-4 text-sm leading-relaxed text-blue-800">
        <strong>Catatan:</strong>
        Hasil SAW merupakan rekomendasi peringkat berdasarkan kriteria dan bobot
        pada saat perhitungan dilakukan. Keputusan akhir penerimaan kandidat
        tetap berada di tangan HRD.
    </div>

</div>

<script>
    function toggleCandidateDetail(id) {
        const content = document.getElementById(id);
        const icon = document.getElementById(id + '-icon');

        if (!content || !icon) return;

        const shouldOpen = content.classList.contains('hidden');

        content.classList.toggle('hidden', !shouldOpen);
        icon.style.transform = shouldOpen ? 'rotate(180deg)' : 'rotate(0deg)';

        const button = content.previousElementSibling;

        if (button) {
            button.setAttribute('aria-expanded', shouldOpen ? 'true' : 'false');
        }
    }

    function toggleAllDetails(open) {
        document.querySelectorAll('.candidate-detail-item').forEach(item => {
            const content = item.querySelector('div[id^="candidate-detail-"]');

            if (!content) return;

            const icon = document.getElementById(content.id + '-icon');

            content.classList.toggle('hidden', !open);

            if (icon) {
                icon.style.transform = open ? 'rotate(180deg)' : 'rotate(0deg)';
            }

            const button = content.previousElementSibling;

            if (button) {
                button.setAttribute('aria-expanded', open ? 'true' : 'false');
            }
        });
    }
</script>

@endsection
