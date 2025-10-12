<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width-device-width, initial-scale=1">

        <title>SD Muhammadiyah 2 Ambarketawang</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="antialiased font-sans bg-white">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            {{-- Navigation Bar --}}
            <header class="flex justify-between items-center py-6">
                
                {{-- Logo (Diimpor dari guest.blade.php) --}}
                <a href="/">
                    <img src="{{ asset('storage/logosd.png') }}" alt="Logo SD Muhammadiyah 2 Ambarketawang" class="h-14 w-auto">
                </a>

                {{-- Menu Links --}}
                <nav class="hidden md:flex items-center space-x-8 text-gray-600 font-medium">
                    <a href="#" class="hover:text-indigo-700">Beranda</a>
                    <a href="#" class="hover:text-indigo-700">Tentang Kami</a>
                    <a href="#" class="hover:text-indigo-700">Ekstrakurikuler</a>
                    <a href="#" class="hover:text-indigo-700">Tenaga Pengajar</a>
                    <a href="#" class="flex items-center hover:text-indigo-700">
                        Pendaftaran
                        <svg class="w-4 h-4 ml-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                        </svg>
                    </a>
                </nav>

                {{-- Auth Buttons --}}
                <div class="flex items-center space-x-2">
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}" class="px-5 py-2.5 text-sm font-semibold text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Dashboard
                            </a>
                        @else
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="px-5 py-2.5 text-sm font-semibold text-gray-700 rounded-lg hover:bg-gray-100">
                                    Sign Up
                                </a>
                            @endif
                            <a href="{{ route('login') }}" class="px-5 py-2.5 text-sm font-semibold text-white bg-indigo-700 rounded-lg hover:bg-indigo-800">
                                Sign In
                            </a>
                        @endauth
                    @endif
                </div>
            </header>

            {{-- Hero Section --}}
            <main class="grid lg:grid-cols-2 gap-12 items-center py-12 lg:py-24">
                {{-- Left Column: Text Content --}}
                <div class="text-center lg:text-left">
                    <h1 class="text-4xl md:text-6xl font-extrabold text-gray-900 leading-tight">
                        SD Muhammadiyah 2<br>Ambarketawang
                    </h1>
                    <p class="mt-6 text-lg text-gray-600">
                        SD Muhammadiyah 2 Ambarketawang menghadirkan pendidikan dasar yang unggul dan Islami, membentuk generasi cerdas, berakhlak, dan siap menghadapi masa depan.
                    </p>
                </div>

                {{-- Right Column: Image --}}
                <div>
                    {{-- Ganti 'src' dengan path gambar Anda, contoh: asset('images/school.png') --}}
                    <img src="https://i.imgur.com/G9A4nlv.png" alt="Gedung Sekolah SD Muhammadiyah 2 Ambarketawang" class="rounded-xl shadow-2xl w-full h-auto object-cover">
                </div>
            </main>
        </div>
    </body>
</html>