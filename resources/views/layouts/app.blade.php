<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'Dashboard') - HireWise</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
@include('partials.delete-modal')
<body class="min-h-screen bg-slate-100 text-slate-800">

    <div class="min-h-screen md:flex">

        @include('partials.sidebar')

        <div class="min-w-0 flex-1">
            @include('partials.header')

            <main class="p-4 md:p-8">
                @yield('content')
            </main>
        </div>

    </div>

</body>
</html>
