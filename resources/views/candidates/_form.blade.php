@php
    $isEdit = $candidate !== null;
@endphp

<form
    action="{{ $isEdit ? route('candidates.update', $candidate) : route('candidates.store') }}"
    method="POST"
    class="bg-white border border-slate-200 rounded-xl shadow-sm"
>
    @csrf

    @if($isEdit)
        @method('PUT')
    @endif

    <div class="p-6 space-y-6">

        @if($isEdit)
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">
                    Kode Kandidat
                </label>
                <input
                    type="text"
                    value="{{ $candidate->candidate_code }}"
                    disabled
                    class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-sm text-slate-500"
                >
                <p class="text-xs text-slate-400 mt-1">
                    Kode kandidat dibuat otomatis dan tidak dapat diubah.
                </p>
            </div>
        @endif

        {{-- Identitas --}}
        <div>
            <h3 class="font-semibold text-slate-800">Informasi Identitas</h3>
            <p class="text-xs text-slate-500 mt-1">Informasi dasar kandidat.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-slate-700 mb-1">
                    Nama Lengkap <span class="text-red-500">*</span>
                </label>
                <input
                    type="text"
                    name="full_name"
                    value="{{ old('full_name', $candidate?->full_name) }}"
                    required
                    class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                >
                @error('full_name')
                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Email</label>
                <input
                    type="email"
                    name="email"
                    value="{{ old('email', $candidate?->email) }}"
                    class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                >
                @error('email')
                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Nomor Telepon</label>
                <input
                    type="text"
                    name="phone"
                    value="{{ old('phone', $candidate?->phone) }}"
                    class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                >
                @error('phone')
                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Jenis Kelamin</label>
                <select
                    name="gender"
                    class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                >
                    <option value="">Pilih jenis kelamin</option>
                    <option value="male" @selected(old('gender', $candidate?->gender) === 'male')>
                        Laki-laki
                    </option>
                    <option value="female" @selected(old('gender', $candidate?->gender) === 'female')>
                        Perempuan
                    </option>
                </select>
                @error('gender')
                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Tanggal Lahir</label>
                <input
                    type="date"
                    name="birth_date"
                    value="{{ old('birth_date', $candidate?->birth_date?->format('Y-m-d')) }}"
                    class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                >
                @error('birth_date')
                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-slate-700 mb-1">Alamat</label>
                <textarea
                    name="address"
                    rows="3"
                    class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                >{{ old('address', $candidate?->address) }}</textarea>
                @error('address')
                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

        </div>

        <div class="border-t border-slate-100"></div>

        {{-- Pendidikan --}}
        <div>
            <h3 class="font-semibold text-slate-800">Informasi Pendidikan</h3>
            <p class="text-xs text-slate-500 mt-1">Riwayat pendidikan terakhir kandidat.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Pendidikan Terakhir</label>
                <select
                    name="education"
                    class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                >
                    <option value="">Pilih pendidikan</option>
                    @foreach(['SMA/SMK', 'D1', 'D2', 'D3', 'D4', 'S1', 'S2', 'S3'] as $level)
                        <option value="{{ $level }}"
                            @selected(old('education', $candidate?->education) === $level)>
                            {{ $level }}
                        </option>
                    @endforeach
                </select>
                @error('education')
                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Tahun Lulus</label>
                <input
                    type="number"
                    name="graduation_year"
                    min="1950"
                    max="{{ date('Y') + 5 }}"
                    value="{{ old('graduation_year', $candidate?->graduation_year) }}"
                    class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                >
                @error('graduation_year')
                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Institusi / Sekolah / Universitas</label>
                <input
                    type="text"
                    name="institution"
                    value="{{ old('institution', $candidate?->institution) }}"
                    class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                >
                @error('institution')
                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Jurusan</label>
                <input
                    type="text"
                    name="major"
                    value="{{ old('major', $candidate?->major) }}"
                    class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                >
                @error('major')
                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

        </div>

        <div class="border-t border-slate-100"></div>

        {{-- Catatan --}}
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Catatan HRD</label>
            <textarea
                name="notes"
                rows="3"
                placeholder="Catatan tambahan mengenai kandidat..."
                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
            >{{ old('notes', $candidate?->notes) }}</textarea>
            @error('notes')
                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

    </div>

    <div class="flex justify-end gap-3 border-t border-slate-100 px-6 py-4">
        <a
            href="{{ $isEdit ? route('candidates.show', $candidate) : route('candidates.index') }}"
            class="px-4 py-2 rounded-lg text-sm font-medium text-slate-600 hover:bg-slate-100"
        >
            Batal
        </a>

        <button
            type="submit"
            class="px-5 py-2 rounded-lg bg-blue-600 text-white text-sm font-semibold hover:bg-blue-700"
        >
            {{ $isEdit ? 'Simpan Perubahan' : 'Simpan Kandidat' }}
        </button>
    </div>
</form>
