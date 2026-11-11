
@extends('layouts.app')

@section('title', 'SAW Analysis')
@section('page-title', 'SAW Analysis')

@section('content')

<div class="space-y-6">

    {{-- HEADER --}}
    <div>
        <h1 class="text-2xl font-bold text-slate-800">SAW Analysis</h1>
        <p class="mt-1 text-sm text-slate-500">
            Perhitungan Simple Additive Weighting untuk menentukan peringkat kandidat.
        </p>
    </div>

    {{-- PILIH BATCH --}}
    <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
        <form method="GET" action="{{ route('saw-analysis.index') }}">
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
            $passedCount = $selectedBatch->applications()
                ->where('screening_status', 'passed')
                ->count();

            $criteria = $selectedBatch->criteria()
                ->where('is_active', true)
                ->orderBy('criterion_code')
                ->get();

            $totalWeight = (float) $criteria->sum('weight');
            $isWeightValid = abs($totalWeight - 100) <= 0.01;
        @endphp

        {{-- INFORMASI BATCH --}}
        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
            <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-center">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                        Recruitment Batch Terpilih
                    </p>

                    <h2 class="mt-1 text-xl font-bold text-slate-800">
                        {{ $selectedBatch->name }}
                    </h2>

                    <p class="mt-1 text-sm text-slate-500">
                        {{ $selectedBatch->batch_code }}
                        <span class="mx-1">·</span>
                        {{ $selectedBatch->position }}
                    </p>
                </div>

                <form method="POST"
                      action="{{ route('saw-analysis.calculate', $selectedBatch) }}"
                      onsubmit="return confirm('Jalankan perhitungan SAW untuk batch ini?');">
                    @csrf

                    <button type="submit"
                            class="w-full rounded-lg bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-indigo-700 sm:w-auto">
                        Hitung SAW Sekarang
                    </button>
                </form>
            </div>
        </div>

        {{-- RINGKASAN --}}
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">

            <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-sm text-slate-500">Kandidat Lolos Screening</p>
                <p class="mt-2 text-3xl font-bold text-slate-800">
                    {{ $passedCount }}
                </p>
            </div>

            <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-sm text-slate-500">Kriteria Aktif</p>
                <p class="mt-2 text-3xl font-bold text-slate-800">
                    {{ $criteria->count() }}
                </p>
            </div>

            <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-sm text-slate-500">Total Bobot</p>
                <p class="mt-2 text-3xl font-bold {{ $isWeightValid ? 'text-green-600' : 'text-red-600' }}">
                    {{ number_format($totalWeight, 2, ',', '.') }}%
                </p>
                <p class="mt-1 text-xs {{ $isWeightValid ? 'text-green-600' : 'text-red-600' }}">
                    {{ $isWeightValid ? 'Total bobot sudah sesuai.' : 'Total bobot wajib 100%.' }}
                </p>
            </div>

        </div>

        {{-- KRITERIA PENILAIAN --}}
        <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
            <div class="border-b border-slate-200 p-5">
                <h3 class="text-lg font-semibold text-slate-800">
                    Kriteria Penilaian
                </h3>
                <p class="mt-1 text-sm text-slate-500">
                    Kriteria aktif yang digunakan dalam perhitungan batch ini.
                </p>
            </div>

            {{-- DESKTOP TABLE --}}
            <div class="hidden lg:block">
                @if($criteria->isNotEmpty())
                    <table class="w-full table-fixed text-left text-sm">
                        <thead class="bg-slate-50 text-xs uppercase tracking-wide text-slate-500">
                            <tr>
                                <th class="w-[15%] px-5 py-4">Kode</th>
                                <th class="w-[40%] px-5 py-4">Nama Kriteria</th>
                                <th class="w-[25%] px-5 py-4">Atribut</th>
                                <th class="w-[20%] px-5 py-4 text-right">Bobot</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-slate-100">
                            @foreach($criteria as $criterion)
                                <tr class="transition hover:bg-slate-50">
                                    <td class="break-words px-5 py-4 font-semibold text-slate-800">
                                        {{ $criterion->criterion_code }}
                                    </td>

                                    <td class="break-words px-5 py-4 text-slate-700">
                                        {{ $criterion->name }}
                                    </td>

                                    <td class="px-5 py-4">
                                        <span class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold
                                            {{ $criterion->attribute === 'benefit'
                                                ? 'bg-green-100 text-green-700'
                                                : 'bg-amber-100 text-amber-700' }}">
                                            {{ ucfirst($criterion->attribute) }}
                                        </span>
                                    </td>

                                    <td class="px-5 py-4 text-right font-semibold text-slate-800">
                                        {{ number_format((float) $criterion->weight, 2, ',', '.') }}%
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="px-6 py-10 text-center text-sm text-amber-600">
                        Belum ada kriteria aktif.
                    </div>
                @endif
            </div>

            {{-- MOBILE & TABLET --}}
            <div class="space-y-3 p-4 lg:hidden">
                @forelse($criteria as $criterion)
                    <div class="rounded-xl border border-slate-200 p-4">
                        <div class="flex items-start justify-between gap-3">
                            <div class="min-w-0">
                                <p class="text-xs font-bold text-indigo-600">
                                    {{ $criterion->criterion_code }}
                                </p>
                                <p class="mt-1 break-words font-semibold text-slate-800">
                                    {{ $criterion->name }}
                                </p>
                            </div>

                            <p class="shrink-0 text-sm font-bold text-slate-800">
                                {{ number_format((float) $criterion->weight, 2, ',', '.') }}%
                            </p>
                        </div>

                        <div class="mt-3">
                            <span class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold
                                {{ $criterion->attribute === 'benefit'
                                    ? 'bg-green-100 text-green-700'
                                    : 'bg-amber-100 text-amber-700' }}">
                                {{ ucfirst($criterion->attribute) }}
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="py-8 text-center text-sm text-amber-600">
                        Belum ada kriteria aktif.
                    </div>
                @endforelse
            </div>
        </div>

        {{-- RIWAYAT PERHITUNGAN --}}
        <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
            <div class="border-b border-slate-200 p-5">
                <h3 class="text-lg font-semibold text-slate-800">
                    Riwayat Perhitungan SAW
                </h3>
                <p class="mt-1 text-sm text-slate-500">
                    Setiap perhitungan disimpan sebagai versi tersendiri.
                </p>
            </div>

            {{-- DESKTOP TABLE --}}
            <div class="hidden lg:block">
                @if($calculations->isNotEmpty())
                    <table class="w-full table-fixed text-left text-sm">
                        <thead class="bg-slate-50 text-xs uppercase tracking-wide text-slate-500">
                            <tr>
                                <th class="w-[13%] px-4 py-4">Versi</th>
                                <th class="w-[25%] px-4 py-4">Tanggal Perhitungan</th>
                                <th class="w-[17%] px-4 py-4">Jumlah Kandidat</th>
                                <th class="w-[25%] px-4 py-4">Dihitung Oleh</th>
                                <th class="w-[20%] px-4 py-4 text-center">Aksi</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-slate-100">
                            @foreach($calculations as $calculation)
                                <tr class="transition hover:bg-slate-50">
                                    <td class="px-4 py-4 font-semibold text-slate-800">
                                        Versi {{ $calculation->version }}
                                    </td>

                                    <td class="px-4 py-4 text-slate-600">
                                        {{ $calculation->calculated_at?->format('d/m/Y H:i') ?? '-' }}
                                    </td>

                                    <td class="px-4 py-4 text-slate-700">
                                        {{ $calculation->candidate_count }}
                                    </td>

                                    <td class="break-words px-4 py-4 text-slate-700">
                                        {{ $calculation->calculator?->name ?? '-' }}
                                    </td>

                                    <td class="px-4 py-4 text-center">
                                        <a href="{{ route('saw-analysis.show', $calculation) }}"
                                           class="inline-flex items-center rounded-lg bg-indigo-50 px-3 py-2 text-xs font-semibold text-indigo-700 transition hover:bg-indigo-100">
                                            Lihat Detail
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="px-6 py-10 text-center">
                        <p class="font-medium text-slate-600">
                            Belum ada riwayat perhitungan SAW.
                        </p>
                        <p class="mt-1 text-sm text-slate-400">
                            Jalankan perhitungan SAW untuk membuat versi pertama.
                        </p>
                    </div>
                @endif
            </div>

            {{-- MOBILE & TABLET ACCORDION --}}
            <div class="space-y-3 p-4 lg:hidden">
                @forelse($calculations as $calculation)
                    <details class="group rounded-xl border border-slate-200">
                        <summary class="flex cursor-pointer list-none items-center justify-between gap-3 p-4">
                            <div class="min-w-0">
                                <p class="font-semibold text-slate-800">
                                    Versi {{ $calculation->version }}
                                </p>
                                <p class="mt-1 text-xs text-slate-500">
                                    {{ $calculation->calculated_at?->format('d/m/Y H:i') ?? '-' }}
                                </p>
                            </div>

                            <span class="shrink-0 text-slate-400 transition group-open:rotate-180">
                                ▼
                            </span>
                        </summary>

                        <div class="space-y-3 border-t border-slate-100 p-4">
                            <div>
                                <p class="text-xs text-slate-400">Jumlah Kandidat</p>
                                <p class="mt-1 text-sm font-semibold text-slate-800">
                                    {{ $calculation->candidate_count }}
                                </p>
                            </div>

                            <div>
                                <p class="text-xs text-slate-400">Dihitung Oleh</p>
                                <p class="mt-1 break-words text-sm font-medium text-slate-700">
                                    {{ $calculation->calculator?->name ?? '-' }}
                                </p>
                            </div>

                            <div class="border-t border-slate-100 pt-3">
                                <a href="{{ route('saw-analysis.show', $calculation) }}"
                                   class="inline-flex w-full items-center justify-center rounded-lg bg-indigo-50 px-4 py-2.5 text-sm font-semibold text-indigo-700 transition hover:bg-indigo-100">
                                    Lihat Detail Perhitungan
                                </a>
                            </div>
                        </div>
                    </details>
                @empty
                    <div class="py-8 text-center">
                        <p class="font-medium text-slate-600">
                            Belum ada riwayat perhitungan SAW.
                        </p>
                        <p class="mt-1 text-sm text-slate-400">
                            Jalankan perhitungan SAW untuk membuat versi pertama.
                        </p>
                    </div>
                @endforelse
            </div>
        </div>

    @else

        {{-- EMPTY STATE: BELUM PILIH BATCH --}}
        <div class="rounded-xl border border-dashed border-slate-300 bg-white px-5 py-12 text-center shadow-sm sm:px-8">
            <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-indigo-50">
                <span class="text-2xl text-indigo-600">∑</span>
            </div>

            <h3 class="mt-4 text-lg font-semibold text-slate-800">
                Pilih Recruitment Batch
            </h3>

            <p class="mx-auto mt-2 max-w-md text-sm leading-relaxed text-slate-500">
                Pilih Recruitment Batch terlebih dahulu melalui dropdown di atas,
                kemudian klik Tampilkan untuk melihat kriteria, riwayat perhitungan,
                dan menjalankan analisis SAW.
            </p>
        </div>

    @endif

</div>

@endsection
