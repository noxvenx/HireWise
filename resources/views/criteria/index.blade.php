@extends('layouts.app')

@section('title', 'Criteria Management')
@section('page-title', 'Criteria Management')

@section('content')

<div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <h1 class="text-2xl font-bold text-slate-800">Criteria Management</h1>
        <p class="text-sm text-slate-500 mt-1">
            Atur kriteria dan bobot penilaian untuk recruitment batch.
        </p>
    </div>

    <a href="{{ route('recruitment-batches.show', $recruitmentBatch) }}"
       class="bg-slate-200 hover:bg-slate-300 text-slate-700 px-4 py-2 rounded-lg text-center">
        Kembali ke Batch
    </a>
</div>

{{-- INFORMASI BATCH --}}
<div class="bg-white border border-slate-100 rounded-xl p-5 shadow-sm mb-6">
    <p class="text-sm text-slate-500">Recruitment Batch</p>
    <h2 class="text-lg font-bold text-slate-800 mt-1">
        {{ $recruitmentBatch->name }}
    </h2>
    <p class="text-sm text-slate-500 mt-1">
        {{ $recruitmentBatch->batch_code }} · {{ $recruitmentBatch->position }}
    </p>
</div>

{{-- RINGKASAN BOBOT --}}
<div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
    <div class="bg-white border border-slate-100 rounded-xl p-5 shadow-sm">
        <p class="text-sm text-slate-500">Jumlah Kriteria</p>
        <p class="text-2xl font-bold text-slate-800 mt-1">
            {{ $criteria->count() }}
        </p>
    </div>

    <div class="bg-white border border-slate-100 rounded-xl p-5 shadow-sm">
        <p class="text-sm text-slate-500">Total Bobot</p>
        <p class="text-2xl font-bold mt-1 {{ (float) $totalWeight === 100.0 ? 'text-green-600' : 'text-amber-600' }}">
            {{ number_format($totalWeight, 2, ',', '.') }}%
        </p>
        <p class="text-xs text-slate-500 mt-1">
            Total bobot wajib 100% sebelum proses SAW.
        </p>
    </div>
</div>

{{-- FORM TAMBAH KRITERIA --}}
<div class="bg-white border border-slate-100 rounded-xl p-6 shadow-sm mb-6">
    <h2 class="text-lg font-semibold text-slate-800 mb-1">
        Tambah Kriteria
    </h2>
    <p class="text-sm text-slate-500 mb-5">
        Masukkan kriteria penilaian baru untuk batch ini.
    </p>

    <form action="{{ route('recruitment-batches.criteria.store', $recruitmentBatch) }}"
          method="POST">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">
                    Kode Kriteria
                </label>
                <input type="text"
                       name="criterion_code"
                       value="{{ old('criterion_code') }}"
                       placeholder="C1"
                       required
                       class="w-full rounded-lg border border-slate-300 px-3 py-2.5">
                @error('criterion_code')
                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">
                    Nama Kriteria
                </label>
                <input type="text"
                       name="name"
                       value="{{ old('name') }}"
                       placeholder="Pendidikan"
                       required
                       class="w-full rounded-lg border border-slate-300 px-3 py-2.5">
                @error('name')
                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">
                    Atribut
                </label>
                <select name="attribute"
                        required
                        class="w-full rounded-lg border border-slate-300 px-3 py-2.5">
                    <option value="">Pilih Atribut</option>
                    <option value="benefit" {{ old('attribute') == 'benefit' ? 'selected' : '' }}>Benefit</option>
                    <option value="cost" {{ old('attribute') == 'cost' ? 'selected' : '' }}>Cost</option>
                </select>
                @error('attribute')
                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">
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
                       class="w-full rounded-lg border border-slate-300 px-3 py-2.5">
                @error('weight')
                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="md:col-span-2 lg:col-span-4">
                <label class="block text-sm font-medium text-slate-700 mb-2">
                    Deskripsi
                </label>
                <textarea name="description"
                          rows="2"
                          class="w-full rounded-lg border border-slate-300 px-3 py-2.5"
                          placeholder="Penjelasan kriteria (opsional)">{{ old('description') }}</textarea>
            </div>
        </div>

        <div class="mt-5">
            <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-lg font-medium">
                + Simpan Kriteria
            </button>
        </div>
    </form>
</div>

