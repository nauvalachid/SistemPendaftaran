<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>SD Muhammadiyah 2 Ambarketawang</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="antialiased font-sans bg-white">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            
            {{-- Panggil kode navbar dari file terpisah --}}
            @include('partials.navbar')

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
                {{-- ... sisa kode Anda ... --}}
            </section>
        </div>
    </body>
</html>