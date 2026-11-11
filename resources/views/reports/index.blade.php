@extends('layouts.app')

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Recruitment Reports</h1>
        <p class="mt-1 text-sm text-gray-500">
            Laporan hasil seleksi, perhitungan SAW, dan keputusan akhir rekrutmen.
        </p>
    </div>

    {{-- Filter --}}
    <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm sm:p-5">
        <form action="{{ route('reports.index') }}" method="GET"
              class="grid grid-cols-1 gap-4 md:grid-cols-3 md:items-end">

            <div>
                <label class="mb-2 block text-sm font-medium text-gray-700">
                    Recruitment Batch
                </label>
                <select name="batch_id"
                    class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-700 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-100">
                    <option value="">Pilih Batch</option>
                    @foreach ($batches as $batch)
                        <option value="{{ $batch->id }}"
                            {{ $selectedBatch?->id == $batch->id ? 'selected' : '' }}>
                            {{ $batch->batch_code }} - {{ $batch->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="mb-2 block text-sm font-medium text-gray-700">
                    Versi Perhitungan SAW
                </label>
                <select name="calculation_id"
                    class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-700 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-100">
                    <option value="">Versi terbaru</option>
                    @foreach ($calculations as $calculation)
                        <option value="{{ $calculation->id }}"
                            {{ $selectedCalculation?->id == $calculation->id ? 'selected' : '' }}>
                            Versi {{ $calculation->version }}
                            - {{ $calculation->calculated_at?->format('d/m/Y H:i') }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <button type="submit"
                    class="w-full rounded-lg bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-indigo-700">
                    Tampilkan Data
                </button>
            </div>

        </form>
    </div>

    @if ($selectedBatch)

        @if ($selectedCalculation)

            {{-- Batch Information --}}
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">

                <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm sm:p-5">
                    <p class="text-sm text-gray-500">Batch Rekrutmen</p>
                    <p class="mt-2 font-semibold text-gray-800">
                        {{ $selectedBatch->batch_code }}
                    </p>
                    <p class="text-sm text-gray-500">
                        {{ $selectedBatch->name }}
                    </p>
                    <p class="mt-1 text-xs text-gray-500">
                        Posisi: {{ $selectedBatch->position }}
                    </p>
                </div>

                <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm sm:p-5">
                    <p class="text-sm text-gray-500">Versi Perhitungan SAW</p>
                    <p class="mt-2 text-2xl font-bold text-gray-800">
                        #{{ $selectedCalculation->version }}
                    </p>
                    <p class="text-xs text-gray-500">
                        {{ $selectedCalculation->calculated_at?->format('d/m/Y H:i') }}
                    </p>
                </div>

                <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm sm:p-5">
                    <p class="text-sm text-gray-500">Jumlah Kandidat SAW</p>
                    <p class="mt-2 text-2xl font-bold text-gray-800">
                        {{ $selectedCalculation->candidate_count }}
                    </p>
                </div>

            </div>

            {{-- Screening Summary --}}
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">

                <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm sm:p-5">
                    <p class="text-sm text-gray-500">Total Applicants</p>
                    <p class="mt-2 text-2xl font-bold text-gray-800">
                        {{ $screeningSummary['total'] }}
                    </p>
                </div>

                <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm sm:p-5">
                    <p class="text-sm text-gray-500">Pending</p>
                    <p class="mt-2 text-2xl font-bold text-amber-600">
                        {{ $screeningSummary['pending'] }}
                    </p>
                </div>

                <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm sm:p-5">
                    <p class="text-sm text-gray-500">Passed</p>
                    <p class="mt-2 text-2xl font-bold text-green-600">
                        {{ $screeningSummary['passed'] }}
                    </p>
                </div>

                <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm sm:p-5">
                    <p class="text-sm text-gray-500">Failed</p>
                    <p class="mt-2 text-2xl font-bold text-red-600">
                        {{ $screeningSummary['failed'] }}
                    </p>
                </div>

            </div>

            {{-- Export Actions --}}
            <div class="flex flex-col gap-3 sm:flex-row sm:justify-end">

                <a href="{{ route('reports.export-excel', [
                    'batch_id' => $selectedBatch->id,
                    'calculation_id' => $selectedCalculation->id,
                ]) }}"
                   class="inline-flex items-center justify-center gap-2 rounded-lg bg-green-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-green-700">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor"
                         viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              stroke-width="2"
                              d="M12 10v6m0 0l-3-3m3 3l3-3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    Export Excel
                </a>

                <a href="{{ route('reports.export-pdf', [
                    'batch_id' => $selectedBatch->id,
                    'calculation_id' => $selectedCalculation->id,
                ]) }}"
                   class="inline-flex items-center justify-center gap-2 rounded-lg bg-red-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-red-700">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor"
                         viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              stroke-width="2"
                              d="M12 10v6m0 0l-3-3m3 3l3-3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    Export PDF
                </a>

            </div>

            {{-- SAW Ranking --}}
            <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">

                <div class="border-b border-gray-200 px-4 py-4 sm:px-5">
                    <h2 class="font-semibold text-gray-800">
                        Hasil Ranking dan Keputusan Kandidat
                    </h2>
                    <p class="mt-1 text-sm text-gray-500">
                        Hasil berdasarkan versi perhitungan SAW yang dipilih.
                    </p>
                </div>

                {{-- Desktop Table --}}
                <div class="hidden lg:block">

                    <table class="w-full table-fixed text-sm">

                        <thead class="bg-gray-50 text-left text-xs uppercase text-gray-500">
                            <tr>
                                <th class="w-[7%] px-3 py-3">Ranking</th>
                                <th class="w-[13%] px-3 py-3">Kode Aplikasi</th>
                                <th class="w-[18%] px-3 py-3">Nama Kandidat</th>
                                <th class="w-[12%] px-3 py-3">Nilai SAW</th>
                                <th class="w-[15%] px-3 py-3">Rekomendasi Sistem</th>
                                <th class="w-[15%] px-3 py-3">Keputusan HRD</th>
                                <th class="w-[20%] px-3 py-3">Alasan</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-100">

                            @forelse ($selectedCalculation->results->sortBy('rank') as $result)

                                @php
                                    $application = $result->application;
                                    $candidate = $application?->candidate;

                                    $applicationId = $result->recruitment_application_id;

                                    $decision = $latestDecisions->get($applicationId);
                                    $recommendation = $systemRecommendations->get($applicationId);

                                    $decisionLabels = [
                                        'recommended' => 'Recommended',
                                        'reserve' => 'Reserve',
                                        'accepted' => 'Accepted',
                                        'rejected' => 'Rejected',
                                        'pending_decision' => 'Pending Decision',
                                    ];

                                    $decisionColor = match ($decision?->decision_status) {
                                        'accepted' => 'bg-green-50 text-green-700',
                                        'rejected' => 'bg-red-50 text-red-700',
                                        'recommended' => 'bg-indigo-50 text-indigo-700',
                                        'reserve' => 'bg-amber-50 text-amber-700',
                                        default => 'bg-gray-100 text-gray-600',
                                    };
                                @endphp

                                <tr class="align-top transition hover:bg-gray-50">

                                    <td class="px-3 py-4">
                                        <span class="inline-flex rounded-full bg-indigo-50 px-3 py-1 font-semibold text-indigo-700">
                                            {{ $result->rank }}
                                        </span>
                                    </td>

                                    <td class="break-words px-3 py-4 font-medium text-gray-700">
                                        {{ $application?->application_code ?? '-' }}
                                    </td>

                                    <td class="break-words px-3 py-4">
                                        <div class="font-medium text-gray-800">
                                            {{ $candidate?->full_name ?? '-' }}
                                        </div>
                                        <div class="mt-1 break-all text-xs text-gray-500">
                                            {{ $candidate?->email ?? '-' }}
                                        </div>
                                    </td>

                                    <td class="break-all px-3 py-4 font-semibold tabular-nums text-gray-800">
                                        {{ number_format($result->preference_score, 8, '.', '') }}
                                    </td>

                                    <td class="px-3 py-4">
                                        @if ($recommendation === 'recommended')
                                            <span class="inline-flex rounded-full bg-green-50 px-2.5 py-1 text-xs font-semibold text-green-700">
                                                Recommended
                                            </span>
                                        @elseif ($recommendation === 'reserve')
                                            <span class="inline-flex rounded-full bg-amber-50 px-2.5 py-1 text-xs font-semibold text-amber-700">
                                                Reserve
                                            </span>
                                        @else
                                            <span class="inline-flex rounded-full bg-gray-100 px-2.5 py-1 text-xs font-semibold text-gray-600">
                                                Not Recommended
                                            </span>
                                        @endif
                                    </td>

                                    <td class="px-3 py-4">
                                        @if ($decision)
                                            <span class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold {{ $decisionColor }}">
                                                {{ $decisionLabels[$decision->decision_status] ?? $decision->decision_status }}
                                            </span>
                                        @else
                                            <span class="inline-flex rounded-full bg-gray-100 px-2.5 py-1 text-xs font-semibold text-gray-600">
                                                Belum diputuskan
                                            </span>
                                        @endif
                                    </td>

                                    <td class="break-words px-3 py-4 text-gray-600">
                                        {{ $decision?->reason ?? '-' }}
                                    </td>

                                </tr>

                            @empty

                                <tr>
                                    <td colspan="7" class="px-5 py-10 text-center text-sm text-gray-500">
                                        Belum ada data kandidat pada perhitungan ini.
                                    </td>
                                </tr>

                            @endforelse

                        </tbody>
                    </table>

                </div>

                {{-- Mobile / Tablet Cards --}}
                <div class="space-y-3 p-4 lg:hidden">

                    @forelse ($selectedCalculation->results->sortBy('rank') as $result)

                        @php
                            $application = $result->application;
                            $candidate = $application?->candidate;

                            $applicationId = $result->recruitment_application_id;

                            $decision = $latestDecisions->get($applicationId);
                            $recommendation = $systemRecommendations->get($applicationId);

                            $decisionLabels = [
                                'recommended' => 'Recommended',
                                'reserve' => 'Reserve',
                                'accepted' => 'Accepted',
                                'rejected' => 'Rejected',
                                'pending_decision' => 'Pending Decision',
                            ];

                            $decisionColor = match ($decision?->decision_status) {
                                'accepted' => 'bg-green-50 text-green-700',
                                'rejected' => 'bg-red-50 text-red-700',
                                'recommended' => 'bg-indigo-50 text-indigo-700',
                                'reserve' => 'bg-amber-50 text-amber-700',
                                default => 'bg-gray-100 text-gray-600',
                            };
                        @endphp

                        <div class="rounded-xl border border-gray-200 bg-white p-4">

                            {{-- Candidate Identity --}}
                            <div class="flex items-start gap-3">

                                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-indigo-50 font-bold text-indigo-700">
                                    {{ $result->rank }}
                                </div>

                                <div class="min-w-0 flex-1">
                                    <h3 class="break-words font-semibold text-gray-800">
                                        {{ $candidate?->full_name ?? '-' }}
                                    </h3>

                                    <p class="mt-1 break-all text-xs text-gray-500">
                                        {{ $candidate?->email ?? '-' }}
                                    </p>

                                    <p class="mt-1 text-xs font-medium text-gray-600">
                                        {{ $application?->application_code ?? '-' }}
                                    </p>
                                </div>

                            </div>

                            {{-- Score --}}
                            <div class="mt-4 rounded-lg bg-gray-50 p-3">
                                <p class="text-xs text-gray-500">Nilai SAW</p>
                                <p class="mt-1 break-all text-lg font-bold tabular-nums text-gray-800">
                                    {{ number_format($result->preference_score, 8, '.', '') }}
                                </p>
                            </div>

                            {{-- Recommendation and Decision --}}
                            <div class="mt-3 grid grid-cols-1 gap-3 sm:grid-cols-2">

                                <div>
                                    <p class="mb-1.5 text-xs font-medium text-gray-500">
                                        Rekomendasi Sistem
                                    </p>

                                    @if ($recommendation === 'recommended')
                                        <span class="inline-flex rounded-full bg-green-50 px-2.5 py-1 text-xs font-semibold text-green-700">
                                            Recommended
                                        </span>
                                    @elseif ($recommendation === 'reserve')
                                        <span class="inline-flex rounded-full bg-amber-50 px-2.5 py-1 text-xs font-semibold text-amber-700">
                                            Reserve
                                        </span>
                                    @else
                                        <span class="inline-flex rounded-full bg-gray-100 px-2.5 py-1 text-xs font-semibold text-gray-600">
                                            Not Recommended
                                        </span>
                                    @endif
                                </div>

                                <div>
                                    <p class="mb-1.5 text-xs font-medium text-gray-500">
                                        Keputusan HRD
                                    </p>

                                    @if ($decision)
                                        <span class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold {{ $decisionColor }}">
                                            {{ $decisionLabels[$decision->decision_status] ?? $decision->decision_status }}
                                        </span>
                                    @else
                                        <span class="inline-flex rounded-full bg-gray-100 px-2.5 py-1 text-xs font-semibold text-gray-600">
                                            Belum diputuskan
                                        </span>
                                    @endif
                                </div>

                            </div>

                            {{-- Decision Reason --}}
                            <div class="mt-4 border-t border-gray-100 pt-3">
                                <p class="text-xs font-medium text-gray-500">
                                    Alasan Keputusan
                                </p>
                                <p class="mt-1 break-words whitespace-pre-line text-sm leading-relaxed text-gray-700">
                                    {{ $decision?->reason ?? '-' }}
                                </p>
                            </div>

                        </div>

                    @empty

                        <div class="rounded-lg border border-dashed border-gray-300 px-4 py-10 text-center text-sm text-gray-500">
                            Belum ada data kandidat pada perhitungan ini.
                        </div>

                    @endforelse

                </div>

            </div>

        @else

            <div class="rounded-xl border border-yellow-200 bg-yellow-50 p-5 text-sm text-yellow-800">
                Batch ini belum memiliki hasil perhitungan SAW.
                Silakan lakukan perhitungan SAW terlebih dahulu.
            </div>

        @endif

    @elseif ($batches->isEmpty())

        <div class="rounded-xl border border-gray-200 bg-white p-8 text-center shadow-sm">
            <p class="text-sm text-gray-500">
                Belum ada recruitment batch.
            </p>
        </div>

    @else

        <div class="rounded-xl border border-dashed border-gray-300 bg-white p-8 text-center shadow-sm">
            <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-indigo-50 text-indigo-600">
                <svg class="h-6 w-6" fill="none" stroke="currentColor"
                     viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          stroke-width="2"
                          d="M8 7V3m8 4V3M4 11h16M5 5h14a1 1 0 011 1v13a1 1 0 01-1 1H5a1 1 0 01-1-1V6a1 1 0 011-1z"/>
                </svg>
            </div>

            <h3 class="mt-3 font-semibold text-gray-800">
                Pilih Recruitment Batch
            </h3>

            <p class="mt-1 text-sm text-gray-500">
                Pilih batch rekrutmen dan versi perhitungan SAW untuk melihat laporan hasil seleksi.
            </p>
        </div>

    @endif

</div>
@endsection
