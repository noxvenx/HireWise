@extends('layouts.app')

@section('title', 'Tambah Kandidat')
@section('page-title', 'Tambah Kandidat')

@section('content')

<div class="max-w-8xl mx-auto space-y-6">

    <div>
        <a href="{{ route('candidates.index') }}"
           class="text-sm text-blue-600 hover:text-blue-800">
            ← Kembali ke Candidate Management
        </a>

        <h2 class="text-xl font-bold text-slate-800 mt-3">
            Tambah Kandidat
        </h2>

        <p class="text-sm text-slate-500 mt-1">
            Masukkan informasi identitas dan pendidikan kandidat.
        </p>
    </div>

    @include('candidates._form', ['candidate' => null])

</div>

@endsection
