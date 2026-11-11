@extends('layouts.app')

@section('title', 'Candidate Assessment')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-slate-800">Candidate Assessment</h1>
    <p class="mt-1 text-sm text-slate-500">
        Pengisian nilai kandidat berdasarkan kriteria Recruitment Batch.
    </p>
</div>

<div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
    <form method="GET" action="{{ route('candidate-assessments.index') }}">
        <label class="mb-2 block text-sm font-medium text-slate-700">
            Pilih Recruitment Batch
        </label>

        <div class="flex flex-col gap-3 sm:flex-row">
            <select name="batch"
                class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm focus:border-indigo-500 focus:outline-none">
                <option value="">-- Pilih Batch --</option>

                @foreach($batches as $batch)
                    <option value="{{ $batch->id }}"
                        @selected(request('batch') == $batch->id)>
                        {{ $batch->batch_code }} - {{ $batch->name }}
                    </option>
                @endforeach
            </select>

            <button type="submit"
                class="rounded-lg bg-indigo-600 px-5 py-2.5 text-sm font-medium text-white hover:bg-indigo-700">
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
    <div class="mt-6 rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
        <h2 class="text-lg font-semibold text-slate-800">
            {{ $selectedBatch->name }}
        </h2>

        <p class="mt-1 text-sm text-slate-500">
            Posisi: {{ $selectedBatch->position }}
        </p>

        <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-3">
            <div class="rounded-lg bg-slate-50 p-4">
                <p class="text-sm text-slate-500">Kandidat Lolos Screening</p>
                <p class="mt-1 text-2xl font-bold">{{ $applications->count() }}</p>
            </div>

            <div class="rounded-lg bg-slate-50 p-4">
                <p class="text-sm text-slate-500">Jumlah Kriteria Aktif</p>
                <p class="mt-1 text-2xl font-bold">{{ $criteria->count() }}</p>
            </div>
            <div class="rounded-lg bg-slate-50 p-4">
                <p class="text-sm text-slate-500">Progres Penilaian</p>

                <p class="mt-1 text-2xl font-bold text-indigo-600">
                    {{ $completedCount }}/{{ $applications->count() }}
                </p>

                <p class="mt-1 text-xs text-slate-500">
                    {{ $completedCount }} lengkap · {{ $incompleteCount }} belum lengkap
                </p>
            </div>
        </div>

        <div class="mt-6">
            <h3 class="font-semibold text-slate-800">Daftar Kriteria</h3>

            @forelse($criteria as $criterion)
                <div class="mt-2 flex items-center justify-between rounded-lg border border-slate-200 p-3">
                    <div>
                        <p class="font-medium">{{ $criterion->criterion_code }} - {{ $criterion->name }}</p>
                        <p class="text-xs text-slate-500">
                            {{ ucfirst($criterion->attribute) }}
                        </p>
                    </div>

                    <span class="text-sm font-semibold">
                        {{ number_format((float) $criterion->weight, 2) }}%
                    </span>
                </div>
            @empty
                <p class="mt-3 text-sm text-amber-600">
                    Belum ada kriteria aktif pada batch ini.
                </p>
            @endforelse
        </div>

        
        <div class="mt-6">
            <h3 class="font-semibold text-slate-800">
                Matriks Penilaian Kandidat
            </h3>

            @if($applications->isNotEmpty() && $criteria->isNotEmpty())
                <form method="POST"
                    action="{{ route('candidate-assessments.store', $selectedBatch) }}">
                    @csrf

                    <div class="mt-3 overflow-x-auto">
                        <table class="w-full text-left text-sm border-collapse">
                            <thead class="bg-slate-50 text-slate-600">
                                <tr>
                                    <th class="px-4 py-3 whitespace-nowrap">Kandidat</th>

                                    @foreach($criteria as $criterion)
                                        <th class="px-4 py-3 min-w-36">
                                            {{ $criterion->criterion_code }}
                                            <div class="text-xs font-normal">
                                                {{ $criterion->name }}
                                            </div>
                                            <div class="text-xs font-normal">
                                                {{ ucfirst($criterion->attribute) }}
                                            </div>
                                        </th>
                                    @endforeach

                                    <th class="px-4 py-3 whitespace-nowrap">Progres</th>
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

                                    <tr>
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            <div class="font-medium text-slate-800">
                                                {{ $application->candidate->full_name }}
                                            </div>
                                            <div class="text-xs text-slate-500">
                                                {{ $application->candidate->candidate_code }}
                                            </div>
                                        </td>

                                        @foreach($criteria as $criterion)
                                            @php
                                                $assessment = $application->assessments
                                                    ->firstWhere('recruitment_criterion_id', $criterion->id);
                                            @endphp

                                            <td class="px-4 py-3">
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
                                                    class="w-28 rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-indigo-500 focus:outline-none"
                                                >

                                                @error("scores.{$application->id}.{$criterion->id}")
                                                    <p class="mt-1 text-xs text-red-600">
                                                        {{ $message }}
                                                    </p>
                                                @enderror
                                            </td>
                                        @endforeach

                                        <td class="px-4 py-3 whitespace-nowrap">
                                            @if($isComplete)
                                                <span class="rounded-full bg-green-100 px-3 py-1 text-xs font-medium text-green-700">
                                                    Lengkap
                                                </span>
                                            @else
                                                <span class="rounded-full bg-amber-100 px-3 py-1 text-xs font-medium text-amber-700">
                                                    {{ $filledCount }}/{{ $criterionIds->count() }} Terisi
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-5 flex justify-end">
                        <button type="submit"
                            class="rounded-lg bg-indigo-600 px-5 py-2.5 text-sm font-medium text-white hover:bg-indigo-700">
                            Simpan Seluruh Penilaian
                        </button>
                    </div>
                </form>

            @elseif($applications->isEmpty())
                <p class="mt-3 rounded-lg bg-amber-50 p-4 text-sm text-amber-700">
                    Belum ada kandidat yang lolos administrative screening.
                </p>
            @else
                <p class="mt-3 rounded-lg bg-amber-50 p-4 text-sm text-amber-700">
                    Belum ada kriteria aktif untuk batch ini.
                </p>
            @endif
        </div>
    @endif
@endsection