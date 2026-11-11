<div class="grid grid-cols-1 md:grid-cols-2 gap-5">

    <div>
        <label class="block text-sm font-medium mb-2">Kode Batch</label>
        <input type="text" name="batch_code"
            value="{{ old('batch_code', $batch->batch_code ?? '') }}"
            placeholder="Contoh: REC-2026-001"
            required class="w-full border border-slate-300 rounded-lg px-4 py-3">
        @error('batch_code')
            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label class="block text-sm font-medium mb-2">Nama Batch</label>
        <input type="text" name="name"
            value="{{ old('name', $batch->name ?? '') }}"
            placeholder="Contoh: Rekrutmen Staff IT"
            required class="w-full border border-slate-300 rounded-lg px-4 py-3">
        @error('name')
            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label class="block text-sm font-medium mb-2">Posisi</label>
        <input type="text" name="position"
            value="{{ old('position', $batch->position ?? '') }}"
            placeholder="Contoh: Backend Developer"
            required class="w-full border border-slate-300 rounded-lg px-4 py-3">
        @error('position')
            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label class="block text-sm font-medium mb-2">Kuota</label>
        <input type="number" name="quota"
            value="{{ old('quota', $batch->quota ?? '') }}"
            placeholder="Contoh: 3"
            min="1" class="w-full border border-slate-300 rounded-lg px-4 py-3">
        @error('quota')
            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label class="block text-sm font-medium mb-2">Jumlah Kandidat Cadangan</label>
        <input type="number" name="reserve_quota"
            value="{{ old('reserve_quota', $batch->reserve_quota ?? 0) }}"
            placeholder="Contoh: 2"
            min="0"
            required
            class="w-full border border-slate-300 rounded-lg px-4 py-3">

        <p class="text-xs text-slate-500 mt-1">
            Jumlah kandidat cadangan yang ingin disiapkan HRD. Sistem akan menentukan rekomendasi berdasarkan ranking SAW.
        </p>

        @error('reserve_quota')
            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label class="block text-sm font-medium mb-2">Tanggal Mulai</label>
        <input type="date" name="start_date"
            value="{{ old('start_date', isset($batch) && $batch->start_date ? $batch->start_date->format('Y-m-d') : '') }}"
            class="w-full border border-slate-300 rounded-lg px-4 py-3">
        @error('start_date')
            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label class="block text-sm font-medium mb-2">Tanggal Selesai</label>
        <input type="date" name="end_date"
            value="{{ old('end_date', isset($batch) && $batch->end_date ? $batch->end_date->format('Y-m-d') : '') }}"
            class="w-full border border-slate-300 rounded-lg px-4 py-3">
        @error('end_date')
            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label class="block text-sm font-medium mb-2">Status</label>
        <select name="status" required class="w-full border border-slate-300 rounded-lg px-4 py-3">
            @foreach(['draft', 'active', 'closed', 'completed'] as $status)
                <option value="{{ $status }}"
                    @selected(old('status', $batch->status ?? 'draft') === $status)>
                    {{ ucfirst($status) }}
                </option>
            @endforeach
        </select>
        @error('status')
            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
        @enderror
    </div>

</div>

<div class="mt-5">
    <label class="block text-sm font-medium mb-2">Deskripsi</label>
    <textarea name="description" rows="4"
        class="w-full border border-slate-300 rounded-lg px-4 py-3">{{ old('description', $batch->description ?? '') }}</textarea>
    @error('description')
        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
    @enderror
</div>

<div class="flex justify-end gap-3 mt-6">
    <a href="{{ route('recruitment-batches.index') }}"
       class="px-5 py-3 rounded-lg border border-slate-300 text-slate-600">
        Batal
    </a>

    <button type="submit"
        class="px-5 py-3 rounded-lg bg-blue-600 text-white font-medium hover:bg-blue-700">
        Simpan Perubahan
    </button>
</div>