@extends('layouts.app')

@section('title', 'SAW Analysis')

@section('content')
<div class="space-y-6">

    <div>
        <h1 class="text-2xl font-bold text-gray-800">SAW Analysis</h1>
        <p class="mt-1 text-sm text-gray-500">
            Perhitungan Simple Additive Weighting untuk menentukan peringkat kandidat.
        </p>
    </div>

    {{-- Pilih Batch --}}
    <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
        <form method="GET" action="{{ route('saw-analysis.index') }}">
            <label class="mb-2 block text-sm font-semibold text-gray-700">
                Pilih Recruitment Batch
            </label>

            <div class="flex flex-col gap-3 sm:flex-row">
                <select name="batch"
                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-indigo-500 focus:outline-none">
                    <option value="">-- Pilih Batch --</option>

                    @foreach($batches as $batch)
                        <option value="{{ $batch->id }}"
                            @selected(request('batch') == $batch->id)>
                            {{ $batch->batch_code }} - {{ $batch->name }}
                        </option>
                    @endforeach
                </select>

                <button type="submit"
                    class="rounded-lg bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-indigo-700">
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
        @endphp

        {{-- Informasi Batch --}}
        <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
            <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-center">
                <div>
                    <h2 class="text-lg font-bold text-gray-800">
                        {{ $selectedBatch->name }}
                    </h2>
                    <p class="mt-1 text-sm text-gray-500">
                        {{ $selectedBatch->batch_code }} · {{ $selectedBatch->position }}
                    </p>
                </div>

                <form method="POST"
                    action="{{ route('saw-analysis.calculate', $selectedBatch) }}"
                    onsubmit="return confirm('Jalankan perhitungan SAW untuk batch ini?');">
                    @csrf
                    <button type="submit"
                        class="rounded-lg bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-indigo-700">
                        Hitung SAW Sekarang
                    </button>
                </form>
            </div>
        </div>

        {{-- Ringkasan --}}
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">

            <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                <p class="text-sm text-gray-500">Kandidat Lolos Screening</p>
                <p class="mt-2 text-3xl font-bold text-gray-800">
                    {{ $passedCount }}
                </p>
            </div>

            <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                <p class="text-sm text-gray-500">Kriteria Aktif</p>
                <p class="mt-2 text-3xl font-bold text-gray-800">
                    {{ $criteria->count() }}
                </p>
            </div>

            <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                <p class="text-sm text-gray-500">Total Bobot</p>
                <p class="mt-2 text-3xl font-bold {{ abs($totalWeight - 100) <= 0.01 ? 'text-green-600' : 'text-red-600' }}">
                    {{ number_format($totalWeight, 2) }}%
                </p>
            </div>

        </div>

        {{-- Daftar Kriteria --}}
        <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
            <div class="border-b border-gray-200 p-5">
                <h3 class="font-bold text-gray-800">Kriteria Penilaian</h3>
                <p class="mt-1 text-sm text-gray-500">
                    Kriteria aktif yang digunakan dalam perhitungan batch ini.
                </p>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-gray-50 text-gray-600">
                        <tr>
                            <th class="px-5 py-3">Kode</th>
                            <th class="px-5 py-3">Nama Kriteria</th>
                            <th class="px-5 py-3">Atribut</th>
                            <th class="px-5 py-3 text-right">Bobot</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($criteria as $criterion)
                            <tr>
                                <td class="px-5 py-3 font-medium">
                                    {{ $criterion->criterion_code }}
                                </td>
                                <td class="px-5 py-3">
                                    {{ $criterion->name }}
                                </td>
                                <td class="px-5 py-3">
                                    <span class="rounded-full px-2.5 py-1 text-xs font-medium
                                        {{ $criterion->attribute === 'benefit'
                                            ? 'bg-green-100 text-green-700'
                                            : 'bg-orange-100 text-orange-700' }}">
                                        {{ ucfirst($criterion->attribute) }}
                                    </span>
                                </td>
                                <td class="px-5 py-3 text-right font-semibold">
                                    {{ number_format($criterion->weight, 2) }}%
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-5 py-8 text-center text-gray-500">
                                    Belum ada kriteria aktif.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Riwayat Perhitungan --}}
        <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
            <div class="border-b border-gray-200 p-5">
                <h3 class="font-bold text-gray-800">Riwayat Perhitungan SAW</h3>
                <p class="mt-1 text-sm text-gray-500">
                    Setiap perhitungan disimpan sebagai versi tersendiri.
                </p>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-gray-50 text-gray-600">
                        <tr>
                            <th class="px-5 py-3">Versi</th>
                            <th class="px-5 py-3">Tanggal Perhitungan</th>
                            <th class="px-5 py-3">Jumlah Kandidat</th>
                            <th class="px-5 py-3">Dihitung Oleh</th>
                            <th class="px-5 py-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($calculations as $calculation)
                            <tr>
                                <td class="px-5 py-3 font-semibold">
                                    Versi {{ $calculation->version }}
                                </td>
                                <td class="px-5 py-3">
                                    {{ $calculation->calculated_at?->format('d/m/Y H:i') ?? '-' }}
                                </td>
                                <td class="px-5 py-3">
                                    {{ $calculation->candidate_count }}
                                </td>
                                <td class="px-5 py-3">
                                    {{ $calculation->calculator?->name ?? '-' }}
                                </td>
                                <td class="px-5 py-3 text-right">
                                    <a href="{{ route('saw-analysis.show', $calculation) }}"
                                        class="font-semibold text-indigo-600 hover:text-indigo-800">
                                        Lihat Detail
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-5 py-8 text-center text-gray-500">
                                    Belum ada riwayat perhitungan SAW.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    @else
        <div class="rounded-xl border border-dashed border-gray-300 bg-white p-10 text-center">
            <h3 class="font-semibold text-gray-700">Pilih Recruitment Batch</h3>
            <p class="mt-2 text-sm text-gray-500">
                Pilih batch terlebih dahulu untuk melihat kriteria dan menjalankan analisis SAW.
            </p>
        </div>
    @endif

</div>
@endsection
