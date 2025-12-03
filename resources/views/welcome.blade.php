<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>SD Muhammadiyah 2 Ambarketawang</title>

        {{-- Load CSS & JS Laravel --}}
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="antialiased font-sans bg-white">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            
            @include('partials.navbar')

            {{-- 1. BERANDA --}}
            <main id="beranda" class="grid lg:grid-cols-2 gap-12 items-center py-12 lg:py-24">
                @if ($kontenBeranda)
                    @php
                        $media_utama = $kontenBeranda->media->where('urutan', 0)->first();
                        $image_url = $media_utama ? asset('storage/' . $media_utama->file_path) : asset('storage/default.png');
                    @endphp
                    <div class="text-center lg:text-left">
                        <h1 class="text-4xl md:text-5xl font-bold text-black leading-tight">
                            {!! nl2br(e($kontenBeranda->judul)) !!} 
                        </h1>
                        <p class="mt-10 text-lg text-black ">
                            {!! nl2br(e($kontenBeranda->isi)) !!}
                        </p>
                    </div>
                    <div>
                        <img src="{{ $image_url }}" class="w-full h-auto object-cover rounded-xl shadow-lg">
                    </div>
                @else
                    <div class="text-center lg:text-left">
                        <h1 class="text-4xl md:text-5xl font-bold text-black leading-tight">
                            SD Muhammadiyah 2<br>Ambarketawang
                        </h1>
                        <p class="mt-10 text-lg text-black ">Konten belum diatur.</p>
                    </div>
                    <div>
                        <img src="{{ asset('storage/halamansekolah.png') }}" class="w-full h-auto object-cover rounded-xl shadow-lg">
                    </div>
                @endif
            </main>

            {{-- 2. TENTANG SEKOLAH --}}
            <section id="tentang-sekolah" class="py-20 lg:py-24">
                @if ($kontenTentangSekolah && $kontenTentangSekolah->count() > 0)
                    @php
                        $deskripsiUtama = $kontenTentangSekolah->first(function ($item) {
                            return \Illuminate\Support\Str::contains(\Illuminate\Support\Str::lower($item->judul), ['sejarah', 'tentang sekolah']);
                        });
                        $visiData = $kontenTentangSekolah->first(function ($item) {
                             return \Illuminate\Support\Str::contains(\Illuminate\Support\Str::lower($item->judul), 'visi');
                        });
                        $misiData = $kontenTentangSekolah->first(function ($item) {
                             return \Illuminate\Support\Str::contains(\Illuminate\Support\Str::lower($item->judul), 'misi');
                        });
                    @endphp

                    <h2 class="text-3xl md:text-4xl font-extrabold text-center text-black mb-8">Tentang sekolah</h2>

                    <div class="max-w-5xl mx-auto px-4">
                        @if($deskripsiUtama)
                        <div class="text-center mb-16">
                            <p class="text-blue-900 leading-relaxed text-lg md:text-xl font-medium">
                                {!! nl2br(e($deskripsiUtama->isi)) !!}
                            </p>
                        </div>
                        @endif

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 lg:gap-12">
                            @if($visiData)
                            <div class="bg-[#EFE4D2] p-8 md:p-10 rounded-2xl shadow-sm text-center flex flex-col justify-center">
                                <h3 class="text-2xl font-bold text-black mb-6">{!! nl2br(e($visiData->judul)) !!}</h3>
                                <p class="text-gray-800 leading-relaxed text-lg">{!! nl2br(e($visiData->isi)) !!}</p>
                            </div>
                            @endif

                            @if($misiData)
                            <div class="bg-[#EFE4D2] p-8 md:p-10 rounded-2xl shadow-sm text-center flex flex-col justify-center">
                                <h3 class="text-2xl font-bold text-black mb-6">{!! nl2br(e($misiData->judul)) !!}</h3>
                                <p class="text-gray-800 leading-relaxed text-lg">{!! nl2br(e($misiData->isi)) !!}</p>
                            </div>
                            @endif
                        </div>
                    </div>
                @endif
            </section>

            {{-- ===================================================================== --}}
            {{-- 3. BAGIAN EKSTRAKURIKULER (TOMBOL MINIMALIS TANPA BAYANGAN)           --}}
            {{-- ===================================================================== --}}
            <section id="ekstrakurikuler" class="py-20 lg:py-24 bg-white">
                <div class="max-w-7xl mx-auto px-6 lg:px-8">
                    
                    <div class="text-center mb-16">
                        <h2 class="text-3xl md:text-4xl font-extrabold text-black">Ekstrakurikuler</h2>
                    </div>

                    @if(isset($kontenEkstrakurikuler) && $kontenEkstrakurikuler->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                            
                            @foreach($kontenEkstrakurikuler as $ekskul)
                                @php
                                    $semuaMedia = $ekskul->media;
                                    $jumlahFoto = $semuaMedia->count();
                                    $sliderId = 'slider-' . $ekskul->id; 
                                @endphp

                                <div class="group relative rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 border border-gray-100 bg-white h-full flex flex-col">
                                    
                                    {{-- Wrapper Foto --}}
                                    <div class="relative h-64 w-full bg-gray-100 overflow-hidden" id="{{ $sliderId }}" data-current="0" data-total="{{ $jumlahFoto }}">
                                        
                                        @if($jumlahFoto > 0)
                                            @foreach($semuaMedia as $index => $media)
                                                {{-- Gambar --}}
                                                <div class="slide-item absolute inset-0 w-full h-full transition-opacity duration-500 ease-in-out {{ $index === 0 ? 'opacity-100 z-10' : 'opacity-0 z-0' }}">
                                                    <img src="{{ asset('storage/' . $media->file_path) }}" 
                                                         class="w-full h-full object-cover"
                                                         alt="{{ $ekskul->judul }}">
                                                </div>
                                            @endforeach

                                            {{-- TOMBOL NAVIGASI (MINIMALIS) --}}
                                            @if($jumlahFoto > 1)
                                                {{-- Tombol Kiri (<) --}}
                                                {{-- Hapus bg-white, shadow, p-2. Ganti warna text jadi abu gelap --}}
                                                <button onclick="moveSlide('{{ $sliderId }}', -1)" 
                                                        class="absolute left-2 top-1/2 -translate-y-1/2 z-20 text-gray-600 hover:text-black transition-colors focus:outline-none transform hover:scale-110">
                                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"></path>
                                                    </svg>
                                                </button>

                                                {{-- Tombol Kanan (>) --}}
                                                <button onclick="moveSlide('{{ $sliderId }}', 1)" 
                                                        class="absolute right-2 top-1/2 -translate-y-1/2 z-20 text-gray-600 hover:text-black transition-colors focus:outline-none transform hover:scale-110">
                                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"></path>
                                                    </svg>
                                                </button>
                                            @endif
                                        @else
                                            {{-- Placeholder --}}
                                            <div class="w-full h-full flex flex-col items-center justify-center text-gray-400">
                                                <svg class="w-12 h-12 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                                <span class="text-xs">Tidak ada foto</span>
                                            </div>
                                        @endif
                                    </div>

                                    {{-- Deskripsi --}}
                                    <div class="p-6 text-center flex-1 flex flex-col justify-center bg-[#F2E8D9]">
                                        <h3 class="text-xl font-bold text-black mb-3">{{ $ekskul->judul }}</h3>
                                        <p class="text-gray-800 text-sm leading-relaxed line-clamp-3">{{ $ekskul->isi }}</p>
                                    </div>
                                </div>
                            @endforeach

                        </div>
                    @else
                        <div class="text-center py-12">
                            <p class="text-gray-500">Belum ada data ekstrakurikuler.</p>
                        </div>
                    @endif
                </div>
            </section>

            {{-- 4. PPDB --}}
            @if($kontenPPDB)
            <section id="ppdb" class="py-20 lg:py-24 bg-blue-50 relative overflow-hidden">
                <div class="max-w-4xl mx-auto px-6 lg:px-8 relative z-10 text-center">
                    <span class="inline-block py-1 px-3 rounded-full bg-blue-100 text-blue-700 text-sm font-bold tracking-wide uppercase mb-6 border border-blue-200">Penerimaan Peserta Didik Baru</span>
                    <h2 class="text-3xl md:text-5xl font-extrabold text-black mb-4 leading-tight">{!! nl2br(e($kontenPPDB->judul)) !!}</h2>
                    @if(isset($kontenPPDB->sub_judul) && $kontenPPDB->sub_judul)
                        <h3 class="text-xl md:text-2xl font-bold text-blue-700 mb-8">{{ $kontenPPDB->sub_judul }}</h3>
                    @endif
                    <div class="prose prose-lg mx-auto text-gray-700 mb-10 leading-relaxed"><p>{!! nl2br(e($kontenPPDB->isi)) !!}</p></div>
                    <div class="flex flex-col sm:flex-row justify-center gap-4">
                        <a href="/pendaftaran" class="inline-flex items-center justify-center px-8 py-4 text-base font-bold text-white transition-all duration-200 bg-[#003366] border border-transparent rounded-xl hover:bg-blue-900 shadow-lg hover:shadow-xl transform hover:-translate-y-1">Daftar Sekarang</a>
                        <a href="#kontak" class="inline-flex items-center justify-center px-8 py-4 text-base font-bold text-[#003366] transition-all duration-200 bg-white border-2 border-[#003366] rounded-xl hover:bg-blue-50 shadow-sm hover:shadow-md">Hubungi Kami</a>
                    </div>
                </div>
            </section>
            @endif
            
        </div>

        {{-- SCRIPT MANUAL UNTUK SLIDER --}}
        <script>
            function moveSlide(sliderId, direction) {
                const container = document.getElementById(sliderId);
                let current = parseInt(container.getAttribute('data-current'));
                const total = parseInt(container.getAttribute('data-total'));
                const slides = container.querySelectorAll('.slide-item');

                let next = current + direction;
                if (next >= total) next = 0;
                if (next < 0) next = total - 1;

                slides[current].classList.remove('opacity-100', 'z-10');
                slides[current].classList.add('opacity-0', 'z-0');

                slides[next].classList.remove('opacity-0', 'z-0');
                slides[next].classList.add('opacity-100', 'z-10');

                container.setAttribute('data-current', next);
            }
        </script>
    </body>
</html>