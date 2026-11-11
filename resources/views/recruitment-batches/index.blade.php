@extends('layouts.app')

@section('title', 'Recruitment Batch')
@section('page-title', 'Recruitment Batch')

@section('content')

@php
    $statusStyle = [
        'draft' => 'bg-slate-100 text-slate-600 ring-slate-200',
        'active' => 'bg-emerald-50 text-emerald-700 ring-emerald-200',
        'closed' => 'bg-amber-50 text-amber-700 ring-amber-200',
        'completed' => 'bg-blue-50 text-blue-700 ring-blue-200',
    ];

    $statusLabel = [
        'draft' => 'Draft',
        'active' => 'Active',
        'closed' => 'Closed',
        'completed' => 'Completed',
    ];

    $statusDot = [
        'draft' => 'bg-slate-400',
        'active' => 'bg-emerald-500',
        'closed' => 'bg-amber-500',
        'completed' => 'bg-blue-500',
    ];
@endphp

{{-- Page Header --}}
<div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
    <div>
        <div class="flex items-center gap-2">
            <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-blue-100 text-blue-700">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                    <rect x="3" y="5" width="18" height="16" rx="2"/>
                    <path stroke-linecap="round" d="M7 3v4m10-4v4M3 10h18"/>
                </svg>
            </span>
            <h1 class="text-2xl font-bold tracking-tight text-slate-800">
                Recruitment Batch
            </h1>
        </div>
        <p class="mt-2 text-sm text-slate-500">
            Kelola seluruh proses rekrutmen berdasarkan batch.
        </p>
    </div>

    <a href="{{ route('recruitment-batches.create') }}"
       class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white shadow-sm shadow-blue-600/20 transition hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 sm:w-auto">
        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14m-7-7h14"/>
        </svg>
        Buat Batch
    </a>
</div>

