@extends('layouts.app')

@section('title', 'Edit Kandidat')
@section('page-title', 'Edit Kandidat')

@section('content')

<div class="max-w-8xl mx-auto space-y-6">

    <div>
        <a href="{{ route('candidates.show', $candidate) }}"
           class="text-sm text-blue-600 hover:text-blue-800">
            ← Kembali ke Detail Kandidat
        </a>

        <h2 class="text-xl font-bold text-slate-800 mt-3">
            Edit Kandidat
        </h2>

        <p class="text-sm text-slate-500 mt-1">
            Perbarui informasi kandidat {{ $candidate->full_name }}.
        </p>
    </div>

    @include('candidates._form', ['candidate' => $candidate])

</div>

@endsection
