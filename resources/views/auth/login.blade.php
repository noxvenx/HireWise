<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - HireWise</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-100 flex items-center justify-center p-4">

    <div class="w-full max-w-md bg-white rounded-2xl shadow-xl p-8">

        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-14 h-14 bg-blue-600 text-white rounded-xl text-2xl font-bold mb-4">
                H
            </div>
            <h1 class="text-3xl font-bold text-slate-800">HireWise</h1>
            <p class="text-slate-500 mt-2">
                Recruitment Management System
            </p>
        </div>

        <h2 class="text-xl font-semibold text-slate-800 mb-2">
            Selamat Datang
        </h2>
        <p class="text-sm text-slate-500 mb-6">
            Silakan masuk ke akun Anda.
        </p>

        @if ($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 rounded-lg p-3 mb-5 text-sm">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('login.process') }}">
            @csrf

            <div class="mb-5">
                <label class="block text-sm font-medium text-slate-700 mb-2">
                    Email
                </label>
                <input
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    required
                    autofocus
                    class="w-full border border-slate-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="nama@perusahaan.com"
                >
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-slate-700 mb-2">
                    Password
                </label>
                <input
                    type="password"
                    name="password"
                    required
                    class="w-full border border-slate-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="Masukkan password"
                >
            </div>

            <button
                type="submit"
                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 rounded-lg transition"
            >
                Masuk
            </button>
        </form>

        <p class="text-center text-xs text-slate-400 mt-8">
            © {{ date('Y') }} HireWise. All rights reserved.
        </p>

    </div>

</body>
</html>