{{-- TABEL KRITERIA --}}
<div class="bg-white border border-slate-100 rounded-xl shadow-sm overflow-hidden">
    <div class="p-5 border-b border-slate-100">
        <h2 class="text-lg font-semibold text-slate-800">Daftar Kriteria</h2>
        <p class="text-sm text-slate-500 mt-1">
            Kelola kriteria yang digunakan dalam penilaian batch ini.
        </p>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead class="bg-slate-50 text-slate-600">
                <tr>
                    <th class="px-5 py-4">Kode</th>
                    <th class="px-5 py-4">Nama Kriteria</th>
                    <th class="px-5 py-4">Atribut</th>
                    <th class="px-5 py-4">Bobot</th>
                    <th class="px-5 py-4">Deskripsi</th>
                    <th class="px-5 py-4 text-center">Aksi</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-slate-100">
                @forelse($criteria as $criterion)
                    <tr class="hover:bg-slate-50 align-top">
                        <td class="px-5 py-4 font-semibold">
                            {{ $criterion->criterion_code }}
                        </td>

                        <td class="px-5 py-4">
                            {{ $criterion->name }}
                        </td>

                        <td class="px-5 py-4">
                            <span class="px-3 py-1 rounded-full text-xs font-medium
                                {{ $criterion->attribute === 'benefit'
                                    ? 'bg-green-100 text-green-700'
                                    : 'bg-amber-100 text-amber-700' }}">
                                {{ ucfirst($criterion->attribute) }}
                            </span>
                        </td>

                        <td class="px-5 py-4 font-semibold">
                            {{ number_format($criterion->weight, 2, ',', '.') }}%
                        </td>

                        <td class="px-5 py-4 text-slate-500">
                            {{ $criterion->description ?: '-' }}
                        </td>

                        <td class="px-5 py-4 text-center whitespace-nowrap">
                            <button type="button"
                                    onclick="document.getElementById('edit-criterion-{{ $criterion->id }}').classList.toggle('hidden')"
                                    class="text-blue-600 hover:underline mr-2">
                                Edit
                            </button>

                            <button type="button"
                                    data-delete-url="{{ route('recruitment-batches.criteria.destroy', [$recruitmentBatch, $criterion]) }}"
                                    data-delete-name="{{ $criterion->name }}"
                                    class="text-red-600 hover:underline">
                                Delete
                            </button>
                        </td>
                    </tr>

                    {{-- FORM EDIT --}}
                    <tr id="edit-criterion-{{ $criterion->id }}" class="hidden bg-blue-50">
                        <td colspan="6" class="px-5 py-5">
                            <form action="{{ route('recruitment-batches.criteria.update', [$recruitmentBatch, $criterion]) }}"
                                  method="POST">
                                @csrf
                                @method('PUT')

                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                                    <div>
                                        <label class="block text-xs font-medium mb-1">Kode</label>
                                        <input type="text"
                                               name="criterion_code"
                                               value="{{ $criterion->criterion_code }}"
                                               required
                                               class="w-full rounded-lg border border-slate-300 px-3 py-2">
                                    </div>

                                    <div>
                                        <label class="block text-xs font-medium mb-1">Nama Kriteria</label>
                                        <input type="text"
                                               name="name"
                                               value="{{ $criterion->name }}"
                                               required
                                               class="w-full rounded-lg border border-slate-300 px-3 py-2">
                                    </div>

                                    <div>
                                        <label class="block text-xs font-medium mb-1">Atribut</label>
                                        <select name="attribute"
                                                required
                                                class="w-full rounded-lg border border-slate-300 px-3 py-2">
                                            <option value="benefit" {{ $criterion->attribute === 'benefit' ? 'selected' : '' }}>Benefit</option>
                                            <option value="cost" {{ $criterion->attribute === 'cost' ? 'selected' : '' }}>Cost</option>
                                        </select>
                                    </div>

                                    <div>
                                        <label class="block text-xs font-medium mb-1">Bobot (%)</label>
                                        <input type="number"
                                               name="weight"
                                               value="{{ $criterion->weight }}"
                                               min="0.01"
                                               max="100"
                                               step="0.01"
                                               required
                                               class="w-full rounded-lg border border-slate-300 px-3 py-2">
                                    </div>

                                    <div class="md:col-span-2 lg:col-span-4">
                                        <label class="block text-xs font-medium mb-1">Deskripsi</label>
                                        <textarea name="description"
                                                  rows="2"
                                                  class="w-full rounded-lg border border-slate-300 px-3 py-2">{{ $criterion->description }}</textarea>
                                    </div>
                                </div>

                                <div class="flex gap-2 mt-4">
                                    <button type="submit"
                                            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm">
                                        Simpan Perubahan
                                    </button>

                                    <button type="button"
                                            onclick="document.getElementById('edit-criterion-{{ $criterion->id }}').classList.add('hidden')"
                                            class="bg-slate-200 hover:bg-slate-300 text-slate-700 px-4 py-2 rounded-lg text-sm">
                                        Batal
                                    </button>
                                </div>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center px-6 py-10 text-slate-400">
                            Belum ada kriteria untuk batch ini.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
