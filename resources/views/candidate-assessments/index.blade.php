
@extends('layouts.app')

@section('title', 'Candidate Assessment')
@section('page-title', 'Candidate Assessment')

@section('content')

{{-- HEADER --}}
<div class="mb-6">
    <h1 class="text-2xl font-bold text-slate-800">Candidate Assessment</h1>
    <p class="mt-1 text-sm text-slate-500">
        Pengisian nilai kandidat berdasarkan kriteria Recruitment Batch.
    </p>
</div>

{{-- FILTER BATCH --}}
<div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
    <form method="GET" action="{{ route('candidate-assessments.index') }}">
        <label class="mb-2 block text-sm font-medium text-slate-700">
            Pilih Recruitment Batch
        </label>

        <div class="flex flex-col gap-3 sm:flex-row">
            <select name="batch"
                    class="w-full rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-700 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-100">
                <option value="">-- Pilih Batch --</option>

                @foreach($batches as $batch)
                    <option value="{{ $batch->id }}"
                        @selected(request('batch') == $batch->id)>
                        {{ $batch->batch_code }} - {{ $batch->name }}
                    </option>
                @endforeach
            </select>

            <button type="submit"
                    class="rounded-lg bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-indigo-700">
                Tampilkan
            </button>
        </div>
    </form>
</div>

