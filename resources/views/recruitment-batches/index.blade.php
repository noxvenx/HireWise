@extends('layouts.app')

@section('title', 'Recruitment Batch')
@section('page-title', 'Recruitment Batch')

@section('content')

<div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <h1 class="text-2xl font-bold text-slate-800">Recruitment Batch</h1>
        <p class="text-sm text-slate-500 mt-1">
            Kelola seluruh proses rekrutmen berdasarkan batch.
        </p>
    </div>

    <a href="{{ route('recruitment-batches.create') }}"
       class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-3 rounded-lg font-medium text-center">
        + Buat Batch
    </a>
</div>

@if(session('success'))
    <div class="bg-green-50 border border-green-200 text-green-700 p-4 rounded-lg mb-6">
        {{ session('success') }}
    </div>
@endif

<div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead class="bg-slate-50 text-slate-600">
                <tr>
                    <th class="px-6 py-4">Kode Batch</th>
                    <th class="px-6 py-4">Nama Batch</th>
                    <th class="px-6 py-4">Posisi</th>
                    <th class="px-6 py-4">Kuota</th>
                    <th class="px-6 py-4">Periode</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4">Dibuat Oleh</th>
                    <th class="px-6 py-4 text-center">Aksi</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-slate-100">
                @forelse($batches as $batch)
                    <tr class="hover:bg-slate-50">
                        <td class="px-6 py-4 font-medium">{{ $batch->batch_code }}</td>
                        <td class="px-6 py-4">{{ $batch->name }}</td>
                        <td class="px-6 py-4">{{ $batch->position }}</td>
                        <td class="px-6 py-4">{{ $batch->quota ?? '-' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ $batch->start_date?->format('d/m/Y') ?? '-' }}
                            -
                            {{ $batch->end_date?->format('d/m/Y') ?? '-' }}
                        </td>
                        <td class="px-6 py-4">
                            @php
                                $statusStyle = [
                                    'draft' => 'bg-slate-100 text-slate-700',
                                    'active' => 'bg-green-100 text-green-700',
                                    'closed' => 'bg-amber-100 text-amber-700',
                                    'completed' => 'bg-blue-100 text-blue-700',
                                ];
                            @endphp

                            <span class="px-3 py-1 rounded-full text-xs {{ $statusStyle[$batch->status] ?? 'bg-slate-100 text-slate-700' }}">
                                {{ ucfirst($batch->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4">{{ $batch->creator?->name ?? '-' }}</td>
                        <td class="px-6 py-4 text-center whitespace-nowrap">
                            <a href="{{ route('recruitment-batches.show', $batch) }}"
                            class="text-indigo-600 hover:underline px-1.5 py-1 rounded-lg">
                                Detail
                            </a>

                            <a href="{{ route('recruitment-batches.edit', $batch) }}"
                            class="text-blue-600 hover:underline px-1.5 py-1 rounded-lg">
                                Edit
                            </a>

                            <a href="{{ route('recruitment-batches.destroy', $batch) }}"
                            class="text-red-600 hover:underline px-1.5 py-1 rounded-lg"
                            data-delete-url="{{ route('recruitment-batches.destroy', $batch) }}"
                            data-delete-name="{{ $batch->name }}">
                                Delete
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center px-6 py-10 text-slate-400">
                            Belum ada Recruitment Batch.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="px-6 py-4 border-t border-slate-100">
        {{ $batches->links() }}
    </div>
</div>

@endsection