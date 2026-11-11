@extends('layouts.app')

@section('title', 'Criteria Management')
@section('page-title', 'Criteria Management')

@section('content')

@php
    $hasBatch = (bool) $recruitmentBatch;
    $isLocked = $hasBatch && (bool) $recruitmentBatch->criteria_weights_locked;
    $isWeightValid = abs((float) $totalWeight - 100) < 0.001;
    $remainingWeight = 100 - (float) $totalWeight;
    $weightPercent = min(100, max(0, (float) $totalWeight));
@endphp

{{-- HEADER --}}
<div class="mb-6">
    <h1 class="text-2xl font-bold text-slate-800">Criteria Management</h1>
    <p class="mt-1 text-sm text-slate-500">
        Pengaturan kriteria dan distribusi bobot untuk setiap Recruitment Batch.
    </p>
</div>

@if(session('success'))
    <div class="mb-5 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-800">
        {{ session('success') }}
    </div>
@endif

@if($errors->any())
    <div class="mb-5 rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-800">
        <p class="font-semibold">Periksa kembali data yang dikirim.</p>
        <ul class="mt-1 list-inside list-disc">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

{{-- FILTER BATCH --}}
<div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
    <form method="GET" action="{{ route('criteria-management.index') }}">
        <label class="mb-2 block text-sm font-medium text-slate-700">
            Pilih Recruitment Batch
        </label>

        <div class="flex flex-col gap-3 sm:flex-row">
            <select name="batch_id"
                    class="w-full rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-700 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-100">
                <option value="">-- Pilih Batch --</option>

                @foreach($batches as $batch)
                    <option value="{{ $batch->id }}"
                        @selected($hasBatch && $recruitmentBatch->id == $batch->id)>
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

    @if($batches->isEmpty())
        <p class="mt-3 text-sm text-amber-700">
            Belum ada recruitment batch. Buat batch terlebih dahulu.
        </p>
    @endif
</div>

@if(!$hasBatch)

    {{-- EMPTY STATE --}}
    <div class="mt-6 rounded-xl border border-dashed border-slate-300 bg-white px-5 py-12 text-center shadow-sm sm:px-8">
        <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-indigo-50">
            <span class="text-2xl text-indigo-600">≡</span>
        </div>

        <h3 class="mt-4 text-lg font-semibold text-slate-800">
            Pilih Recruitment Batch
        </h3>

        <p class="mx-auto mt-2 max-w-md text-sm leading-relaxed text-slate-500">
            Pilih Recruitment Batch terlebih dahulu melalui dropdown di atas,
            kemudian klik Tampilkan untuk mengatur kriteria dan bobot penilaian.
        </p>
    </div>

