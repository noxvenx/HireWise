@extends('layouts.app')

@section('title', 'Criteria Management')
@section('page-title', 'Criteria Management')

@section('content')

@php
    $isWeightValid = abs((float) $totalWeight - 100) < 0.001;
@endphp

{{-- HEADER --}}
<div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
    <div>
        <h1 class="text-2xl font-bold text-slate-800">Criteria Management</h1>
        <p class="mt-1 text-sm text-slate-500">
            Atur kriteria dan bobot penilaian untuk recruitment batch.
        </p>
    </div>

    <a href="{{ route('recruitment-batches.show', $recruitmentBatch) }}"
       class="inline-flex items-center justify-center rounded-lg bg-slate-100 px-4 py-2.5 text-sm font-medium text-slate-700 transition hover:bg-slate-200">
        <span class="mr-2">←</span> Kembali ke Batch
    </a>
</div>

{{-- INFORMASI BATCH --}}
<div class="mb-6 rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
    <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">
        Recruitment Batch
    </p>

    <h2 class="mt-1 text-lg font-bold text-slate-800">
        {{ $recruitmentBatch->name }}
    </h2>

    <p class="mt-1 text-sm text-slate-500">
        {{ $recruitmentBatch->batch_code }}
        <span class="mx-1">·</span>
        {{ $recruitmentBatch->position }}
    </p>
</div>

{{-- RINGKASAN --}}
<div class="mb-6 grid grid-cols-1 gap-4 sm:grid-cols-2">
    <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
        <div class="flex items-center justify-between">
            <p class="text-sm font-medium text-slate-500">Jumlah Kriteria</p>
            <span class="rounded-lg bg-indigo-50 p-2 text-indigo-600">≡</span>
        </div>
        <p class="mt-2 text-2xl font-bold text-slate-800">
            {{ $criteria->count() }}
        </p>
        <p class="mt-1 text-xs text-slate-400">Kriteria penilaian batch ini</p>
    </div>

    <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
        <div class="flex items-center justify-between">
            <p class="text-sm font-medium text-slate-500">Total Bobot</p>
            <span class="rounded-lg {{ $isWeightValid ? 'bg-green-50 text-green-600' : 'bg-amber-50 text-amber-600' }} p-2">
                %
            </span>
        </div>

        <p class="mt-2 text-2xl font-bold {{ $isWeightValid ? 'text-green-600' : 'text-amber-600' }}">
            {{ number_format($totalWeight, 2, ',', '.') }}%
        </p>

        <p class="mt-1 text-xs {{ $isWeightValid ? 'text-green-600' : 'text-amber-600' }}">
            {{ $isWeightValid ? 'Total bobot sudah sesuai 100%.' : 'Total bobot wajib 100% sebelum proses SAW.' }}
        </p>
    </div>
</div>

{{-- FORM TAMBAH KRITERIA --}}
<div class="mb-6 rounded-xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
    <div class="mb-5">
        <h2 class="text-lg font-semibold text-slate-800">Tambah Kriteria</h2>
        <p class="mt-1 text-sm text-slate-500">
            Masukkan kriteria penilaian baru untuk batch ini.
        </p>
    </div>

    <form action="{{ route('recruitment-batches.criteria.store', $recruitmentBatch) }}"
          method="POST">
        @csrf

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
            <div>
                <label class="mb-2 block text-sm font-medium text-slate-700">
                    Kode Kriteria
                </label>
                <input type="text"
                       name="criterion_code"
                       value="{{ old('criterion_code') }}"
                       placeholder="C1"
                       required
                       class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-100">
                @error('criterion_code')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="mb-2 block text-sm font-medium text-slate-700">
                    Nama Kriteria
                </label>
                <input type="text"
                       name="name"
                       value="{{ old('name') }}"
                       placeholder="Pendidikan"
                       required
                       class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-100">
                @error('name')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="mb-2 block text-sm font-medium text-slate-700">
                    Atribut
                </label>
                <select name="attribute"
                        required
                        class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-100">
                    <option value="">Pilih Atribut</option>
                    <option value="benefit" {{ old('attribute') == 'benefit' ? 'selected' : '' }}>Benefit</option>
                    <option value="cost" {{ old('attribute') == 'cost' ? 'selected' : '' }}>Cost</option>
                </select>
                @error('attribute')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="mb-2 block text-sm font-medium text-slate-700">
                    Bobot (%)
                </label>
                <input type="number"
                       name="weight"
                       value="{{ old('weight') }}"
                       min="0.01"
                       max="100"
                       step="0.01"
                       placeholder="25"
                       required
                       class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-100">
                @error('weight')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="sm:col-span-2 xl:col-span-4">
                <label class="mb-2 block text-sm font-medium text-slate-700">
                    Deskripsi
                </label>
                <textarea name="description"
                          rows="2"
                          placeholder="Penjelasan kriteria (opsional)"
                          class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-100">{{ old('description') }}</textarea>
                @error('description')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="mt-5">
            <button type="submit"
                    class="inline-flex w-full items-center justify-center rounded-lg bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-indigo-700 sm:w-auto">
                + Simpan Kriteria
            </button>
        </div>
    </form>
