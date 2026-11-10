@extends('layouts.app')

@section('title', 'Edit Recruitment Batch')
@section('page-title', 'Edit Recruitment Batch')

@section('content')

<div class="max-w-8xl">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-slate-800">Edit Recruitment Batch</h1>
        <p class="text-sm text-slate-500 mt-1">
            Perbarui informasi Recruitment Batch.
        </p>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-6">

        <form action="{{ route('recruitment-batches.update', $recruitmentBatch) }}" method="POST">
            @csrf
            @method('PUT')

            @include('recruitment-batches._form', ['batch' => $recruitmentBatch])

        </form>
    </div>
</div>

@endsection