@if($selectedBatch)

    @php
        $criterionIds = $criteria->pluck('id');

        $completedApplications = $applications->filter(function ($application) use ($criterionIds) {
            return $criterionIds->isNotEmpty()
                && $application->assessments
                    ->whereIn('recruitment_criterion_id', $criterionIds)
                    ->count() === $criterionIds->count();
        });

        $completedCount = $completedApplications->count();
        $incompleteCount = $applications->count() - $completedCount;
    @endphp

    {{-- INFORMASI BATCH --}}
    <div class="mt-6 rounded-xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
        <div>
            <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                Recruitment Batch Terpilih
            </p>

            <h2 class="mt-1 text-xl font-bold text-slate-800">
                {{ $selectedBatch->name }}
            </h2>

            <p class="mt-1 text-sm text-slate-500">
                Posisi: {{ $selectedBatch->position }}
            </p>
        </div>

        {{-- RINGKASAN --}}
        <div class="mt-5 grid grid-cols-1 gap-4 sm:grid-cols-3">
            <div class="rounded-xl border border-slate-100 bg-slate-50 p-4">
                <p class="text-sm text-slate-500">Kandidat Lolos Screening</p>
                <p class="mt-2 text-2xl font-bold text-slate-800">
                    {{ $applications->count() }}
                </p>
            </div>

            <div class="rounded-xl border border-slate-100 bg-slate-50 p-4">
                <p class="text-sm text-slate-500">Jumlah Kriteria Aktif</p>
                <p class="mt-2 text-2xl font-bold text-slate-800">
                    {{ $criteria->count() }}
                </p>
            </div>

            <div class="rounded-xl border border-slate-100 bg-slate-50 p-4">
                <p class="text-sm text-slate-500">Progres Penilaian</p>
                <p class="mt-2 text-2xl font-bold text-indigo-600">
                    {{ $completedCount }}/{{ $applications->count() }}
                </p>
                <p class="mt-1 text-xs text-slate-500">
                    {{ $completedCount }} lengkap · {{ $incompleteCount }} belum lengkap
                </p>
            </div>
        </div>
    </div>

    {{-- DAFTAR KRITERIA --}}
    <div class="mt-6 rounded-xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
        <div class="mb-4">
            <h3 class="text-lg font-semibold text-slate-800">Daftar Kriteria</h3>
            <p class="mt-1 text-sm text-slate-500">
                Kriteria aktif yang digunakan untuk menilai kandidat.
            </p>
        </div>

        @forelse($criteria as $criterion)
            <div class="mb-3 flex flex-col gap-2 rounded-lg border border-slate-200 p-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <p class="font-semibold text-slate-800">
                        {{ $criterion->criterion_code }} - {{ $criterion->name }}
                    </p>

                    <span class="mt-1 inline-flex rounded-full px-2.5 py-1 text-xs font-semibold
                        {{ $criterion->attribute === 'benefit'
                            ? 'bg-green-100 text-green-700'
                            : 'bg-amber-100 text-amber-700' }}">
                        {{ ucfirst($criterion->attribute) }}
                    </span>
                </div>

                <div class="text-sm font-bold text-slate-700">
                    {{ number_format((float) $criterion->weight, 2, ',', '.') }}%
                </div>
            </div>
        @empty
            <div class="rounded-lg bg-amber-50 p-4 text-sm text-amber-700">
                Belum ada kriteria aktif pada batch ini.
            </div>
        @endforelse
    </div>

    {{-- MATRIKS PENILAIAN --}}
    <div class="mt-6 overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
        <div class="border-b border-slate-200 p-5">
            <h3 class="text-lg font-semibold text-slate-800">
                Matriks Penilaian Kandidat
            </h3>
            <p class="mt-1 text-sm text-slate-500">
                Masukkan nilai setiap kandidat berdasarkan kriteria yang tersedia.
            </p>
        </div>

        @if($applications->isNotEmpty() && $criteria->isNotEmpty())

            {{-- DESKTOP TABLE --}}
            <div class="hidden lg:block">
                <form method="POST"
                      action="{{ route('candidate-assessments.store', $selectedBatch) }}">
                    @csrf

                    <div class="p-4">
                        <table class="w-full table-fixed text-left text-sm">
                            <thead class="bg-slate-50 text-xs uppercase tracking-wide text-slate-500">
                                <tr>
                                    <th class="w-[20%] px-3 py-4">Kandidat</th>

                                    @foreach($criteria as $criterion)
                                        <th class="px-2 py-4 text-center">
                                            <div class="break-words font-bold text-slate-700">
                                                {{ $criterion->criterion_code }}
                                            </div>
                                            <div class="mt-1 break-words text-xs font-medium normal-case text-slate-600">
                                                {{ $criterion->name }}
                                            </div>
                                            <div class="mt-1 text-[11px] font-normal normal-case text-slate-400">
                                                {{ ucfirst($criterion->attribute) }} · {{ number_format((float) $criterion->weight, 2) }}%
                                            </div>
                                        </th>
                                    @endforeach

                                    <th class="w-[12%] px-2 py-4 text-center">Progres</th>
                                </tr>
                            </thead>

                            <tbody class="divide-y divide-slate-100">
                                @foreach($applications as $application)
                                    @php
                                        $filledCount = $application->assessments
                                            ->whereIn('recruitment_criterion_id', $criterionIds)
                                            ->count();

                                        $isComplete = $criterionIds->isNotEmpty()
                                            && $filledCount === $criterionIds->count();
                                    @endphp

                                    <tr class="align-top transition hover:bg-slate-50">
                                        <td class="break-words px-3 py-4">
                                            <p class="font-semibold text-slate-800">
                                                {{ $application->candidate->full_name }}
                                            </p>
                                            <p class="mt-1 text-xs text-slate-500">
                                                {{ $application->candidate->candidate_code }}
                                            </p>
                                        </td>

                                        @foreach($criteria as $criterion)
                                            @php
                                                $assessment = $application->assessments
                                                    ->firstWhere('recruitment_criterion_id', $criterion->id);
                                            @endphp

                                            <td class="px-2 py-4">
                                                <input
                                                    type="number"
                                                    name="scores[{{ $application->id }}][{{ $criterion->id }}]"
                                                    value="{{ old(
                                                        'scores.' . $application->id . '.' . $criterion->id,
                                                        $assessment?->score
                                                    ) }}"
                                                    min="{{ $criterion->attribute === 'cost' ? '0.01' : '0' }}"
                                                    step="0.01"
                                                    placeholder="Nilai"
                                                    class="w-full min-w-0 rounded-lg border border-slate-300 px-2 py-2 text-center text-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-100"
                                                >

                                                @error("scores.{$application->id}.{$criterion->id}")
                                                    <p class="mt-1 break-words text-xs text-red-600">
                                                        {{ $message }}
                                                    </p>
                                                @enderror
                                            </td>
                                        @endforeach

                                        <td class="px-2 py-4 text-center">
                                            @if($isComplete)
                                                <span class="inline-flex rounded-full bg-green-100 px-2 py-1 text-xs font-semibold text-green-700">
                                                    Lengkap
                                                </span>
                                            @else
                                                <span class="inline-flex rounded-full bg-amber-100 px-2 py-1 text-xs font-semibold text-amber-700">
                                                    {{ $filledCount }}/{{ $criterionIds->count() }}
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="flex justify-end border-t border-slate-100 p-5">
                        <button type="submit"
                                class="w-full rounded-lg bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-indigo-700 sm:w-auto">
                            Simpan Seluruh Penilaian
                        </button>
                    </div>
                </form>
            </div>

            {{-- MOBILE & TABLET ACCORDION --}}
            <div class="space-y-3 p-4 lg:hidden">
                <form method="POST"
                      action="{{ route('candidate-assessments.store', $selectedBatch) }}">
                    @csrf

                    @foreach($applications as $application)
                        @php
                            $filledCount = $application->assessments
                                ->whereIn('recruitment_criterion_id', $criterionIds)
                                ->count();

                            $isComplete = $criterionIds->isNotEmpty()
                                && $filledCount === $criterionIds->count();
                        @endphp

                        <details class="group mb-3 rounded-xl border border-slate-200 bg-white">
                            <summary class="flex cursor-pointer list-none items-center justify-between gap-3 p-4">
                                <div class="min-w-0">
                                    <p class="truncate font-semibold text-slate-800">
                                        {{ $application->candidate->full_name }}
                                    </p>
                                    <p class="mt-1 text-xs text-slate-500">
                                        {{ $application->candidate->candidate_code }}
                                    </p>

                                    <div class="mt-2">
                                        @if($isComplete)
                                            <span class="inline-flex rounded-full bg-green-100 px-2.5 py-1 text-xs font-semibold text-green-700">
                                                Lengkap
                                            </span>
                                        @else
                                            <span class="inline-flex rounded-full bg-amber-100 px-2.5 py-1 text-xs font-semibold text-amber-700">
                                                {{ $filledCount }}/{{ $criterionIds->count() }} Terisi
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <span class="shrink-0 text-slate-400 transition group-open:rotate-180">
                                    ▼
                                </span>
                            </summary>

                            <div class="space-y-4 border-t border-slate-100 p-4">
                                @foreach($criteria as $criterion)
                                    @php
                                        $assessment = $application->assessments
                                            ->firstWhere('recruitment_criterion_id', $criterion->id);
                                    @endphp

                                    <div>
                                        <div class="mb-2 flex flex-wrap items-center justify-between gap-2">
                                            <div>
                                                <p class="text-sm font-semibold text-slate-800">
                                                    {{ $criterion->criterion_code }} - {{ $criterion->name }}
                                                </p>
                                                <p class="mt-1 text-xs text-slate-500">
                                                    {{ ucfirst($criterion->attribute) }}
                                                    · Bobot {{ number_format((float) $criterion->weight, 2, ',', '.') }}%
                                                </p>
                                            </div>
                                        </div>

                                        <input
                                            type="number"
                                            name="scores[{{ $application->id }}][{{ $criterion->id }}]"
                                            value="{{ old(
                                                'scores.' . $application->id . '.' . $criterion->id,
                                                $assessment?->score
                                            ) }}"
                                            min="{{ $criterion->attribute === 'cost' ? '0.01' : '0' }}"
                                            step="0.01"
                                            placeholder="Masukkan nilai"
                                            class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-100"
                                        >

                                        @error("scores.{$application->id}.{$criterion->id}")
                                            <p class="mt-1 text-xs text-red-600">
                                                {{ $message }}
                                            </p>
                                        @enderror
                                    </div>
                                @endforeach
                            </div>
                        </details>
                    @endforeach

                    <div class="sticky bottom-0 -mx-4 border-t border-slate-200 bg-white p-4">
                        <button type="submit"
                                class="w-full rounded-lg bg-indigo-600 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700">
                            Simpan Seluruh Penilaian
                        </button>
                    </div>
                </form>
            </div>

        @elseif($applications->isEmpty())
            <div class="p-6">
                <div class="rounded-lg bg-amber-50 p-4 text-sm text-amber-700">
                    Belum ada kandidat yang lolos administrative screening.
                </div>
            </div>
        @else
            <div class="p-6">
                <div class="rounded-lg bg-amber-50 p-4 text-sm text-amber-700">
                    Belum ada kriteria aktif untuk batch ini.
                </div>
            </div>
        @endif
    </div>

@else

    {{-- EMPTY STATE: BELUM PILIH BATCH --}}
    <div class="mt-6 rounded-xl border border-dashed border-slate-300 bg-white px-5 py-12 text-center shadow-sm sm:px-8">
        <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-indigo-50">
            <span class="text-2xl text-indigo-600">✎</span>
        </div>

        <h3 class="mt-4 text-lg font-semibold text-slate-800">
            Pilih Recruitment Batch
        </h3>

        <p class="mx-auto mt-2 max-w-md text-sm leading-relaxed text-slate-500">
            Pilih Recruitment Batch terlebih dahulu melalui dropdown di atas,
            kemudian klik Tampilkan untuk melihat daftar kandidat dan mengisi penilaian.
        </p>
    </div>

@endif

@endsection