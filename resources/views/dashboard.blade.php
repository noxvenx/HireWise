
@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

@php
    $pipelineTotal = $statistics['pending_screening']
        + $statistics['passed_screening']
        + $statistics['failed_screening'];

    $pendingPercentage = $pipelineTotal > 0
        ? ($statistics['pending_screening'] / $pipelineTotal) * 100 : 0;

    $passedPercentage = $pipelineTotal > 0
        ? ($statistics['passed_screening'] / $pipelineTotal) * 100 : 0;

    $failedPercentage = $pipelineTotal > 0
        ? ($statistics['failed_screening'] / $pipelineTotal) * 100 : 0;
@endphp

<div class="space-y-8">

    {{-- Welcome Header --}}
    <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-center">
        <div>
            <p class="text-sm font-medium text-indigo-600">
                Recruitment Management System
            </p>

            <h1 class="mt-1 text-2xl font-bold tracking-tight text-slate-900">
                Dashboard
            </h1>

            <p class="mt-1 text-sm text-slate-500">
                Monitor your recruitment activities and candidate progress.
            </p>
        </div>

        <a href="{{ route('recruitment-batches.create') }}"
            class="inline-flex items-center justify-center gap-2 rounded-lg bg-indigo-600 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700">
            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 4v16m8-8H4"/>
            </svg>
            Create Recruitment
        </a>
    </div>


    {{-- Statistics Cards --}}
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">

        {{-- Active Batches --}}
        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <div class="flex h-11 w-11 items-center justify-center rounded-lg bg-indigo-50 text-indigo-600">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                            d="M8 7V3m8 4V3M4 11h16M5 5h14a1 1 0 011 1v13a1 1 0 01-1 1H5a1 1 0 01-1-1V6a1 1 0 011-1z"/>
                    </svg>
                </div>

                <span class="rounded-full bg-green-50 px-2.5 py-1 text-xs font-medium text-green-700">
                    Active
                </span>
            </div>

            <p class="mt-5 text-sm font-medium text-slate-500">
                Active Batches
            </p>

            <h2 class="mt-1 text-3xl font-bold tracking-tight text-slate-900">
                {{ number_format($statistics['active_batches']) }}
            </h2>

            <p class="mt-1 text-xs text-slate-400">
                Currently running recruitment
            </p>
        </div>


        {{-- Total Candidates --}}
        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
            <div class="flex h-11 w-11 items-center justify-center rounded-lg bg-blue-50 text-blue-600">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                        d="M16 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2m6-10a4 4 0 100-8 4 4 0 000 8zm10 10v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/>
                </svg>
            </div>

            <p class="mt-5 text-sm font-medium text-slate-500">
                Total Candidates
            </p>

            <h2 class="mt-1 text-3xl font-bold tracking-tight text-slate-900">
                {{ number_format($statistics['total_candidates']) }}
            </h2>

            <p class="mt-1 text-xs text-slate-400">
                Registered candidate profiles
            </p>
        </div>


        {{-- Total Applications --}}
        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
            <div class="flex h-11 w-11 items-center justify-center rounded-lg bg-violet-50 text-violet-600">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                        d="M8 7h8m-8 4h8m-8 4h5M6 3h9l5 5v13H6a2 2 0 01-2-2V5a2 2 0 012-2z"/>
                </svg>
            </div>

            <p class="mt-5 text-sm font-medium text-slate-500">
                Total Applications
            </p>

            <h2 class="mt-1 text-3xl font-bold tracking-tight text-slate-900">
                {{ number_format($statistics['total_applications']) }}
            </h2>

            <p class="mt-1 text-xs text-slate-400">
                Applications across all batches
            </p>
        </div>


        {{-- Pending Screening --}}
        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
            <div class="flex h-11 w-11 items-center justify-center rounded-lg bg-amber-50 text-amber-600">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                        d="M12 8v4l3 2m6-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>

            <p class="mt-5 text-sm font-medium text-slate-500">
                Pending Screening
            </p>

            <h2 class="mt-1 text-3xl font-bold tracking-tight text-slate-900">
                {{ number_format($statistics['pending_screening']) }}
            </h2>

            <p class="mt-1 text-xs text-slate-400">
                Waiting for administrative review
            </p>
        </div>

    </div>


    {{-- Recruitment Pipeline & Screening Summary --}}
    <div class="grid grid-cols-1 gap-6 xl:grid-cols-3">

        {{-- Pipeline --}}
        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6 xl:col-span-2">

            <div>
                <h3 class="text-base font-semibold text-slate-900">
                    Recruitment Pipeline
                </h3>

                <p class="mt-1 text-sm text-slate-500">
                    Administrative screening progress across all applications.
                </p>
            </div>

            <div class="mt-8 space-y-6">

                {{-- Pending --}}
                <div>
                    <div class="mb-2 flex items-center justify-between gap-3">
                        <div class="flex items-center gap-2">
                            <span class="h-2.5 w-2.5 rounded-full bg-amber-400"></span>
                            <span class="text-sm font-medium text-slate-700">
                                Pending Screening
                            </span>
                        </div>

                        <span class="text-sm font-semibold text-slate-900">
                            {{ number_format($statistics['pending_screening']) }}
                            <span class="font-normal text-slate-400">
                                ({{ number_format($pendingPercentage, 1) }}%)
                            </span>
                        </span>
                    </div>

                    <div class="h-3 overflow-hidden rounded-full bg-slate-100">
                        <div class="h-full rounded-full bg-amber-400 transition-all duration-500"
                            style="width: {{ $pendingPercentage }}%">
                        </div>
                    </div>
                </div>


                {{-- Passed --}}
                <div>
                    <div class="mb-2 flex items-center justify-between gap-3">
                        <div class="flex items-center gap-2">
                            <span class="h-2.5 w-2.5 rounded-full bg-green-500"></span>
                            <span class="text-sm font-medium text-slate-700">
                                Passed Screening
                            </span>
                        </div>

                        <span class="text-sm font-semibold text-slate-900">
                            {{ number_format($statistics['passed_screening']) }}
                            <span class="font-normal text-slate-400">
                                ({{ number_format($passedPercentage, 1) }}%)
                            </span>
                        </span>
                    </div>

                    <div class="h-3 overflow-hidden rounded-full bg-slate-100">
                        <div class="h-full rounded-full bg-green-500 transition-all duration-500"
                            style="width: {{ $passedPercentage }}%">
                        </div>
                    </div>
                </div>


                {{-- Failed --}}
                <div>
                    <div class="mb-2 flex items-center justify-between gap-3">
                        <div class="flex items-center gap-2">
                            <span class="h-2.5 w-2.5 rounded-full bg-red-400"></span>
                            <span class="text-sm font-medium text-slate-700">
                                Failed Screening
                            </span>
                        </div>

                        <span class="text-sm font-semibold text-slate-900">
                            {{ number_format($statistics['failed_screening']) }}
                            <span class="font-normal text-slate-400">
                                ({{ number_format($failedPercentage, 1) }}%)
                            </span>
                        </span>
                    </div>

                    <div class="h-3 overflow-hidden rounded-full bg-slate-100">
                        <div class="h-full rounded-full bg-red-400 transition-all duration-500"
                            style="width: {{ $failedPercentage }}%">
                        </div>
                    </div>
                </div>

            </div>

            <div class="mt-8 flex flex-wrap items-center justify-between gap-3 border-t border-slate-100 pt-5">
                <div>
                    <p class="text-xs text-slate-500">
                        Total applications screened
                    </p>

                    <p class="mt-1 text-xl font-bold text-slate-900">
                        {{ number_format($pipelineTotal) }}
                    </p>
                </div>

                <a href="{{ route('administrative-screening.index') }}"
                    class="inline-flex items-center gap-2 rounded-lg border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-600 transition hover:border-indigo-300 hover:text-indigo-600">
                    View Screening
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>

        </div>


        {{-- Quick Actions --}}
        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">

            <h3 class="text-base font-semibold text-slate-900">
                Quick Actions
            </h3>

            <p class="mt-1 text-sm text-slate-500">
                Common recruitment tasks.
            </p>

            <div class="mt-6 space-y-3">

                <a href="{{ route('recruitment-batches.create') }}"
                    class="group flex items-center gap-3 rounded-lg border border-slate-200 p-3 transition hover:border-indigo-300 hover:bg-indigo-50">
                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-indigo-50 text-indigo-600 transition group-hover:bg-white">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4v16m8-8H4"/>
                        </svg>
                    </div>

                    <div class="min-w-0 flex-1">
                        <p class="text-sm font-semibold text-slate-800">
                            Create Recruitment
                        </p>
                        <p class="mt-0.5 text-xs text-slate-500">
                            Open a new recruitment batch
                        </p>
                    </div>

                    <svg class="h-4 w-4 shrink-0 text-slate-400 group-hover:text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5l7 7-7 7"/>
                    </svg>
                </a>


                <a href="{{ route('candidates.create') }}"
                    class="group flex items-center gap-3 rounded-lg border border-slate-200 p-3 transition hover:border-blue-300 hover:bg-blue-50">
                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-blue-50 text-blue-600 transition group-hover:bg-white">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2m6-10a4 4 0 100-8 4 4 0 000 8zm10 10v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/>
                        </svg>
                    </div>

                    <div class="min-w-0 flex-1">
                        <p class="text-sm font-semibold text-slate-800">
                            Add Candidate
                        </p>
                        <p class="mt-0.5 text-xs text-slate-500">
                            Register a candidate profile
                        </p>
                    </div>

                    <svg class="h-4 w-4 shrink-0 text-slate-400 group-hover:text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5l7 7-7 7"/>
                    </svg>
                </a>


                <a href="{{ route('candidate-assessments.index') }}"
                    class="group flex items-center gap-3 rounded-lg border border-slate-200 p-3 transition hover:border-violet-300 hover:bg-violet-50">
                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-violet-50 text-violet-600 transition group-hover:bg-white">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m5-3v10a2 2 0 01-2 2H6a2 2 0 01-2-2V7a2 2 0 012-2h12a2 2 0 012 2z"/>
                        </svg>
                    </div>

                    <div class="min-w-0 flex-1">
                        <p class="text-sm font-semibold text-slate-800">
                            Candidate Assessment
                        </p>
                        <p class="mt-0.5 text-xs text-slate-500">
                            Evaluate qualified candidates
                        </p>
                    </div>

                    <svg class="h-4 w-4 shrink-0 text-slate-400 group-hover:text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5l7 7-7 7"/>
                    </svg>
                </a>

            </div>
        </div>

    </div>


    {{-- Recent Recruitment Batches --}}
    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">

        <div class="flex flex-col justify-between gap-3 border-b border-slate-200 px-5 py-4 sm:flex-row sm:items-center sm:px-6">
            <div>
                <h3 class="text-base font-semibold text-slate-900">
                    Recent Recruitment Batches
                </h3>

                <p class="mt-1 text-sm text-slate-500">
                    Latest recruitment campaigns in your workspace.
                </p>
            </div>

            <a href="{{ route('recruitment-batches.index') }}"
                class="inline-flex items-center gap-1 text-sm font-semibold text-indigo-600 hover:text-indigo-700">
                View all batches
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        </div>


        @if($recentBatches->isEmpty())

            <div class="px-5 py-12 text-center">
                <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-slate-100 text-slate-400">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                            d="M8 7V3m8 4V3M4 11h16M5 5h14a1 1 0 011 1v13a1 1 0 01-1 1H5a1 1 0 01-1-1V6a1 1 0 011-1z"/>
                    </svg>
                </div>

                <p class="mt-3 text-sm font-semibold text-slate-700">
                    No recruitment batches yet
                </p>

                <p class="mt-1 text-sm text-slate-500">
                    Create your first recruitment batch to get started.
                </p>

                <a href="{{ route('recruitment-batches.create') }}"
                    class="mt-4 inline-flex rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-700">
                    Create Recruitment
                </a>
            </div>

        @else

            {{-- Desktop Table --}}
            <div class="hidden overflow-x-auto md:block">
                <table class="w-full min-w-175 text-left text-sm">
                    <thead class="bg-slate-50 text-xs uppercase tracking-wide text-slate-500">
                        <tr>
                            <th class="px-6 py-3 font-semibold">Batch</th>
                            <th class="px-6 py-3 font-semibold">Position</th>
                            <th class="px-6 py-3 text-center font-semibold">Applications</th>
                            <th class="px-6 py-3 font-semibold">Period</th>
                            <th class="px-6 py-3 font-semibold">Status</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-100">
                        @foreach($recentBatches as $batch)
                            <tr class="transition hover:bg-slate-50">
                                <td class="px-6 py-4">
                                    <p class="font-semibold text-slate-800">
                                        {{ $batch->name }}
                                    </p>
                                    <p class="mt-1 text-xs text-slate-400">
                                        {{ $batch->batch_code }}
                                    </p>
                                </td>

                                <td class="px-6 py-4 text-slate-600">
                                    {{ $batch->position }}
                                </td>

                                <td class="px-6 py-4 text-center">
                                    <span class="inline-flex min-w-9 justify-center rounded-full bg-indigo-50 px-3 py-1 text-xs font-semibold text-indigo-700">
                                        {{ number_format($batch->applications_count) }}
                                    </span>
                                </td>

                                <td class="px-6 py-4 text-slate-500">
                                    <p>{{ $batch->start_date?->format('d M Y') ?? '-' }}</p>
                                    <p class="mt-1 text-xs text-slate-400">
                                        Until {{ $batch->end_date?->format('d M Y') ?? '-' }}
                                    </p>
                                </td>

                                <td class="px-6 py-4">
                                    @if($batch->status === 'active')
                                        <span class="inline-flex rounded-full bg-green-50 px-3 py-1 text-xs font-semibold text-green-700">
                                            Active
                                        </span>
                                    @elseif($batch->status === 'completed')
                                        <span class="inline-flex rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-600">
                                            Completed
                                        </span>
                                    @else
                                        <span class="inline-flex rounded-full bg-amber-50 px-3 py-1 text-xs font-semibold text-amber-700">
                                            {{ ucfirst($batch->status) }}
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>


            {{-- Mobile Cards --}}
            <div class="divide-y divide-slate-100 md:hidden">
                @foreach($recentBatches as $batch)
                    <div class="space-y-3 p-5">
                        <div class="flex items-start justify-between gap-3">
                            <div class="min-w-0">
                                <p class="truncate text-sm font-semibold text-slate-800">
                                    {{ $batch->name }}
                                </p>
                                <p class="mt-1 text-xs text-slate-400">
                                    {{ $batch->batch_code }}
                                </p>
                            </div>

                            @if($batch->status === 'active')
                                <span class="shrink-0 rounded-full bg-green-50 px-2.5 py-1 text-xs font-semibold text-green-700">
                                    Active
                                </span>
                            @elseif($batch->status === 'completed')
                                <span class="shrink-0 rounded-full bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-600">
                                    Completed
                                </span>
                            @else
                                <span class="shrink-0 rounded-full bg-amber-50 px-2.5 py-1 text-xs font-semibold text-amber-700">
                                    {{ ucfirst($batch->status) }}
                                </span>
                            @endif
                        </div>

                        <p class="text-sm text-slate-600">
                            {{ $batch->position }}
                        </p>

                        <div class="flex flex-wrap items-center justify-between gap-2 text-xs text-slate-500">
                            <span>
                                {{ number_format($batch->applications_count) }} applications
                            </span>

                            <span>
                                {{ $batch->start_date?->format('d M Y') ?? '-' }}
                                –
                                {{ $batch->end_date?->format('d M Y') ?? '-' }}
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>

        @endif

    </div>

</div>

@endsection