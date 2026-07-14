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
        <header class="w-full border-b border-green-100">
            <div class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between">
                <a href="{{ route('home') }}" class="flex items-center gap-3 hover:opacity-80 transition-opacity">
                    <div class="w-9 h-9 rounded-full bg-green-600 flex items-center justify-center text-white font-semibold">S</div>
                    <div>
                        <div class="font-semibold text-gray-900">SiPeMo</div>
                        <div class="text-xs text-gray-500 -mt-0.5">Sistem Penyusunan Modul</div>
                    </div>
                </a>
                <div class="flex items-center gap-3">
                    @if (Route::has('login'))
                    @auth
                    <a href="{{ route(auth()->user()->getDashboardRoute()) }}" class="px-4 py-2 rounded-full bg-green-600 text-white hover:bg-green-700 transition">Dashboard</a>
                    @else
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" @click.away="open = false" class="px-4 py-2 rounded-full bg-green-50 text-green-700 ring-1 ring-green-200 hover:bg-green-100 transition flex items-center gap-1">
                            Daftar
                            <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div x-show="open" x-transition class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-xl ring-1 ring-black ring-opacity-5 z-50 py-1 border border-gray-100">
                            <a href="{{ route('penyusun.apply.create') }}" class="block px-4 py-2.5 text-sm text-gray-700 hover:bg-green-50 hover:text-green-800 transition">
                                Penyusun Modul
                            </a>
                            <a href="{{ route('reviewer.apply.create') }}" class="block px-4 py-2.5 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-800 transition">
                                Reviewer Modul
                            </a>
                        </div>
                    </div>
                    <a href="{{ route('login') }}" class="px-4 py-2 rounded-full bg-green-600 text-white hover:bg-green-700 transition">Masuk</a>
                    @endauth
                    @endif
                </div>
            </div>
        </header>

        <main class="flex-1 py-8">
            <div class="max-w-7xl mx-auto px-6">
                {{ $slot }}
            </div>
        </main>

        <footer class="px-6 py-6 text-center text-sm text-gray-500">
            © {{ date('Y') }} SiPeMo. Semua hak dilindungi.
        </footer>
    </div>
</body>
</html>