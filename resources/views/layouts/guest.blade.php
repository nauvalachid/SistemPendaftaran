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
    <body class="font-sans text-gray-900 antialiased">
        
        {{-- BAGIAN INI KITA UBAH --}}
        <div class="min-h-screen bg-gray-100">
            
            {{-- 1. Panggil Navbar di sini --}}
            {{-- Kita bungkus dengan container agar padding-nya konsisten --}}
            <div class="max-w-7xl mx-auto px-6 lg:px-8">
                @include('partials.navbar')
            </div>

            {{-- 2. Konten utama ($slot) akan ditampilkan di bawah navbar --}}
            <main>
                {{ $slot }}
            </main>
        </div>
        
    </body>
</html>