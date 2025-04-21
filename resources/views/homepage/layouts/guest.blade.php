<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'SIMOBI') }} - @yield('title')</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js']) {{-- If you're using Vite --}}
</head>
<body class="bg-gray-100 text-gray-900 min-h-screen flex flex-col">

    <header class="bg-white shadow p-4">
        <div class="container mx-auto">
            <h1 class="text-xl font-bold">
                <a href="{{ url('/') }}">{{ config('app.name', 'SIMOBI') }}</a>
            </h1>
        </div>
    </header>

    <main class="flex-1 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="w-full max-w-md space-y-8 bg-white p-8 rounded shadow">
            @yield('content')
        </div>
    </main>

    <footer class="bg-white border-t mt-auto p-4 text-center text-sm text-gray-500">
        &copy; {{ date('Y') }} SIMOBI. All rights reserved.
    </footer>

</body>
</html>
