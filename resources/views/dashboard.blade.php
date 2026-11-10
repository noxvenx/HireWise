@extends('layouts.app')

@section('title', 'Dashboard')

@section('page-title', 'Dashboard')

@section('content')

    <div class="mb-8">
        <h1 class="text-2xl font-bold text-slate-800">
            Halo, {{ auth()->user()->name }}! 👋
        </h1>

        <p class="text-slate-500 mt-1">
            Selamat datang kembali di HireWise.
        </p>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5">

        @foreach([
            ['label' => 'Recruitment Batch', 'value' => '0'],
            ['label' => 'Total Candidates', 'value' => '0'],
            ['label' => 'Pending Screening', 'value' => '0'],
            ['label' => 'Assessment Completed', 'value' => '0'],
        ] as $stat)

            <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-100">
                <p class="text-sm text-slate-500">{{ $stat['label'] }}</p>

                <h3 class="text-3xl font-bold text-slate-800 mt-3">
                    {{ $stat['value'] }}
                </h3>
            </div>

        @endforeach

    </div>

    <div class="bg-white rounded-xl p-8 mt-8 shadow-sm border border-slate-100">
        <h3 class="text-lg font-bold text-slate-800 mb-2">
            Welcome to HireWise
        </h3>

        <p class="text-slate-500 leading-relaxed">
            Kelola proses rekrutmen, penilaian kandidat, dan pengambilan
            keputusan menggunakan metode Simple Additive Weighting (SAW).
        </p>
    </div>

@endsection
