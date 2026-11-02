
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'HireWise') - HireWise</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-slate-50 text-slate-900 antialiased">

    <div class="min-h-screen">

        {{-- Sidebar --}}
        @include('components.sidebar')

        {{-- Main Content --}}
        <div class="lg:pl-72">

            {{-- Topbar --}}
            @include('components.topbar')

            {{-- Page Content --}}
            <main class="p-6 lg:p-8">
                @yield('content')
            </main>

        </div>

    </div>

</body>
</html>