{{-- Batch List Container --}}
<div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

    {{-- Section Header --}}
    <div class="border-b border-slate-100 px-5 py-4 sm:px-6">
        <h2 class="text-base font-bold text-slate-800">
            Daftar Recruitment Batch
        </h2>
        <p class="mt-1 text-xs text-slate-500">
            Informasi batch rekrutmen yang telah dibuat.
        </p>
    </div>

    {{-- DESKTOP TABLE --}}
    <div class="hidden lg:block">
        @if($batches->isNotEmpty())
            <table class="w-full table-fixed text-left text-sm">
                <thead class="bg-slate-50 text-[11px] font-bold uppercase tracking-wider text-slate-500">
                    <tr>
                        <th class="w-[13%] px-4 py-4">Kode Batch</th>
                        <th class="w-[17%] px-4 py-4">Nama Batch</th>
                        <th class="w-[13%] px-4 py-4">Posisi</th>
                        <th class="w-[7%] px-4 py-4 text-center">Kuota</th>
                        <th class="w-[13%] px-4 py-4">Periode</th>
                        <th class="w-[11%] px-4 py-4">Status</th>
                        <th class="w-[12%] px-4 py-4">Dibuat Oleh</th>
                        <th class="w-[14%] px-4 py-4 text-center">Aksi</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100">
                    @foreach($batches as $batch)
                        <tr class="transition hover:bg-slate-50/80">
                            <td class="break-words px-4 py-4">
                                <span class="font-semibold text-blue-700">
                                    {{ $batch->batch_code }}
                                </span>
                            </td>

                            <td class="break-words px-4 py-4 font-semibold text-slate-800">
                                {{ $batch->name }}
                            </td>

                            <td class="break-words px-4 py-4 text-slate-600">
                                {{ $batch->position }}
                            </td>

                            <td class="px-4 py-4 text-center text-slate-600">
                                {{ $batch->quota ?? '-' }}
                            </td>

                            <td class="px-4 py-4 text-xs text-slate-600">
                                <div>{{ $batch->start_date?->format('d/m/Y') ?? '-' }}</div>
                                <div class="mt-1 text-slate-400">
                                    s/d {{ $batch->end_date?->format('d/m/Y') ?? '-' }}
                                </div>
                            </td>

                            <td class="px-4 py-4">
                                <span class="inline-flex items-center gap-1.5 rounded-full px-2 py-1 text-[10px] font-bold ring-1 ring-inset {{ $statusStyle[$batch->status] ?? 'bg-slate-100 text-slate-600 ring-slate-200' }}">
                                    <span class="h-1.5 w-1.5 rounded-full {{ $statusDot[$batch->status] ?? 'bg-slate-400' }}"></span>
                                    {{ $statusLabel[$batch->status] ?? ucfirst($batch->status) }}
                                </span>
                            </td>

                            <td class="break-words px-4 py-4 text-slate-600">
                                {{ $batch->creator?->name ?? '-' }}
                            </td>

                            <td class="px-3 py-4">
                                <div class="flex flex-wrap items-center justify-center gap-1">
                                    <a href="{{ route('recruitment-batches.show', $batch) }}"
                                       class="rounded-lg border border-indigo-200 bg-indigo-50 px-2 py-1.5 text-[11px] font-semibold text-indigo-700 transition hover:bg-indigo-100">
                                        Detail
                                    </a>

                                    <a href="{{ route('recruitment-batches.edit', $batch) }}"
                                       class="rounded-lg border border-blue-200 bg-blue-50 px-2 py-1.5 text-[11px] font-semibold text-blue-700 transition hover:bg-blue-100">
                                        Edit
                                    </a>

                                    <a href="{{ route('recruitment-batches.destroy', $batch) }}"
                                       class="rounded-lg border border-red-200 bg-red-50 px-2 py-1.5 text-[11px] font-semibold text-red-600 transition hover:bg-red-100"
                                       data-delete-url="{{ route('recruitment-batches.destroy', $batch) }}"
                                       data-delete-name="{{ $batch->name }}">
                                        Delete
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="px-6 py-16 text-center">
                <p class="text-sm font-semibold text-slate-700">Belum ada Recruitment Batch</p>
                <p class="mt-1 text-xs text-slate-500">Buat batch rekrutmen pertama untuk memulai proses.</p>
            </div>
        @endif
    </div>

    {{-- TABLET & MOBILE ACCORDION --}}
    <div class="space-y-3 p-4 lg:hidden">
        @forelse($batches as $batch)
            <div class="overflow-hidden rounded-xl border border-slate-200 bg-white">
                <details class="group">
                    <summary class="flex cursor-pointer list-none items-center justify-between gap-3 p-4">
                        <div class="min-w-0 flex-1">
                            <div class="flex flex-wrap items-center gap-2">
                                <span class="text-xs font-bold text-blue-700">
                                    {{ $batch->batch_code }}
                                </span>

                                <span class="inline-flex items-center gap-1 rounded-full px-2 py-0.5 text-[10px] font-bold ring-1 ring-inset {{ $statusStyle[$batch->status] ?? 'bg-slate-100 text-slate-600 ring-slate-200' }}">
                                    <span class="h-1.5 w-1.5 rounded-full {{ $statusDot[$batch->status] ?? 'bg-slate-400' }}"></span>
                                    {{ $statusLabel[$batch->status] ?? ucfirst($batch->status) }}
                                </span>
                            </div>

                            <p class="mt-2 truncate text-sm font-bold text-slate-800">
                                {{ $batch->name }}
                            </p>

                            <p class="mt-1 text-xs text-slate-500">
                                {{ $batch->position }}
                            </p>
                        </div>

                        <svg class="h-5 w-5 shrink-0 text-slate-400 transition-transform group-open:rotate-180"
                             fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m6 9 6 6 6-6"/>
                        </svg>
                    </summary>

                    <div class="border-t border-slate-100 bg-slate-50/70 p-4">
                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <div>
                                <p class="text-[11px] text-slate-400">Kuota</p>
                                <p class="mt-1 font-semibold text-slate-700">{{ $batch->quota ?? '-' }}</p>
                            </div>

                            <div>
                                <p class="text-[11px] text-slate-400">Dibuat Oleh</p>
                                <p class="mt-1 break-words font-semibold text-slate-700">{{ $batch->creator?->name ?? '-' }}</p>
                            </div>

                            <div class="col-span-2">
                                <p class="text-[11px] text-slate-400">Periode Rekrutmen</p>
                                <p class="mt-1 font-semibold text-slate-700">
                                    {{ $batch->start_date?->format('d/m/Y') ?? '-' }}
                                    <span class="font-normal text-slate-400">s/d</span>
                                    {{ $batch->end_date?->format('d/m/Y') ?? '-' }}
                                </p>
                            </div>
                        </div>

                        <div class="mt-4 flex flex-wrap gap-2">
                            <a href="{{ route('recruitment-batches.show', $batch) }}"
                               class="flex-1 rounded-lg border border-indigo-200 bg-indigo-50 px-3 py-2 text-center text-xs font-semibold text-indigo-700 transition hover:bg-indigo-100">
                                Detail
                            </a>

                            <a href="{{ route('recruitment-batches.edit', $batch) }}"
                               class="flex-1 rounded-lg border border-blue-200 bg-blue-50 px-3 py-2 text-center text-xs font-semibold text-blue-700 transition hover:bg-blue-100">
                                Edit
                            </a>

                            <a href="{{ route('recruitment-batches.destroy', $batch) }}"
                               class="flex-1 rounded-lg border border-red-200 bg-red-50 px-3 py-2 text-center text-xs font-semibold text-red-600 transition hover:bg-red-100"
                               data-delete-url="{{ route('recruitment-batches.destroy', $batch) }}"
                               data-delete-name="{{ $batch->name }}">
                                Delete
                            </a>
                        </div>
                    </div>
                </details>
            </div>
        @empty
            <div class="py-12 text-center">
                <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-slate-100 text-slate-400">
                    <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.6">
                        <rect x="3" y="5" width="18" height="16" rx="2"/>
                        <path stroke-linecap="round" d="M7 3v4m10-4v4M3 10h18"/>
                    </svg>
                </div>
                <p class="mt-4 text-sm font-semibold text-slate-700">Belum ada Recruitment Batch</p>
                <p class="mt-1 text-xs text-slate-500">Buat batch rekrutmen pertama untuk memulai proses.</p>
            </div>
        @endforelse
    </div>

    {{-- Shared Pagination --}}
    <div class="border-t border-slate-100 px-5 py-4 sm:px-6">
        {{ $batches->links() }}
    </div>

</div>

@endsection
