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
    <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
        <form action="{{ route('reports.index') }}" method="GET"
              class="grid grid-cols-1 gap-4 md:grid-cols-3 md:items-stretch">

            <div>
                <label class="mb-2 block text-sm font-medium text-gray-700">
                    Recruitment Batch
                </label>
                <select name="batch_id"
                    class="w-full cursor-pointer rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-700 shadow-sm hover:border-indigo-400 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-100">
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
                    class="w-full cursor-pointer rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-700 shadow-sm hover:border-indigo-400 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-100">
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

            <div class="flex flex-col">
                <label class="invisible mb-2 block text-sm font-medium">
                    Tampilkan Data
                </label>
                <button type="submit"
                    class="w-full rounded-lg border border-indigo-600 bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-indigo-700">
                    Tampilkan Data
                </button>
            </div>
        </form>
    </div>

    @if ($selectedBatch)

        @if ($selectedCalculation)

            {{-- Batch Information --}}
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">

                <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
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

                <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                    <p class="text-sm text-gray-500">Versi Perhitungan SAW</p>
                    <p class="mt-2 text-2xl font-bold text-gray-800">
                        #{{ $selectedCalculation->version }}
                    </p>
                    <p class="text-xs text-gray-500">
                        {{ $selectedCalculation->calculated_at?->format('d/m/Y H:i') }}
                    </p>
                </div>

                <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                    <p class="text-sm text-gray-500">Jumlah Kandidat SAW</p>
                    <p class="mt-2 text-2xl font-bold text-gray-800">
                        {{ $selectedCalculation->candidate_count }}
                    </p>
                </div>

            </div>

            {{-- Screening Summary --}}
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">

                <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                    <p class="text-sm text-gray-500">Total Applicants</p>
                    <p class="mt-2 text-2xl font-bold text-gray-800">
                        {{ $screeningSummary['total'] }}
                    </p>
                </div>

                <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                    <p class="text-sm text-gray-500">Pending</p>
                    <p class="mt-2 text-2xl font-bold text-amber-600">
                        {{ $screeningSummary['pending'] }}
                    </p>
                </div>

                <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                    <p class="text-sm text-gray-500">Passed</p>
                    <p class="mt-2 text-2xl font-bold text-green-600">
                        {{ $screeningSummary['passed'] }}
                    </p>
                </div>

                <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                    <p class="text-sm text-gray-500">Failed</p>
                    <p class="mt-2 text-2xl font-bold text-red-600">
                        {{ $screeningSummary['failed'] }}
                    </p>
                </div>

            </div>

            {{-- Export Actions --}}
            <div class="flex flex-wrap justify-end gap-3">
                <a href="{{ route('reports.export-excel', [
                    'batch_id' => $selectedBatch->id,
                    'calculation_id' => $selectedCalculation->id,
                ]) }}"
                   class="inline-flex items-center gap-2 rounded-lg bg-green-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-green-700">
                    Export Excel
                </a>

                <a href="{{ route('reports.export-pdf', [
                    'batch_id' => $selectedBatch->id,
                    'calculation_id' => $selectedCalculation->id,
                ]) }}"
                   class="inline-flex items-center gap-2 rounded-lg bg-red-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-red-700">
                    Export PDF
                </a>
            </div>

            {{-- SAW Ranking --}}
            <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">

                <div class="border-b border-gray-200 px-5 py-4">
                    <h2 class="font-semibold text-gray-800">
                        Hasil Ranking dan Keputusan Kandidat
                    </h2>
                    <p class="mt-1 text-sm text-gray-500">
                        Hasil berdasarkan versi perhitungan SAW yang dipilih.
                    </p>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full min-w-250 text-sm">

                        <thead class="bg-gray-50 text-left text-xs uppercase text-gray-500">
                            <tr>
                                <th class="px-5 py-3">Ranking</th>
                                <th class="px-5 py-3">Kode Aplikasi</th>
                                <th class="px-5 py-3">Nama Kandidat</th>
                                <th class="px-5 py-3">Nilai SAW</th>
                                <th class="px-5 py-3">Rekomendasi Sistem</th>
                                <th class="px-5 py-3">Keputusan HRD</th>
                                <th class="px-5 py-3">Alasan</th>
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
                                @endphp

                                <tr class="align-top hover:bg-gray-50">

                                    <td class="px-5 py-4">
                                        <span class="inline-flex rounded-full bg-indigo-50 px-3 py-1 font-semibold text-indigo-700">
                                            {{ $result->rank }}
                                        </span>
                                    </td>

                                    <td class="px-5 py-4 font-medium text-gray-700">
                                        {{ $application?->application_code ?? '-' }}
                                    </td>

                                    <td class="px-5 py-4">
                                        <div class="font-medium text-gray-800">
                                            {{ $candidate?->full_name ?? '-' }}
                                        </div>
                                        <div class="mt-1 text-xs text-gray-500">
                                            {{ $candidate?->email ?? '-' }}
                                        </div>
                                    </td>

                                    <td class="px-5 py-4 font-semibold text-gray-800">
                                        {{ number_format($result->preference_score, 8, '.', '') }}
                                    </td>

                                    <td class="px-5 py-4">
                                        @if ($recommendation === 'recommended')
                                            <span class="inline-flex rounded-full bg-green-50 px-3 py-1 text-xs font-semibold text-green-700">
                                                Recommended
                                            </span>
                                        @elseif ($recommendation === 'reserve')
                                            <span class="inline-flex rounded-full bg-amber-50 px-3 py-1 text-xs font-semibold text-amber-700">
                                                Reserve
                                            </span>
                                        @else
                                            <span class="inline-flex rounded-full bg-gray-100 px-3 py-1 text-xs font-semibold text-gray-600">
                                                Not Recommended
                                            </span>
                                        @endif
                                    </td>

                                    <td class="px-5 py-4">
                                        @if ($decision)
                                            @php
                                                $decisionColor = match ($decision->decision_status) {
                                                    'accepted' => 'bg-green-50 text-green-700',
                                                    'rejected' => 'bg-red-50 text-red-700',
                                                    'recommended' => 'bg-indigo-50 text-indigo-700',
                                                    'reserve' => 'bg-amber-50 text-amber-700',
                                                    default => 'bg-gray-100 text-gray-600',
                                                };
                                            @endphp

                                            <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $decisionColor }}">
                                                {{ $decisionLabels[$decision->decision_status] ?? $decision->decision_status }}
                                            </span>
                                        @else
                                            <span class="inline-flex rounded-full bg-gray-100 px-3 py-1 text-xs font-semibold text-gray-600">
                                                Belum diputuskan
                                            </span>
                                        @endif
                                    </td>

                                    <td class="px-5 py-4 text-gray-600">
                                        {{ $decision?->reason ?? '-' }}
                                    </td>

                                </tr>

                            @empty

                                <tr>
                                    <td colspan="7" class="px-5 py-10 text-center text-gray-500">
                                        Belum ada data kandidat pada perhitungan ini.
                                    </td>
                                </tr>

                            @endforelse

                        </tbody>
                    </table>
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

    @endif

</div>
@endsection
