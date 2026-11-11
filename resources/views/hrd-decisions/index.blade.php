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
    <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm sm:p-5">
        <form action="{{ route('hrd-decisions.index') }}" method="GET"
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

            {{-- Information --}}
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">

                <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm sm:p-5">
                    <p class="text-sm text-gray-500">Batch Rekrutmen</p>
                    <p class="mt-2 font-semibold text-gray-800">
                        {{ $selectedBatch->batch_code }}
                    </p>
                    <p class="text-sm text-gray-500">
                        {{ $selectedBatch->name }}
                    </p>
                </div>

                <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm sm:p-5">
                    <p class="text-sm text-gray-500">Versi Perhitungan</p>
                    <p class="mt-2 text-2xl font-bold text-gray-800">
                        #{{ $selectedCalculation->version }}
                    </p>
                </div>

                <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm sm:p-5">
                    <p class="text-sm text-gray-500">Jumlah Kandidat</p>
                    <p class="mt-2 text-2xl font-bold text-gray-800">
                        {{ $selectedCalculation->candidate_count }}
                    </p>
                </div>

            </div>

            {{-- Candidate Decisions --}}
            <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">

                <div class="border-b border-gray-200 px-4 py-4 sm:px-5">
                    <h2 class="font-semibold text-gray-800">
                        Daftar Keputusan Kandidat
                    </h2>
                    <p class="mt-1 text-sm text-gray-500">
                        Tetapkan keputusan berdasarkan ranking SAW. Keputusan tetap berada di tangan HRD.
                    </p>
                </div>

                {{-- Desktop Table --}}
                <div class="hidden lg:block">
                    <table class="w-full table-fixed text-sm">

                        <thead class="bg-gray-50 text-left text-xs uppercase text-gray-500">
                            <tr>
                                <th class="w-[7%] px-3 py-3">Ranking</th>
                                <th class="w-[12%] px-3 py-3">Kode Aplikasi</th>
                                <th class="w-[15%] px-3 py-3">Nama Kandidat</th>
                                <th class="w-[11%] px-3 py-3">Nilai SAW</th>
                                <th class="w-[13%] px-3 py-3">Rekomendasi Sistem</th>
                                <th class="w-[16%] px-3 py-3">Keputusan HRD</th>
                                <th class="w-[17%] px-3 py-3">Alasan</th>
                                <th class="w-[9%] px-3 py-3">Aksi</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-100">

                            @forelse ($selectedCalculation->results->sortBy('rank') as $result)

                                @php
                                    $application = $result->application;
                                    $candidate = $application->candidate;
                                    $decision = $latestDecisions->get($application->id);
                                    $recommendation = $systemRecommendations->get($application->id);
                                    $desktopFormId = 'decision-form-desktop-' . $application->id;
                                @endphp

                                <tr class="align-top transition hover:bg-gray-50">

                                    <td class="px-3 py-4">
                                        <span class="inline-flex rounded-full bg-indigo-50 px-3 py-1 font-semibold text-indigo-700">
                                            {{ $result->rank }}
                                        </span>
                                    </td>

                                    <td class="break-words px-3 py-4 font-medium text-gray-700">
                                        {{ $application->application_code }}
                                    </td>

                                    <td class="break-words px-3 py-4">
                                        <div class="font-medium text-gray-800">
                                            {{ $candidate->full_name }}
                                        </div>
                                        <div class="mt-1 break-all text-xs text-gray-500">
                                            {{ $candidate->email }}
                                        </div>
                                    </td>

                                    <td class="px-3 py-4 font-semibold tabular-nums text-gray-800">
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
                                        <form id="{{ $desktopFormId }}"
                                              action="{{ route('hrd-decisions.store', [$selectedCalculation, $application]) }}"
                                              method="POST">
                                            @csrf
                                        </form>

                                        <select name="decision_status"
                                                form="{{ $desktopFormId }}"
                                                required
                                                class="w-full rounded-lg border border-gray-300 bg-white px-2 py-2 text-xs text-gray-700 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-100">
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

                                    <td class="px-3 py-4">
                                        <textarea name="reason"
                                                  form="{{ $desktopFormId }}"
                                                  rows="3"
                                                  maxlength="2000"
                                                  placeholder="Alasan keputusan..."
                                                  class="w-full rounded-lg border border-gray-300 px-2.5 py-2 text-xs text-gray-700 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-100">{{ $decision?->reason }}</textarea>
                                    </td>

                                    <td class="px-3 py-4">
                                        <button type="submit"
                                                form="{{ $desktopFormId }}"
                                                class="w-full rounded-lg bg-indigo-600 px-3 py-2 text-xs font-semibold text-white transition hover:bg-indigo-700">
                                            Simpan
                                        </button>

                                        @if ($decision)
                                            <div class="mt-2 text-xs leading-relaxed text-gray-500">
                                                <p>
                                                    Diperbarui:
                                                    {{ $decision->decided_at?->format('d/m/Y H:i') }}
                                                </p>
                                                @if ($decision->decider)
                                                    <p class="mt-1 font-medium">
                                                        {{ $decision->decider->name }}
                                                    </p>
                                                @endif
                                            </div>
                                        @endif
                                    </td>

                                </tr>

                            @empty

                                <tr>
                                    <td colspan="8" class="px-5 py-10 text-center text-sm text-gray-500">
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
                            $candidate = $application->candidate;
                            $decision = $latestDecisions->get($application->id);
                            $recommendation = $systemRecommendations->get($application->id);
                            $mobileFormId = 'decision-form-mobile-' . $application->id;
                        @endphp

                        <div class="rounded-xl border border-gray-200 bg-white p-4">

                            {{-- Candidate Identity --}}
                            <div class="flex items-start gap-3">

                                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-indigo-50 font-bold text-indigo-700">
                                    {{ $result->rank }}
                                </div>

                                <div class="min-w-0 flex-1">
                                    <h3 class="break-words font-semibold text-gray-800">
                                        {{ $candidate->full_name }}
                                    </h3>
                                    <p class="mt-1 break-all text-xs text-gray-500">
                                        {{ $candidate->email }}
                                    </p>
                                    <p class="mt-1 text-xs font-medium text-gray-600">
                                        {{ $application->application_code }}
                                    </p>
                                </div>

                            </div>

                            {{-- Score and Recommendation --}}
                            <div class="mt-4 grid grid-cols-2 gap-3">

                                <div class="rounded-lg bg-gray-50 p-3">
                                    <p class="text-xs text-gray-500">Nilai SAW</p>
                                    <p class="mt-1 break-all font-semibold tabular-nums text-gray-800">
                                        {{ number_format($result->preference_score, 8, '.', '') }}
                                    </p>
                                </div>

                                <div class="rounded-lg bg-gray-50 p-3">
                                    <p class="text-xs text-gray-500">Rekomendasi Sistem</p>
                                    <div class="mt-1">
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
                                </div>

                            </div>

                            {{-- Decision Form --}}
                            <div class="mt-4 border-t border-gray-100 pt-4">

                                <form id="{{ $mobileFormId }}"
                                      action="{{ route('hrd-decisions.store', [$selectedCalculation, $application]) }}"
                                      method="POST"
                                      class="space-y-3">
                                    @csrf

                                    <div>
                                        <label class="mb-1.5 block text-sm font-medium text-gray-700">
                                            Keputusan HRD
                                        </label>

                                        <select name="decision_status"
                                                required
                                                class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-700 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-100">
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
                                    </div>

                                    <div>
                                        <label class="mb-1.5 block text-sm font-medium text-gray-700">
                                            Alasan Keputusan
                                        </label>

                                        <textarea name="reason"
                                                  rows="3"
                                                  maxlength="2000"
                                                  placeholder="Alasan keputusan..."
                                                  class="w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm text-gray-700 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-100">{{ $decision?->reason }}</textarea>
                                    </div>

                                    <button type="submit"
                                            class="w-full rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-indigo-700">
                                        Simpan Keputusan
                                    </button>

                                </form>

                                @if ($decision)
                                    <div class="mt-3 rounded-lg bg-gray-50 p-3 text-xs text-gray-500">
                                        <p>
                                            Terakhir diperbarui:
                                            {{ $decision->decided_at?->format('d/m/Y H:i') }}
                                        </p>

                                        @if ($decision->decider)
                                            <p class="mt-1 font-medium text-gray-700">
                                                {{ $decision->decider->name }}
                                            </p>
                                        @endif
                                    </div>
                                @endif

                            </div>

                        </div>

                    @empty

                        <div class="rounded-lg border border-dashed border-gray-300 px-4 py-10 text-center text-sm text-gray-500">
                            Belum ada data kandidat pada perhitungan ini.
                        </div>

                    @endforelse

                </div>

            </div>

            {{-- HRD Information --}}
            <div class="rounded-xl border border-blue-200 bg-blue-50 p-4">
                <div class="flex items-start gap-3">
                    <div class="mt-0.5 text-blue-600">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor"
                             viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  stroke-width="2"
                                  d="M13 16h-1v-4h-1m1-4h.01M12 2a10 10 0 100 20 10 10 0 000-20z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-blue-800">
                            Catatan Keputusan HRD
                        </p>
                        <p class="mt-1 text-sm leading-relaxed text-blue-700">
                            Rekomendasi sistem merupakan hasil pemeringkatan SAW.
                            Keputusan akhir penerimaan kandidat tetap ditentukan oleh HRD.
                        </p>
                    </div>
                </div>
            </div>

        @else

            <div class="rounded-xl border border-yellow-200 bg-yellow-50 p-5 text-sm text-yellow-800">
                Batch ini belum memiliki hasil perhitungan SAW.
                Silakan lakukan perhitungan SAW terlebih dahulu.
            </div>

        @endif

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
                Pilih batch rekrutmen dan versi perhitungan SAW untuk melihat serta menetapkan keputusan kandidat.
            </p>
        </div>

    @endif

</div>
@endsection
