@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

    {{-- Welcome Section --}}
    <div class="mb-8 flex flex-col justify-between gap-4 sm:flex-row sm:items-center">

        <div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900">
                Dashboard
            </h1>

            <p class="mt-1 text-sm text-slate-500">
                Welcome back! Here's what's happening with your recruitment.
            </p>
        </div>

        <button class="rounded-lg bg-indigo-600 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700">
            + Create Recruitment
        </button>

    </div>


    {{-- Statistics --}}
    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 xl:grid-cols-4">

        @foreach ([
            ['title' => 'Active Batches', 'value' => '24', 'change' => 'Recruitment campaigns'],
            ['title' => 'Total Candidates', 'value' => '1,284', 'change' => 'Across all batches'],
            ['title' => 'Pending Assessments', 'value' => '156', 'change' => 'Waiting for evaluation'],
            ['title' => 'Recommended', 'value' => '42', 'change' => 'Based on SAW results'],
        ] as $stat)

            <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">

                <p class="text-sm font-medium text-slate-500">
                    {{ $stat['title'] }}
                </p>

                <h2 class="mt-3 text-3xl font-bold tracking-tight text-slate-900">
                    {{ $stat['value'] }}
                </h2>

                <p class="mt-2 text-xs text-slate-400">
                    {{ $stat['change'] }}
                </p>

            </div>

        @endforeach

    </div>


    {{-- Main Dashboard Content --}}
    <div class="mt-8 grid grid-cols-1 gap-6 xl:grid-cols-3">

        {{-- Recruitment Overview --}}
        <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm xl:col-span-2">

            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-base font-semibold text-slate-900">
                        Recruitment Overview
                    </h3>

                    <p class="mt-1 text-sm text-slate-500">
                        Overview of your recruitment activities
                    </p>
                </div>

                <button class="text-sm font-medium text-indigo-600 hover:text-indigo-700">
                    View all
                </button>
            </div>

            <div class="mt-8 flex h-64 items-center justify-center rounded-lg bg-slate-50">
                <p class="text-sm text-slate-400">
                    Recruitment chart will be displayed here
                </p>
            </div>

        </div>


        {{-- Recent Activity --}}
        <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">

            <h3 class="text-base font-semibold text-slate-900">
                Recent Activity
            </h3>

            <p class="mt-1 text-sm text-slate-500">
                Latest workspace activities
            </p>

            <div class="mt-6 space-y-6">

                @foreach ([
                    ['title' => 'New recruitment batch created', 'time' => '2 hours ago'],
                    ['title' => 'Candidate assessment updated', 'time' => '4 hours ago'],
                    ['title' => 'SAW calculation completed', 'time' => 'Yesterday'],
                ] as $activity)

                    <div class="flex gap-3">
                        <div class="mt-1 h-2.5 w-2.5 shrink-0 rounded-full bg-indigo-500"></div>

                        <div>
                            <p class="text-sm font-medium text-slate-700">
                                {{ $activity['title'] }}
                            </p>

                            <p class="mt-1 text-xs text-slate-400">
                                {{ $activity['time'] }}
                            </p>
                        </div>
                    </div>

                @endforeach

            </div>

        </div>

    </div>

@endsection