@else

    {{-- INFORMASI BATCH --}}
    <div class="mt-6 rounded-xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                    Recruitment Batch Terpilih
                </p>

                <h2 class="mt-1 text-xl font-bold text-slate-800">
                    {{ $recruitmentBatch->name }}
                </h2>

                <p class="mt-1 text-sm text-slate-500">
                    {{ $recruitmentBatch->batch_code }}
                    <span class="mx-1">·</span>
                    Posisi: {{ $recruitmentBatch->position }}
                </p>
            </div>

            <div class="flex flex-col gap-2 sm:flex-row sm:items-center">
                @if($isLocked)
                    <span class="inline-flex items-center justify-center gap-2 rounded-lg border border-amber-200 bg-amber-50 px-3.5 py-2.5 text-sm font-semibold text-amber-800">
                        🔒 Weights Locked
                    </span>

                    <form method="POST"
                          action="{{ route('recruitment-batches.criteria.unlock', $recruitmentBatch) }}"
                          onsubmit="return confirm('Buka kunci bobot batch ini? Konfigurasi bobot dapat diubah kembali.');">
                        @csrf

                        <button type="submit"
                                class="w-full rounded-lg bg-slate-800 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-slate-700 sm:w-auto">
                            Unlock Weights
                        </button>
                    </form>
                @else
                    <span class="inline-flex items-center justify-center gap-2 rounded-lg border border-emerald-200 bg-emerald-50 px-3.5 py-2.5 text-sm font-semibold text-emerald-800">
                        🔓 Weights Unlocked
                    </span>

                    <form method="POST"
                          action="{{ route('recruitment-batches.criteria.lock', $recruitmentBatch) }}"
                          onsubmit="return confirm('Kunci konfigurasi bobot batch ini? Bobot, kode, atribut, tambah, dan hapus kriteria akan dibatasi.');">
                        @csrf

                        <button type="submit"
                                {{ !$isWeightValid || $criteria->isEmpty() ? 'disabled' : '' }}
                                class="w-full rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-indigo-700 disabled:cursor-not-allowed disabled:bg-slate-300 sm:w-auto">
                            Lock Weights
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>

    {{-- RINGKASAN BOBOT --}}
    <div class="mt-6 grid grid-cols-1 gap-4 xl:grid-cols-3">
        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm xl:col-span-2">
            <div class="flex flex-col gap-2 sm:flex-row sm:items-start sm:justify-between">
                <div>
                    <h2 class="font-semibold text-slate-800">Weight Distribution Monitor</h2>
                    <p class="mt-1 text-sm text-slate-500">
                        Total bobot seluruh kriteria aktif dalam batch ini.
                    </p>
                </div>

                <span class="inline-flex w-fit rounded-lg px-3 py-1.5 text-xs font-bold {{ $isWeightValid ? 'bg-emerald-50 text-emerald-700' : 'bg-amber-50 text-amber-700' }}">
                    {{ $isWeightValid ? 'VALID · 100%' : 'NEEDS ADJUSTMENT' }}
                </span>
            </div>

            <div class="mt-5 flex items-end justify-between gap-3">
                <p class="text-3xl font-bold tabular-nums text-slate-800">
                    {{ number_format($totalWeight, 2, ',', '.') }}
                    <span class="ml-1 text-lg font-semibold text-slate-400">%</span>
                </p>

                <p class="text-right text-xs text-slate-500">
                    {{ $isWeightValid ? 'Bobot sudah seimbang' : ($remainingWeight > 0 ? 'Sisa ' . number_format($remainingWeight, 2, ',', '.') . '%' : 'Kelebihan ' . number_format(abs($remainingWeight), 2, ',', '.') . '%') }}
                </p>
            </div>

            <div class="mt-3 h-3 overflow-hidden rounded-full bg-slate-100">
                <div class="h-full rounded-full transition-all {{ $isWeightValid ? 'bg-emerald-500' : 'bg-indigo-500' }}"
                     style="width: {{ $weightPercent }}%">
                </div>
            </div>

            <div class="mt-2 flex justify-between text-xs text-slate-400">
                <span>0%</span>
                <span>Target 100%</span>
            </div>
        </div>

        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-sm font-medium text-slate-500">
                Configured Decision Criteria
            </p>

            <p class="mt-2 text-3xl font-bold tabular-nums text-slate-800">
                {{ $criteria->count() }}
            </p>

            <p class="mt-1 text-xs text-slate-400">
                Kriteria terdaftar pada batch ini
            </p>

            <div class="mt-4 border-t border-slate-100 pt-3">
                <div class="flex items-center justify-between text-xs">
                    <span class="text-slate-500">Benefit</span>
                    <span class="font-semibold text-emerald-700">
                        {{ $criteria->where('attribute', 'benefit')->count() }}
                    </span>
                </div>

                <div class="mt-2 flex items-center justify-between text-xs">
                    <span class="text-slate-500">Cost</span>
                    <span class="font-semibold text-amber-700">
                        {{ $criteria->where('attribute', 'cost')->count() }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    {{-- FORM TAMBAH KRITERIA --}}
    <div class="mt-6 rounded-xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
        <div class="mb-5 flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-lg font-semibold text-slate-800">
                    Criteria Specification
                </h2>
                <p class="mt-1 text-sm text-slate-500">
                    Tambahkan spesifikasi kriteria untuk proses pengambilan keputusan.
                </p>
            </div>

            @if($isLocked)
                <span class="text-xs font-semibold text-amber-700">
                    Tambah kriteria dinonaktifkan saat terkunci
                </span>
            @endif
        </div>

        <form action="{{ route('recruitment-batches.criteria.store', $recruitmentBatch) }}"
              method="POST">
            @csrf

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
                <div>
                    <label class="mb-1.5 block text-xs font-semibold text-slate-600">
                        Kode Kriteria
                    </label>

                    <input name="criterion_code"
                           value="{{ old('criterion_code') }}"
                           placeholder="C1"
                           required
                           maxlength="30"
                           {{ $isLocked ? 'disabled' : '' }}
                           class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 disabled:bg-slate-100">

                    @error('criterion_code')
                        <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="mb-1.5 block text-xs font-semibold text-slate-600">
                        Nama Kriteria
                    </label>

                    <input name="name"
                           value="{{ old('name') }}"
                           placeholder="Pendidikan"
                           required
                           maxlength="150"
                           {{ $isLocked ? 'disabled' : '' }}
                           class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 disabled:bg-slate-100">

                    @error('name')
                        <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="mb-1.5 block text-xs font-semibold text-slate-600">
                        Atribut
                    </label>

                    <select name="attribute"
                            required
                            {{ $isLocked ? 'disabled' : '' }}
                            class="w-full rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 disabled:bg-slate-100">
                        <option value="">Pilih atribut</option>
                        <option value="benefit" {{ old('attribute') === 'benefit' ? 'selected' : '' }}>Benefit</option>
                        <option value="cost" {{ old('attribute') === 'cost' ? 'selected' : '' }}>Cost</option>
                    </select>

                    @error('attribute')
                        <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="mb-1.5 block text-xs font-semibold text-slate-600">
                        Bobot (%)
                    </label>

                    <input type="number"
                           name="weight"
                           value="{{ old('weight') }}"
                           placeholder="25.00"
                           min="0.01"
                           max="100"
                           step="0.01"
                           required
                           {{ $isLocked ? 'disabled' : '' }}
                           class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 disabled:bg-slate-100">

                    @error('weight')
                        <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="sm:col-span-2 xl:col-span-4">
                    <label class="mb-1.5 block text-xs font-semibold text-slate-600">
                        Deskripsi <span class="font-normal text-slate-400">(opsional)</span>
                    </label>

                    <textarea name="description"
                              rows="2"
                              placeholder="Penjelasan kriteria..."
                              {{ $isLocked ? 'disabled' : '' }}
                              class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 disabled:bg-slate-100">{{ old('description') }}</textarea>

                    @error('description')
                        <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mt-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <p class="text-xs text-slate-400">
                    Total bobot tidak boleh melebihi 100%.
                </p>

                <button type="submit"
                        {{ $isLocked ? 'disabled' : '' }}
                        class="rounded-lg bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-indigo-700 disabled:cursor-not-allowed disabled:bg-slate-300">
                    + Add Criterion
                </button>
            </div>
        </form>
    </div>

    {{-- DAFTAR KRITERIA --}}
    <div class="mt-6 overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
        <div class="flex flex-col gap-3 border-b border-slate-200 p-5 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-lg font-semibold text-slate-800">
                    Configured Decision Criteria
                </h2>
                <p class="mt-1 text-sm text-slate-500">
                    Daftar kriteria dan tindakan pengelolaannya.
                </p>
            </div>

            <form method="POST"
                  action="{{ route('recruitment-batches.criteria.renormalize', $recruitmentBatch) }}"
                  onsubmit="return confirm('Bagi bobot seluruh kriteria secara merata hingga total 100%?');">
                @csrf

                <button type="submit"
                        {{ $isLocked || $criteria->isEmpty() ? 'disabled' : '' }}
                        class="w-full rounded-lg border border-indigo-200 bg-indigo-50 px-4 py-2.5 text-sm font-semibold text-indigo-700 transition hover:bg-indigo-100 disabled:cursor-not-allowed disabled:border-slate-200 disabled:bg-slate-100 disabled:text-slate-400 sm:w-auto">
                    Re-normalize Evenly
                </button>
            </form>
        </div>

        @if($criteria->isEmpty())
            <div class="px-6 py-14 text-center">
                <p class="font-semibold text-slate-700">Belum ada kriteria</p>
                <p class="mt-1 text-sm text-slate-400">
                    Tambahkan kriteria pertama menggunakan form di atas.
                </p>
            </div>
        @else

            {{-- DESKTOP TABLE --}}
            <div class="hidden lg:block">
                <table class="w-full table-fixed text-left text-sm">
                    <thead class="bg-slate-50 text-xs uppercase tracking-wide text-slate-500">
                        <tr>
                            <th class="w-[10%] px-5 py-4">Kode</th>
                            <th class="w-[22%] px-5 py-4">Kriteria</th>
                            <th class="w-[13%] px-5 py-4">Atribut</th>
                            <th class="w-[13%] px-5 py-4">Bobot</th>
                            <th class="w-[25%] px-5 py-4">Deskripsi</th>
                            <th class="w-[17%] px-5 py-4 text-center">Aksi</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-100">
                        @foreach($criteria as $criterion)
                            <tr class="align-top hover:bg-slate-50/70">
                                <td class="break-words px-5 py-4 font-bold text-indigo-700">
                                    {{ $criterion->criterion_code }}
                                </td>

                                <td class="break-words px-5 py-4 font-semibold text-slate-800">
                                    {{ $criterion->name }}
                                </td>

                                <td class="px-5 py-4">
                                    <span class="rounded-lg px-2.5 py-1 text-xs font-semibold {{ $criterion->attribute === 'benefit' ? 'bg-emerald-50 text-emerald-700' : 'bg-amber-50 text-amber-700' }}">
                                        {{ ucfirst($criterion->attribute) }}
                                    </span>
                                </td>

                                <td class="px-5 py-4 font-semibold tabular-nums text-slate-800">
                                    {{ number_format($criterion->weight, 2, ',', '.') }}%
                                </td>

                                <td class="break-words px-5 py-4 text-slate-500">
                                    {{ $criterion->description ?: '—' }}
                                </td>

                                <td class="px-5 py-4">
                                    <div class="flex flex-wrap justify-center gap-2">
                                        <button type="button"
                                                onclick="document.getElementById('edit-desktop-{{ $criterion->id }}').classList.toggle('hidden')"
                                                class="rounded-lg bg-slate-100 px-3 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-200">
                                            Edit
                                        </button>

                                        <button type="button"
                                                data-delete-url="{{ route('recruitment-batches.criteria.destroy', [$recruitmentBatch, $criterion]) }}"
                                                data-delete-name="{{ $criterion->name }}"
                                                {{ $isLocked ? 'disabled' : '' }}
                                                class="rounded-lg bg-rose-50 px-3 py-2 text-xs font-semibold text-rose-700 hover:bg-rose-100 disabled:cursor-not-allowed disabled:opacity-40">
                                            Hapus
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            {{-- FORM EDIT DESKTOP --}}
                            <tr id="edit-desktop-{{ $criterion->id }}" class="hidden bg-slate-50">
                                <td colspan="6" class="px-5 py-5">
                                    <form action="{{ route('recruitment-batches.criteria.update', [$recruitmentBatch, $criterion]) }}"
                                          method="POST">
                                        @csrf
                                        @method('PUT')

                                        <div class="grid grid-cols-1 gap-3 md:grid-cols-2 xl:grid-cols-4">
                                            <div>
                                                <label class="mb-1 block text-xs font-semibold text-slate-600">
                                                    Kode
                                                </label>

                                                <input name="criterion_code"
                                                       value="{{ $criterion->criterion_code }}"
                                                       required
                                                       {{ $isLocked ? 'readonly' : '' }}
                                                       class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm read-only:bg-slate-100">
                                            </div>

                                            <div>
                                                <label class="mb-1 block text-xs font-semibold text-slate-600">
                                                    Nama
                                                </label>

                                                <input name="name"
                                                       value="{{ $criterion->name }}"
                                                       required
                                                       class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                                            </div>

                                            <div>
                                                <label class="mb-1 block text-xs font-semibold text-slate-600">
                                                    Atribut
                                                </label>

                                                <select name="attribute"
                                                        {{ $isLocked ? 'disabled' : '' }}
                                                        class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm disabled:bg-slate-100">
                                                    <option value="benefit" {{ $criterion->attribute === 'benefit' ? 'selected' : '' }}>Benefit</option>
                                                    <option value="cost" {{ $criterion->attribute === 'cost' ? 'selected' : '' }}>Cost</option>
                                                </select>

                                                @if($isLocked)
                                                    <input type="hidden" name="attribute" value="{{ $criterion->attribute }}">
                                                @endif
                                            </div>

                                            <div>
                                                <label class="mb-1 block text-xs font-semibold text-slate-600">
                                                    Bobot (%)
                                                </label>

                                                <input type="number"
                                                       name="weight"
                                                       value="{{ $criterion->weight }}"
                                                       min="0.01"
                                                       max="100"
                                                       step="0.01"
                                                       required
                                                       {{ $isLocked ? 'readonly' : '' }}
                                                       class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm read-only:bg-slate-100">
                                            </div>

                                            <div class="md:col-span-2 xl:col-span-4">
                                                <label class="mb-1 block text-xs font-semibold text-slate-600">
                                                    Deskripsi
                                                </label>

                                                <textarea name="description"
                                                          rows="2"
                                                          class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">{{ $criterion->description }}</textarea>
                                            </div>
                                        </div>

                                        <div class="mt-3 flex gap-2">
                                            <button type="submit"
                                                    class="rounded-lg bg-indigo-600 px-4 py-2 text-xs font-semibold text-white">
                                                Simpan
                                            </button>

                                            <button type="button"
                                                    onclick="document.getElementById('edit-desktop-{{ $criterion->id }}').classList.add('hidden')"
                                                    class="rounded-lg bg-white px-4 py-2 text-xs font-semibold text-slate-600 ring-1 ring-slate-200">
                                                Batal
                                            </button>
                                        </div>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- TABLET & MOBILE --}}
            <div class="space-y-3 p-4 lg:hidden">
                @foreach($criteria as $criterion)
                    <details class="group rounded-xl border border-slate-200 bg-white">
                        <summary class="flex cursor-pointer list-none items-center justify-between gap-3 p-4">
                            <div class="min-w-0">
                                <div class="flex flex-wrap items-center gap-2">
                                    <span class="text-xs font-bold text-indigo-700">
                                        {{ $criterion->criterion_code }}
                                    </span>

                                    <span class="rounded-lg px-2 py-0.5 text-xs font-semibold {{ $criterion->attribute === 'benefit' ? 'bg-emerald-50 text-emerald-700' : 'bg-amber-50 text-amber-700' }}">
                                        {{ ucfirst($criterion->attribute) }}
                                    </span>
                                </div>

                                <p class="mt-1 font-semibold text-slate-800">
                                    {{ $criterion->name }}
                                </p>

                                <p class="mt-1 text-xs text-slate-500">
                                    Bobot {{ number_format($criterion->weight, 2, ',', '.') }}%
                                </p>
                            </div>

                            <span class="shrink-0 text-slate-400 transition group-open:rotate-180">
                                ⌄
                            </span>
                        </summary>

                        <div class="border-t border-slate-100 p-4">
                            <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                                Deskripsi
                            </p>

                            <p class="mt-1 break-words text-sm text-slate-700">
                                {{ $criterion->description ?: '—' }}
                            </p>

                            <div class="mt-4 flex flex-wrap gap-2">
                                <button type="button"
                                        onclick="document.getElementById('edit-mobile-{{ $criterion->id }}').classList.toggle('hidden')"
                                        class="rounded-lg bg-slate-100 px-3 py-2 text-xs font-semibold text-slate-700">
                                    Edit
                                </button>

                                <button type="button"
                                        data-delete-url="{{ route('recruitment-batches.criteria.destroy', [$recruitmentBatch, $criterion]) }}"
                                        data-delete-name="{{ $criterion->name }}"
                                        {{ $isLocked ? 'disabled' : '' }}
                                        class="rounded-lg bg-rose-50 px-3 py-2 text-xs font-semibold text-rose-700 disabled:opacity-40">
                                    Hapus
                                </button>
                            </div>

                            {{-- FORM EDIT MOBILE --}}
                            <div id="edit-mobile-{{ $criterion->id }}"
                                 class="mt-4 hidden rounded-xl bg-slate-50 p-4">
                                <form action="{{ route('recruitment-batches.criteria.update', [$recruitmentBatch, $criterion]) }}"
                                      method="POST">
                                    @csrf
                                    @method('PUT')

                                    <div class="space-y-3">
                                        <div>
                                            <label class="mb-1 block text-xs font-semibold text-slate-600">
                                                Kode
                                            </label>

                                            <input name="criterion_code"
                                                   value="{{ $criterion->criterion_code }}"
                                                   required
                                                   {{ $isLocked ? 'readonly' : '' }}
                                                   class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm read-only:bg-slate-100">
                                        </div>

                                        <div>
                                            <label class="mb-1 block text-xs font-semibold text-slate-600">
                                                Nama
                                            </label>

                                            <input name="name"
                                                   value="{{ $criterion->name }}"
                                                   required
                                                   class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                                        </div>

                                        <div>
                                            <label class="mb-1 block text-xs font-semibold text-slate-600">
                                                Atribut
                                            </label>

                                            <select name="attribute"
                                                    {{ $isLocked ? 'disabled' : '' }}
                                                    class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm disabled:bg-slate-100">
                                                <option value="benefit" {{ $criterion->attribute === 'benefit' ? 'selected' : '' }}>Benefit</option>
                                                <option value="cost" {{ $criterion->attribute === 'cost' ? 'selected' : '' }}>Cost</option>
                                            </select>

                                            @if($isLocked)
                                                <input type="hidden" name="attribute" value="{{ $criterion->attribute }}">
                                            @endif
                                        </div>

                                        <div>
                                            <label class="mb-1 block text-xs font-semibold text-slate-600">
                                                Bobot (%)
                                            </label>

                                            <input type="number"
                                                   name="weight"
                                                   value="{{ $criterion->weight }}"
                                                   min="0.01"
                                                   max="100"
                                                   step="0.01"
                                                   required
                                                   {{ $isLocked ? 'readonly' : '' }}
                                                   class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm read-only:bg-slate-100">
                                        </div>

                                        <div>
                                            <label class="mb-1 block text-xs font-semibold text-slate-600">
                                                Deskripsi
                                            </label>

                                            <textarea name="description"
                                                      rows="2"
                                                      class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">{{ $criterion->description }}</textarea>
                                        </div>
                                    </div>

                                    <div class="mt-3 flex flex-wrap gap-2">
                                        <button type="submit"
                                                class="rounded-lg bg-indigo-600 px-4 py-2 text-xs font-semibold text-white">
                                            Simpan
                                        </button>

                                        <button type="button"
                                                onclick="document.getElementById('edit-mobile-{{ $criterion->id }}').classList.add('hidden')"
                                                class="rounded-lg bg-white px-4 py-2 text-xs font-semibold text-slate-600 ring-1 ring-slate-200">
                                            Batal
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </details>
                @endforeach
            </div>
        @endif
    </div>

    <p class="mt-4 text-xs leading-relaxed text-slate-400">
        Catatan: SAW tetap dapat dijalankan selama total bobot tepat 100%, baik status bobot terkunci maupun terbuka.
        Penguncian hanya melindungi konfigurasi kriteria dari perubahan yang tidak disengaja.
    </p>

@endif

@endsection