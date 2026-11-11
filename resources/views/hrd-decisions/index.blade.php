@extends('layouts.app')

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div>
        <h1 class="text-2xl font-bold text-gray-800">HRD Decisions</h1>
        <p class="mt-1 text-sm text-gray-500">
            Penetapan keputusan akhir kandidat berdasarkan hasil perhitungan SAW.
        </p>
    </div>

    {{-- Flash Message --}}
    @if (session('success'))
        <div class="rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
            {{ session('success') }}
        </div>
    @endif

    {{-- Validation Errors --}}
    @if ($errors->any())
        <div class="rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
            <ul class="list-inside list-disc">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Filter --}}
    <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
        <form action="{{ route('hrd-decisions.index') }}" method="GET"
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
            <label class="invisible mb-2 text-white block text-sm font-medium">
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

            {{-- Information --}}
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                    <p class="text-sm text-gray-500">Batch Rekrutmen</p>
                    <p class="mt-2 font-semibold text-gray-800">
                        {{ $selectedBatch->batch_code }}
                    </p>
                    <p class="text-sm text-gray-500">{{ $selectedBatch->name }}</p>
                </div>

                <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                    <p class="text-sm text-gray-500">Versi Perhitungan</p>
                    <p class="mt-2 text-2xl font-bold text-gray-800">
                        #{{ $selectedCalculation->version }}
                    </p>
                </div>

                <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                    <p class="text-sm text-gray-500">Jumlah Kandidat</p>
                    <p class="mt-2 text-2xl font-bold text-gray-800">
                        {{ $selectedCalculation->candidate_count }}
                    </p>
                </div>
            </div>

            {{-- Candidate Decisions --}}
            <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
                <div class="border-b border-gray-200 px-5 py-4">
                    <h2 class="font-semibold text-gray-800">
                        Daftar Keputusan Kandidat
                    </h2>
                    <p class="mt-1 text-sm text-gray-500">
                        Tetapkan keputusan berdasarkan ranking SAW. Keputusan tetap berada di tangan HRD.
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
                                <th class="px-5 py-3">Aksi</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-100">
                            @forelse ($selectedCalculation->results->sortBy('rank') as $result)
                                @php
                                    $application = $result->application;
                                    $candidate = $application->candidate;
                                    $decision = $latestDecisions->get($application->id);
                                @endphp

                                <tr class="align-top hover:bg-gray-50">
                                    <td class="px-5 py-4">
                                        <span class="inline-flex rounded-full bg-indigo-50 px-3 py-1 font-semibold text-indigo-700">
                                            {{ $result->rank }}
                                        </span>
                                    </td>

                                    <td class="px-5 py-4 font-medium text-gray-700">
                                        {{ $application->application_code }}
                                    </td>

                                    <td class="px-5 py-4">
                                        <div class="font-medium text-gray-800">
                                            {{ $candidate->full_name }}
                                        </div>
                                        <div class="mt-1 text-xs text-gray-500">
                                            {{ $candidate->email }}
                                        </div>
                                    </td>

                                    <td class="px-5 py-4 font-semibold text-gray-800">
                                        {{ number_format($result->preference_score, 8, '.', '') }}
                                    </td>
                                    <td class="px-5 py-4">
                                        @php
                                            $recommendation = $systemRecommendations->get($application->id);
                                        @endphp

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
                                        <form id="decision-form-{{ $application->id }}"
                                              action="{{ route('hrd-decisions.store', [$selectedCalculation, $application]) }}"
                                              method="POST"
                                              class="space-y-3">
                                            @csrf

                                            <select name="decision_status"
                                                    required
                                                    class="w-full rounded-lg border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                                                <option value="pending_decision"
                                                    {{ ($decision?->decision_status ?? 'pending_decision') === 'pending_decision' ? 'selected' : '' }}>
                                                    Pending Decision
                                                </option>
                                                <option value="recommended"
                                                    {{ $decision?->decision_status === 'recommended' ? 'selected' : '' }}>
                                                    Recommended
                                                </option>
                                                <option value="reserve"
                                                    {{ $decision?->decision_status === 'reserve' ? 'selected' : '' }}>
                                                    Reserve
                                                </option>
                                                <option value="accepted"
                                                    {{ $decision?->decision_status === 'accepted' ? 'selected' : '' }}>
                                                    Accepted
                                                </option>
                                                <option value="rejected"
                                                    {{ $decision?->decision_status === 'rejected' ? 'selected' : '' }}>
                                                    Rejected
                                                </option>
                                            </select>
                                    </td>

                                    <td class="px-5 py-4">
                                            <textarea name="reason"
                                                      rows="2"
                                                      maxlength="2000"
                                                      placeholder="Alasan keputusan..."
                                                      class="w-full rounded-lg border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">{{ $decision?->reason }}</textarea>
                                    </td>

                                    <td class="px-5 py-4">
                                            <button type="submit"
                                                    class="rounded-lg bg-indigo-600 px-4 py-2 text-xs font-semibold text-white hover:bg-indigo-700">
                                                Simpan
                                            </button>

                                            @if ($decision)
                                                <div class="mt-2 text-xs text-gray-500">
                                                    Terakhir diperbarui:
                                                    {{ $decision->decided_at?->format('d/m/Y H:i') }}
                                                    @if ($decision->decider)
                                                        <br>{{ $decision->decider->name }}
                                                    @endif
                                                </div>
                                            @endif
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-5 py-10 text-center text-gray-500">
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
    @endif

</div>
@endsection
