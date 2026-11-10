@extends('layouts.app')

@section('title', 'Administrative Screening')
@section('page-title', 'Administrative Screening')

@section('content')

<div class="mb-6">
    <h1 class="text-2xl font-bold text-slate-800">
        Administrative Screening
    </h1>
    <p class="text-sm text-slate-500 mt-1">
        Periksa kelengkapan administrasi dan tentukan kelayakan kandidat.
    </p>
</div>

{{-- RINGKASAN STATUS --}}
<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
    @php
        $totalPending = \App\Models\RecruitmentApplication::where('screening_status', 'pending')->count();
        $totalPassed = \App\Models\RecruitmentApplication::where('screening_status', 'passed')->count();
        $totalFailed = \App\Models\RecruitmentApplication::where('screening_status', 'failed')->count();
    @endphp

    <div class="bg-white border border-slate-100 rounded-xl p-5 shadow-sm">
        <p class="text-sm text-slate-500">Pending</p>
        <p class="text-2xl font-bold text-amber-600 mt-1">{{ $totalPending }}</p>
    </div>

    <div class="bg-white border border-slate-100 rounded-xl p-5 shadow-sm">
        <p class="text-sm text-slate-500">Passed</p>
        <p class="text-2xl font-bold text-green-600 mt-1">{{ $totalPassed }}</p>
    </div>

    <div class="bg-white border border-slate-100 rounded-xl p-5 shadow-sm">
        <p class="text-sm text-slate-500">Failed</p>
        <p class="text-2xl font-bold text-red-600 mt-1">{{ $totalFailed }}</p>
    </div>
</div>

