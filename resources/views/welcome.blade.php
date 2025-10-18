<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
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
               <nav class="hidden md:flex items-center space-x-8 text-primaryblue font-semilight">
                    <a href="#beranda" class="hover:text-hover hover:font-bold transition-all duration-200">Beranda</a>
                    <a href="#tentang-kami" class="hover:text-hover hover:font-bold transition-all duration-200">Tentang Kami</a>
                    <a href="#" class="hover:text-hover hover:font-bold transition-all duration-200">Ekstrakurikuler</a>
                    <a href="#" class="hover:text-hover hover:font-bold transition-all duration-200">Tenaga Pengajar</a>
                    <a href="#" class="flex items-center hover:text-hover hover:font-bold transition-all duration-200">
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
                            {{-- Tombol Dashboard (disamakan dengan Sign In) --}}
                            <a href="{{ url('/dashboard') }}" class="px-5 py-2.5 text-sm font-semibold text-white bg-primaryblue rounded-full hover:bg-blue-950 transition-colors">
                                Dashboard
                            </a>
                        @else
                            @if (Route::has('register'))
                                {{-- Tombol Sign Up --}}
                                <a href="{{ route('register') }}" class=" px-5 py-2 text-sm font-semibold text-white bg-primaryblue rounded-full hover:bg-hover transition-colors">
                                    Sign Up
                                </a>
                            @endif
                            {{-- Tombol Sign In --}}
                            <a href="{{ route('login') }}" class="px-5 py-2 text-sm font-semibold text-white bg-primaryblue rounded-full hover:bg-hover transition-colors">
                                Sign In
                            </a>
                        @endauth
                    @endif
                </div>
            </header>

            {{-- Beranda --}}
            <main id="beranda" class="grid lg:grid-cols-2 gap-12 items-center py-12 lg:py-24">
                {{-- Left Column: Text Content --}}
                <div class="text-center lg:text-left">
                    <h1 class="text-4xl md:text-5xl font-bold text-black leading-tight">
                        SD Muhammadiyah 2<br>Ambarketawang
                    </h1>
                    <p class="mt-10 text-lg text-black ">
                        SD Muhammadiyah 2 Ambarketawang menghadirkan pendidikan dasar yang unggul dan Islami, membentuk generasi cerdas, berakhlak, dan siap menghadapi masa depan.
                    </p>
                </div>

                {{-- Right Column: Image --}}
                <div>
                    <img src="{{ asset('storage/halamansekolah.png') }}" alt="Gedung Sekolah SD Muhammadiyah 2 Ambarketawang" class=" w-full h-auto object-cover">
                </div>
            </main>

             {{-- Tentang Kami --}}
            <section id="tentang-kami" class="py-20 lg:py-24">
                <div class="max-w-7xl mx-auto px-6 lg:px-8">
                    {{-- Judul Section --}}
                    <div class="text-center">
                        <h2 class="text-4xl font-semibold text-black">
                            Tentang sekolah
                        </h2>
                        <p class="mt-6 max-w-8xl mx-auto text-lg text-deskripsi">
                            SD Muhammadiyah Ambarketawang 2 didirikan pada tahun 1972 dengan komitmen yang kuat untuk memberikan pendidikan yang bermutu dan berbasis nilai-nilai keislaman serta keunggulan akademik kepada siswa. Dengan pendekatan holistik dan inovatif, kami menginspirasi generasi masa depan untuk mencapai potensi terbaik mereka dalam lingkungan belajar yang inklusif dan mendukung.
                        </p>
                    </div>

                    {{-- Grid untuk Visi & Misi --}}
                    <div class="mt-16 grid md:grid-cols-2 gap-8 lg:gap-12">
                        
                        {{-- Card Visi --}}
                        <div class="bg-card p-8 rounded-2xl shadow-lg max-w-sm mx-auto">
                            <h3 class="text-xl font-bold text-center text-black">Visi</h3>
                            <p class="mt-4 text-left text-black">
                                Mewujudkan generasi yang unggul, beriman, berakhlak mulia, dan berdaya saing global.
                            </p>
                        </div>

                        {{-- Card Misi --}}
                        <div class="bg-card p-8 rounded-2xl shadow-lg max-w-sm mx-auto">
                            <h3 class="text-xl font-bold text-center text-gray-800">Misi</h3>
                            <p class="mt-4 text-left text-black">
                                Memberikan pendidikan berkualitas yang berorientasi pada karakter, keunggulan akademik, dan kreativitas siswa.
                            </p>
                        </div>

                    </div>
                </div>
            </section>
        </div>
    </body>
</html>