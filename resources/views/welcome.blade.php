<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
    <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    {{-- BARIS PENTING INI --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="antialiased font-sans bg-white">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            
            @include('partials.navbar')

            {{-- Beranda --}}
            <main id="beranda" class="grid lg:grid-cols-2 gap-12 items-center py-12 lg:py-24">
                
                @if ($kontenBeranda)
                    @php
                        // Ambil media utama (urutan 0) dari koleksi media konten
                        $media_utama = $kontenBeranda->media->where('urutan', 0)->first();
                        $image_url = $media_utama ? asset('storage/' . $media_utama->file_path) : asset('storage/default.png');
                        $image_alt = $media_utama ? $kontenBeranda->judul : 'Default Image';
                    @endphp

                    {{-- Left Column: Text Content --}}
                    <div class="text-center lg:text-left">
                        <h1 class="text-4xl md:text-5xl font-bold text-black leading-tight">
                            {{-- Tampilkan Judul dari Admin --}}
                            {!! nl2br(e($kontenBeranda->judul)) !!} 
                        </h1>
                        <p class="mt-10 text-lg text-black ">
                            {{-- Tampilkan Isi/Deskripsi dari Admin --}}
                            {!! nl2br(e($kontenBeranda->isi)) !!}
                        </p>
                    </div>

                    {{-- Right Column: Image --}}
                    <div>
                        {{-- Tampilkan Media Utama dari Admin --}}
                        <img src="{{ $image_url }}" alt="{{ $image_alt }}" class="w-full h-auto object-cover">
                    </div>
                @else
                    {{-- Tampilkan konten fallback jika konten Beranda tidak ditemukan --}}
                    <div class="text-center lg:text-left">
                        <h1 class="text-4xl md:text-5xl font-bold text-black leading-tight">
                            SD Muhammadiyah 2<br>Ambarketawang
                        </h1>
                        <p class="mt-10 text-lg text-black ">
                            Konten belum diatur di admin.
                        </p>
                    </div>
                    <div>
                        <img src="{{ asset('storage/halamansekolah.png') }}" alt="Gedung Sekolah SD Muhammadiyah 2 Ambarketawang" class="w-full h-auto object-cover">
                    </div>
                @endif
            </main>

            {{-- TENTANG SEKOLAH (Sejarah, Visi, Misi) --}}
            <section id="tentang-sekolah" class="py-20 lg:py-24 bg-gray-100">
                <h2 class="text-3xl md:text-4xl font-extrabold text-center text-black mb-12">
                    Tentang Sekolah
                </h2>
                
                @if ($kontenTentangSekolah && $kontenTentangSekolah->count() > 0)
                    <div class="max-w-4xl mx-auto space-y-12">
                        @foreach ($kontenTentangSekolah as $itemKonten)
                        <div class="bg-white p-6 md:p-8 rounded-xl shadow-lg">
                            <h3 class="text-2xl font-bold text-blue-600 mb-3">
                                {{-- Judul Konten (Sejarah, Visi, Misi) --}}
                                {!! nl2br(e($itemKonten->judul)) !!}
                            </h3>
                            <p class="text-gray-700 leading-relaxed">
                                {{-- Isi Konten --}}
                                {!! nl2br(e($itemKonten->isi)) !!}
                            </p>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center text-gray-500">
                        <p>Konten Sejarah, Visi, dan Misi belum tersedia.</p>
                    </div>
                @endif
            </section>
            
        </div>
    </body>
</html>