{{-- FILTER --}}
<div class="bg-white border border-slate-100 rounded-xl p-5 shadow-sm mb-6">

    <form action="{{ route('administrative-screening.index') }}"
          method="GET"
          class="grid grid-cols-1 md:grid-cols-4 gap-4">

        <div>
            <label class="block text-sm font-medium text-slate-700 mb-2">
                Recruitment Batch
            </label>

            <select name="batch"
                    class="w-full rounded-lg border border-slate-300 px-3 py-2.5">
                <option value="">Semua Batch</option>

                @foreach($batches as $batch)
                    <option value="{{ $batch->id }}"
                        {{ request('batch') == $batch->id ? 'selected' : '' }}>
                        {{ $batch->batch_code }} - {{ $batch->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-700 mb-2">
                Status Screening
            </label>

            <select name="status"
                    class="w-full rounded-lg border border-slate-300 px-3 py-2.5">
                <option value="">Semua Status</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>
                    Pending
                </option>
                <option value="passed" {{ request('status') == 'passed' ? 'selected' : '' }}>
                    Passed
                </option>
                <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>
                    Failed
                </option>
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-700 mb-2">
                Cari Kandidat
            </label>

            <input type="text"
                   name="search"
                   value="{{ request('search') }}"
                   placeholder="Nama atau kode kandidat..."
                   class="w-full rounded-lg border border-slate-300 px-3 py-2.5">
        </div>

        <div class="flex items-end gap-2">
            <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-lg font-medium">
                Filter
            </button>

            <a href="{{ route('administrative-screening.index') }}"
               class="bg-slate-200 hover:bg-slate-300 text-slate-700 px-5 py-2.5 rounded-lg">
                Reset
            </a>
        </div>

    </form>
</div>

{{-- DAFTAR KANDIDAT --}}
<div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden">

    <div class="p-5 border-b border-slate-100">
        <h2 class="font-semibold text-slate-800">
            Daftar Kandidat
        </h2>
        <p class="text-sm text-slate-500 mt-1">
            Total {{ $applications->total() }} data lamaran.
        </p>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead class="bg-slate-50 text-slate-600">
                <tr>
                    <th class="px-5 py-4">Kode Lamaran</th>
                    <th class="px-5 py-4">Kandidat</th>
                    <th class="px-5 py-4">Recruitment Batch</th>
                    <th class="px-5 py-4">Status Saat Ini</th>
                    <th class="px-5 py-4">Hasil Screening</th>
                    <th class="px-5 py-4">Aksi</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-slate-100">

                @forelse($applications as $application)

                    @php
                        $statusStyle = [
                            'pending' => 'bg-amber-100 text-amber-700',
                            'passed' => 'bg-green-100 text-green-700',
                            'failed' => 'bg-red-100 text-red-700',
                        ];
                    @endphp

                    <tr class="hover:bg-slate-50 align-top">

                        <td class="px-5 py-4 font-medium whitespace-nowrap">
                            {{ $application->application_code }}
                            <p class="text-xs text-slate-400 font-normal mt-1">
                                {{ $application->applied_at?->format('d/m/Y') ?? '-' }}
                            </p>
                        </td>

                        <td class="px-5 py-4">
                            <p class="font-medium text-slate-800">
                                {{ $application->candidate->full_name }}
                            </p>
                            <p class="text-xs text-slate-500 mt-1">
                                {{ $application->candidate->candidate_code }}
                            </p>
                            <p class="text-xs text-slate-500">
                                {{ $application->candidate->email ?? '-' }}
                            </p>
                        </td>

                        <td class="px-5 py-4">
                            <p class="font-medium">
                                {{ $application->recruitmentBatch->name }}
                            </p>
                            <p class="text-xs text-slate-500 mt-1">
                                {{ $application->recruitmentBatch->batch_code }}
                            </p>
                        </td>

                        <td class="px-5 py-4">
                            <span class="px-3 py-1 rounded-full text-xs font-medium {{ $statusStyle[$application->screening_status] ?? 'bg-slate-100 text-slate-700' }}">
                                {{ ucfirst($application->screening_status) }}
                            </span>

                            @if($application->screened_at)
                                <p class="text-xs text-slate-400 mt-2">
                                    {{ $application->screened_at->format('d/m/Y H:i') }}
                                </p>
                                <p class="text-xs text-slate-400">
                                    Oleh: {{ $application->screener?->name ?? '-' }}
                                </p>
                            @endif
                        </td>

                        <td class="px-5 py-4 min-w-64">
                            <form id="screening-form-{{ $application->id }}"
                                  action="{{ route('administrative-screening.update', $application) }}"
                                  method="POST">
                                @csrf
                                @method('PATCH')

                                <select name="screening_status"
                                        required
                                        class="w-full rounded-lg border border-slate-300 px-3 py-2 mb-2">
                                    <option value="pending"
                                        {{ $application->screening_status == 'pending' ? 'selected' : '' }}>
                                        Pending
                                    </option>

                                    <option value="passed"
                                        {{ $application->screening_status == 'passed' ? 'selected' : '' }}>
                                        Passed
                                    </option>

                                    <option value="failed"
                                        {{ $application->screening_status == 'failed' ? 'selected' : '' }}>
                                        Failed
                                    </option>
                                </select>

                                <textarea name="screening_notes"
                                          rows="2"
                                          class="w-full rounded-lg border border-slate-300 px-3 py-2"
                                          placeholder="Catatan hasil pemeriksaan...">{{ $application->screening_notes }}</textarea>
                            </form>
                        </td>

                        <td class="px-5 py-4">
                            <button type="submit"
                                    form="screening-form-{{ $application->id }}"
                                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-xs font-medium whitespace-nowrap">
                                Simpan Hasil
                            </button>
                        </td>

                    </tr>

                @empty
                    <tr>
                        <td colspan="6"
                            class="text-center px-6 py-10 text-slate-400">
                            Belum ada kandidat yang perlu diperiksa.
                        </td>
                    </tr>
                @endforelse

            </tbody>
        </table>
    </div>

    <div class="px-5 py-4 border-t border-slate-100">
        {{ $applications->links() }}
    </div>

</div>

@endsection
