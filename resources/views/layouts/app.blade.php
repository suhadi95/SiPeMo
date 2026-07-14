<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-white text-gray-900">
    <div class="min-h-screen flex flex-col">
        @include('layouts.navigation')

        <!-- Page Heading -->
        <div class="max-w-7xl mx-auto px-6 py-4">
            @yield('header')
        </div>

        <!-- Page Content -->
        <main class="flex-1">
            @yield('content')
        </main>

        <footer class="px-6 py-6 text-center text-sm text-gray-500">
            © <a href="{{ url('/') }}" class="hover:underline">SiPeMo</a> {{ date('Y') }} – Developed by <a href="https://suhadip.com" target="_blank" rel="noopener noreferrer" class="hover:underline">Suhadi Parman</a> | All rights reserved.
        </footer>
    </div>

    @yield('scripts')
</body>

</html>