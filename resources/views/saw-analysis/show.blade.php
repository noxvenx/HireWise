@extends('layouts.app')

@section('title', 'Detail Hasil SAW')

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

    {{-- Header --}}
    <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-center">
        <div>
            <a href="{{ route('saw-analysis.index', ['batch' => $sawCalculation->recruitment_batch_id]) }}"
                class="text-sm font-medium text-indigo-600 hover:text-indigo-800">
                &larr; Kembali ke SAW Analysis
            </a>

            <h1 class="mt-3 text-2xl font-bold text-gray-800">
                Hasil Perhitungan SAW
            </h1>

            <p class="mt-1 text-sm text-gray-500">
                {{ $sawCalculation->recruitmentBatch->name }}
                ({{ $sawCalculation->recruitmentBatch->batch_code }})
            </p>
        </div>

        <span class="w-fit rounded-full bg-indigo-100 px-4 py-2 text-sm font-semibold text-indigo-700">
            Versi {{ $sawCalculation->version }}
        </span>
    </div>

    {{-- Informasi Perhitungan --}}
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">

        <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
            <p class="text-sm text-gray-500">Jumlah Kandidat</p>
            <p class="mt-2 text-3xl font-bold text-gray-800">
                {{ $sawCalculation->candidate_count }}
            </p>
        </div>

        <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
            <p class="text-sm text-gray-500">Total Bobot</p>
            <p class="mt-2 text-3xl font-bold text-gray-800">
                {{ number_format($sawCalculation->total_weight, 2) }}%
            </p>
        </div>

        <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
            <p class="text-sm text-gray-500">Tanggal Perhitungan</p>
            <p class="mt-2 text-lg font-bold text-gray-800">
                {{ $sawCalculation->calculated_at?->format('d/m/Y H:i') ?? '-' }}
            </p>
            <p class="mt-1 text-xs text-gray-500">
                Oleh {{ $sawCalculation->calculator?->name ?? '-' }}
            </p>
        </div>

    </div>

    {{-- Ranking --}}
    <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">

        <div class="border-b border-gray-200 p-5">
            <h2 class="font-bold text-gray-800">Ranking Kandidat</h2>
            <p class="mt-1 text-sm text-gray-500">
                Hasil akhir berdasarkan nilai preferensi SAW.
            </p>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-gray-50 text-gray-600">
                    <tr>
                        <th class="px-5 py-3">Peringkat</th>
                        <th class="px-5 py-3">Kode Lamaran</th>
                        <th class="px-5 py-3">Nama Kandidat</th>
                        <th class="px-5 py-3 text-right">Nilai Preferensi</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100">
                    @forelse($results as $result)
                        <tr>
                            <td class="px-5 py-3">
                                <span class="inline-flex h-8 w-8 items-center justify-center rounded-full
                                    {{ $result->rank === 1
                                        ? 'bg-yellow-100 text-yellow-700'
                                        : 'bg-gray-100 text-gray-700' }} font-bold">
                                    {{ $result->rank }}
                                </span>
                            </td>

                            <td class="px-5 py-3 font-medium text-gray-700">
                                {{ $result->application->application_code }}
                            </td>

                            <td class="px-5 py-3 font-semibold text-gray-800">
                                {{ $result->application->candidate->full_name }}
                            </td>

                            <td class="px-5 py-3 text-right font-bold text-indigo-700">
                                {{ number_format($result->preference_score, 8) }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-5 py-8 text-center text-gray-500">
                                Belum ada hasil perhitungan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Matriks Nilai Awal --}}
    <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">

        <div class="border-b border-gray-200 p-5">
            <h2 class="font-bold text-gray-800">Matriks Nilai Awal</h2>
            <p class="mt-1 text-sm text-gray-500">
                Nilai kandidat sebelum proses normalisasi.
            </p>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-gray-50 text-gray-600">
                    <tr>
                        <th class="px-5 py-3">Kandidat</th>

                        @foreach($criteria as $criterion)
                            <th class="px-5 py-3 text-center">
                                {{ $criterion->criterion_code }}
                            </th>
                        @endforeach
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100">
                    @foreach($results as $result)
                        @php
                            $candidateDetails = $detailsByApplication->get(
                                $result->recruitment_application_id,
                                collect()
                            );
                        @endphp

                        <tr>
                            <td class="px-5 py-3 font-medium text-gray-800">
                                {{ $result->application->candidate->full_name }}
                            </td>

                            @foreach($criteria as $criterion)
                                @php
                                    $detail = $candidateDetails->firstWhere(
                                        'criterion_code',
                                        $criterion->criterion_code
                                    );
                                @endphp

                                <td class="px-5 py-3 text-center">
                                    {{ $detail ? number_format($detail->raw_score, 2) : '-' }}
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>


    {{-- Detail Normalisasi dan Pembobotan --}}
    <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">

        <div class="flex flex-col justify-between gap-3 border-b border-gray-200 p-5 sm:flex-row sm:items-center">
            <div>
                <h2 class="font-bold text-gray-800">
                    Detail Normalisasi dan Pembobotan
                </h2>
                <p class="mt-1 text-sm text-gray-500">
                    Buka kandidat untuk melihat rincian perhitungan setiap kriteria.
                </p>
            </div>

            <div class="flex gap-2">
                <button type="button"
                    onclick="toggleAllDetails(true)"
                    class="rounded-lg border border-gray-300 px-3 py-2 text-xs font-semibold text-gray-700 hover:bg-gray-50">
                    Buka Semua
                </button>

                <button type="button"
                    onclick="toggleAllDetails(false)"
                    class="rounded-lg border border-gray-300 px-3 py-2 text-xs font-semibold text-gray-700 hover:bg-gray-50">
                    Tutup Semua
                </button>
            </div>
        </div>

        <div class="divide-y divide-gray-100">

            @foreach($results as $result)
                @php
                    $candidateDetails = $detailsByApplication->get(
                        $result->recruitment_application_id,
                        collect()
                    )->sortBy('criterion_code');

                    $detailId = 'candidate-detail-' . $result->id;
                @endphp

                <div class="candidate-detail-item">

                    {{-- Accordion Header --}}
                    <button type="button"
                        onclick="toggleCandidateDetail('{{ $detailId }}')"
                        class="flex w-full flex-col justify-between gap-3 px-5 py-4 text-left transition hover:bg-gray-50 sm:flex-row sm:items-center">

                        <div class="flex items-center gap-3">
                            <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full
                                {{ $result->rank === 1
                                    ? 'bg-yellow-100 text-yellow-700'
                                    : 'bg-gray-100 text-gray-700' }} font-bold">
                                {{ $result->rank }}
                            </span>

                            <div>
                                <p class="font-semibold text-gray-800">
                                    {{ $result->application->candidate->full_name }}
                                </p>
                                <p class="mt-0.5 text-xs text-gray-500">
                                    {{ $result->application->application_code }}
                                    · {{ $candidateDetails->count() }} kriteria penilaian
                                </p>
                            </div>
                        </div>

                        <div class="flex items-center justify-between gap-4 sm:justify-end">
                            <div class="text-left sm:text-right">
                                <p class="text-xs text-gray-500">
                                    Nilai Preferensi
                                </p>
                                <p class="font-bold text-indigo-700">
                                    {{ number_format($result->preference_score, 8) }}
                                </p>
                            </div>

                            <svg id="{{ $detailId }}-icon"
                                class="h-5 w-5 shrink-0 text-gray-500 transition-transform duration-200"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>

                    </button>

                    {{-- Accordion Content --}}
                    <div id="{{ $detailId }}" class="hidden bg-gray-50 px-5 pb-5">

                        <div class="overflow-x-auto rounded-lg border border-gray-200 bg-white">
                            <table class="w-full text-left text-sm">

                                <thead class="bg-gray-100 text-gray-600">
                                    <tr>
                                        <th class="px-4 py-3">Kriteria</th>
                                        <th class="px-4 py-3 text-center">Atribut</th>
                                        <th class="px-4 py-3 text-right">Nilai Awal</th>
                                        <th class="px-4 py-3 text-right">Bobot</th>
                                        <th class="px-4 py-3 text-right">Normalisasi</th>
                                        <th class="px-4 py-3 text-right">Nilai Terbobot</th>
                                    </tr>
                                </thead>

                                <tbody class="divide-y divide-gray-100">
                                    @foreach($candidateDetails as $detail)
                                        <tr>
                                            <td class="px-4 py-3">
                                                <p class="font-semibold text-gray-800">
                                                    {{ $detail->criterion_code }}
                                                </p>
                                                <p class="text-xs text-gray-500">
                                                    {{ $detail->criterion_name }}
                                                </p>
                                            </td>

                                            <td class="px-4 py-3 text-center">
                                                <span class="rounded-full px-2 py-1 text-xs font-medium
                                                    {{ $detail->attribute === 'benefit'
                                                        ? 'bg-green-100 text-green-700'
                                                        : 'bg-orange-100 text-orange-700' }}">
                                                    {{ ucfirst($detail->attribute) }}
                                                </span>
                                            </td>

                                            <td class="px-4 py-3 text-right">
                                                {{ number_format($detail->raw_score, 2) }}
                                            </td>

                                            <td class="px-4 py-3 text-right">
                                                {{ number_format($detail->weight, 2) }}%
                                            </td>

                                            <td class="px-4 py-3 text-right">
                                                {{ number_format($detail->normalized_score, 8) }}
                                            </td>

                                            <td class="px-4 py-3 text-right font-semibold text-indigo-700">
                                                {{ number_format($detail->weighted_score, 8) }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>

                            </table>
                        </div>

                    </div>

                </div>
            @endforeach

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
    }

    function toggleAllDetails(open) {
        document.querySelectorAll('.candidate-detail-item').forEach(item => {
            const content = item.querySelector(
                'div[id^="candidate-detail-"]:not([id$="-icon"])'
            );

            if (!content) return;

            const icon = document.getElementById(content.id + '-icon');

            content.classList.toggle('hidden', !open);

            if (icon) {
                icon.style.transform = open ? 'rotate(180deg)' : 'rotate(0deg)';
            }
        });
    }
    </script>

    <div class="rounded-xl border border-blue-200 bg-blue-50 p-4 text-sm text-blue-800">
        <strong>Catatan:</strong>
        Hasil SAW merupakan rekomendasi peringkat berdasarkan kriteria dan bobot
        pada saat perhitungan dilakukan. Keputusan akhir penerimaan kandidat
        tetap berada di tangan HRD.
    </div>
</div>
@endsection