</div>

{{-- DAFTAR KRITERIA --}}
<div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
    <div class="border-b border-slate-200 p-5">
        <h2 class="text-lg font-semibold text-slate-800">Daftar Kriteria</h2>
        <p class="mt-1 text-sm text-slate-500">
            Kelola kriteria yang digunakan dalam penilaian batch ini.
        </p>
    </div>

    {{-- DESKTOP TABLE --}}
    <div class="hidden lg:block">
        @if($criteria->isNotEmpty())
            <table class="w-full table-fixed text-left text-sm">
                <thead class="bg-slate-50 text-xs uppercase tracking-wide text-slate-500">
                    <tr>
                        <th class="w-[10%] px-4 py-4">Kode</th>
                        <th class="w-[22%] px-4 py-4">Nama Kriteria</th>
                        <th class="w-[13%] px-4 py-4">Atribut</th>
                        <th class="w-[12%] px-4 py-4">Bobot</th>
                        <th class="w-[25%] px-4 py-4">Deskripsi</th>
                        <th class="w-[18%] px-4 py-4 text-center">Aksi</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100">
                    @foreach($criteria as $criterion)
                        <tr class="align-top transition hover:bg-slate-50">
                            <td class="break-words px-4 py-4 font-semibold text-slate-800">
                                {{ $criterion->criterion_code }}
                            </td>

                            <td class="break-words px-4 py-4 font-medium text-slate-700">
                                {{ $criterion->name }}
                            </td>

                            <td class="px-4 py-4">
                                <span class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold
                                    {{ $criterion->attribute === 'benefit'
                                        ? 'bg-green-100 text-green-700'
                                        : 'bg-amber-100 text-amber-700' }}">
                                    {{ ucfirst($criterion->attribute) }}
                                </span>
                            </td>

                            <td class="px-4 py-4 font-semibold text-slate-800">
                                {{ number_format($criterion->weight, 2, ',', '.') }}%
                            </td>

                            <td class="break-words px-4 py-4 text-slate-500">
                                {{ $criterion->description ?: '-' }}
                            </td>

                            <td class="px-4 py-4">
                                <div class="flex flex-wrap items-center justify-center gap-3">
                                    <button type="button"
                                            onclick="document.getElementById('edit-criterion-{{ $criterion->id }}').classList.toggle('hidden')"
                                            class="rounded-lg bg-indigo-50 px-3 py-2 text-sm font-medium text-indigo-600 hover:text-indigo-800">
                                        Edit
                                    </button>

                                    <button type="button"
                                            data-delete-url="{{ route('recruitment-batches.criteria.destroy', [$recruitmentBatch, $criterion]) }}"
                                            data-delete-name="{{ $criterion->name }}"
                                            class="rounded-lg bg-red-50 px-3 py-2 text-sm font-medium text-red-600 hover:text-red-800">
                                        Hapus
                                    </button>
                                </div>
                            </td>
                        </tr>

                        {{-- FORM EDIT DESKTOP --}}
                        <tr id="edit-criterion-{{ $criterion->id }}" class="hidden bg-indigo-50/50">
                            <td colspan="6" class="px-5 py-5">
                                <form action="{{ route('recruitment-batches.criteria.update', [$recruitmentBatch, $criterion]) }}"
                                      method="POST">
                                    @csrf
                                    @method('PUT')

                                    <div class="mb-4">
                                        <h3 class="font-semibold text-slate-800">
                                            Edit Kriteria: {{ $criterion->name }}
                                        </h3>
                                        <p class="mt-1 text-xs text-slate-500">
                                            Perbarui informasi kriteria di bawah ini.
                                        </p>
                                    </div>

                                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-4">
                                        <div>
                                            <label class="mb-1 block text-xs font-medium text-slate-700">Kode</label>
                                            <input type="text" name="criterion_code"
                                                   value="{{ $criterion->criterion_code }}" required
                                                   class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                                        </div>

                                        <div>
                                            <label class="mb-1 block text-xs font-medium text-slate-700">Nama Kriteria</label>
                                            <input type="text" name="name"
                                                   value="{{ $criterion->name }}" required
                                                   class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                                        </div>

                                        <div>
                                            <label class="mb-1 block text-xs font-medium text-slate-700">Atribut</label>
                                            <select name="attribute" required
                                                    class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm">
                                                <option value="benefit" {{ $criterion->attribute === 'benefit' ? 'selected' : '' }}>Benefit</option>
                                                <option value="cost" {{ $criterion->attribute === 'cost' ? 'selected' : '' }}>Cost</option>
                                            </select>
                                        </div>

                                        <div>
                                            <label class="mb-1 block text-xs font-medium text-slate-700">Bobot (%)</label>
                                            <input type="number" name="weight"
                                                   value="{{ $criterion->weight }}"
                                                   min="0.01" max="100" step="0.01" required
                                                   class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                                        </div>

                                        <div class="md:col-span-2 xl:col-span-4">
                                            <label class="mb-1 block text-xs font-medium text-slate-700">Deskripsi</label>
                                            <textarea name="description" rows="2"
                                                      class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">{{ $criterion->description }}</textarea>
                                        </div>
                                    </div>

                                    <div class="mt-4 flex flex-wrap gap-2">
                                        <button type="submit"
                                                class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">
                                            Simpan Perubahan
                                        </button>
                                        <button type="button"
                                                onclick="document.getElementById('edit-criterion-{{ $criterion->id }}').classList.add('hidden')"
                                                class="rounded-lg bg-slate-200 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-300">
                                            Batal
                                        </button>
                                    </div>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="px-6 py-12 text-center">
                <p class="font-medium text-slate-600">Belum ada kriteria untuk batch ini.</p>
                <p class="mt-1 text-sm text-slate-400">Tambahkan kriteria melalui form di atas.</p>
            </div>
        @endif
    </div>

    {{-- MOBILE & TABLET ACCORDION --}}
    <div class="space-y-3 p-4 lg:hidden">
        @forelse($criteria as $criterion)
            <details class="group rounded-xl border border-slate-200 bg-white">
                <summary class="flex cursor-pointer list-none items-center justify-between gap-3 p-4">
                    <div class="min-w-0">
                        <div class="flex flex-wrap items-center gap-2">
                            <span class="text-xs font-bold text-indigo-600">
                                {{ $criterion->criterion_code }}
                            </span>
                            <span class="rounded-full px-2 py-0.5 text-xs font-semibold
                                {{ $criterion->attribute === 'benefit'
                                    ? 'bg-green-100 text-green-700'
                                    : 'bg-amber-100 text-amber-700' }}">
                                {{ ucfirst($criterion->attribute) }}
                            </span>
                        </div>

                        <p class="mt-1 truncate font-semibold text-slate-800">
                            {{ $criterion->name }}
                        </p>

                        <p class="mt-1 text-xs text-slate-500">
                            Bobot: {{ number_format($criterion->weight, 2, ',', '.') }}%
                        </p>
                    </div>

                    <span class="shrink-0 text-slate-400 transition group-open:rotate-180">
                        ▼
                    </span>
                </summary>

                <div class="border-t border-slate-100 p-4">
                    <div class="space-y-3 text-sm">
                        <div>
                            <p class="text-xs font-medium text-slate-400">Deskripsi</p>
                            <p class="mt-1 break-words text-slate-700">
                                {{ $criterion->description ?: '-' }}
                            </p>
                        </div>
                    </div>

                    <div class="mt-4 flex flex-wrap gap-3 border-t border-slate-100 pt-3">
                        <button type="button"
                                onclick="document.getElementById('edit-criterion-mobile-{{ $criterion->id }}').classList.toggle('hidden')"
                                class="rounded-lg bg-indigo-50 px-3 py-2 text-sm font-medium text-indigo-700 hover:bg-indigo-100">
                            Edit Kriteria
                        </button>

                        <button type="button"
                                data-delete-url="{{ route('recruitment-batches.criteria.destroy', [$recruitmentBatch, $criterion]) }}"
                                data-delete-name="{{ $criterion->name }}"
                                class="rounded-lg bg-red-50 px-3 py-2 text-sm font-medium text-red-700 hover:bg-red-100">
                            Hapus
                        </button>
                    </div>

                    {{-- FORM EDIT MOBILE --}}
                    <div id="edit-criterion-mobile-{{ $criterion->id }}"
                         class="mt-4 hidden rounded-lg border border-indigo-100 bg-indigo-50/50 p-4">
                        <h3 class="mb-4 text-sm font-semibold text-slate-800">
                            Edit Kriteria
                        </h3>

                        <form action="{{ route('recruitment-batches.criteria.update', [$recruitmentBatch, $criterion]) }}"
                              method="POST">
                            @csrf
                            @method('PUT')

                            <div class="space-y-3">
                                <div>
                                    <label class="mb-1 block text-xs font-medium text-slate-700">Kode Kriteria</label>
                                    <input type="text" name="criterion_code"
                                           value="{{ $criterion->criterion_code }}" required
                                           class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                                </div>

                                <div>
                                    <label class="mb-1 block text-xs font-medium text-slate-700">Nama Kriteria</label>
                                    <input type="text" name="name"
                                           value="{{ $criterion->name }}" required
                                           class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                                </div>

                                <div>
                                    <label class="mb-1 block text-xs font-medium text-slate-700">Atribut</label>
                                    <select name="attribute" required
                                            class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm">
                                        <option value="benefit" {{ $criterion->attribute === 'benefit' ? 'selected' : '' }}>Benefit</option>
                                        <option value="cost" {{ $criterion->attribute === 'cost' ? 'selected' : '' }}>Cost</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="mb-1 block text-xs font-medium text-slate-700">Bobot (%)</label>
                                    <input type="number" name="weight"
                                           value="{{ $criterion->weight }}"
                                           min="0.01" max="100" step="0.01" required
                                           class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                                </div>

                                <div>
                                    <label class="mb-1 block text-xs font-medium text-slate-700">Deskripsi</label>
                                    <textarea name="description" rows="2"
                                              class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">{{ $criterion->description }}</textarea>
                                </div>
                            </div>

                            <div class="mt-4 flex flex-wrap gap-2">
                                <button type="submit"
                                        class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">
                                    Simpan Perubahan
                                </button>
                                <button type="button"
                                        onclick="document.getElementById('edit-criterion-mobile-{{ $criterion->id }}').classList.add('hidden')"
                                        class="rounded-lg bg-slate-200 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-300">
                                    Batal
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </details>
        @empty
            <div class="py-10 text-center">
                <p class="font-medium text-slate-600">Belum ada kriteria untuk batch ini.</p>
                <p class="mt-1 text-sm text-slate-400">Tambahkan kriteria melalui form di atas.</p>
            </div>
        @endforelse
    </div>
</div>

@endsection
