@extends('layouts.app')

@section('title', 'Buat Recruitment Batch')
@section('page-title', 'Buat Recruitment Batch')

@section('content')

<div class="max-w-8xl">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-slate-800">Buat Recruitment Batch</h1>
        <p class="text-sm text-slate-500 mt-1">
            Masukkan informasi awal proses rekrutmen.
        </p>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-6">
        <form action="{{ route('recruitment-batches.store') }}" method="POST">
            @csrf

            @include('recruitment-batches._form', ['batch' => null])
        </form>
    </div>
</div>

@endsection