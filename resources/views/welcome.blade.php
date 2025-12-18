<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>SD Muhammadiyah 2 Ambarketawang</title>

        {{-- Wajib: Tambahkan Swiper CSS (CDN) untuk slider Tenaga Pengajar --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

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

            {{-- 5. TENAGA PENGAJAR --}}
            <section id="tenaga-pengajar" class="py-20 bg-white">
                <div class="max-w-7xl mx-auto px-6 lg:px-8">
                    <div class="text-center mb-16">
                        <h2 class="text-3xl md:text-4xl font-extrabold text-black">Tenaga Pengajar</h2>
                    </div>

                    @if(isset($kontenTenagaPengajar) && $kontenTenagaPengajar->count() > 0)
                        <div class="relative px-12"> {{-- Padding ekstra untuk tombol navigasi luar --}}
                            <div class="swiper swiperTenagaPengajar">
                                <div class="swiper-wrapper">
                                    @foreach($kontenTenagaPengajar as $guru)
                                        @php
                                            $media = $guru->media->first();
                                            $fotoUrl = $media ? asset('storage/' . $media->file_path) : asset('storage/default-avatar.png');
                                        @endphp
                                        <div class="swiper-slide py-4">
                                            <div class="bg-[#F2E8D9] rounded-2xl shadow-md p-6 flex items-center gap-6 h-48 border border-gray-100 transform transition hover:scale-105">
                                                {{-- Foto Guru (Box Putih) --}}
                                                <div class="w-28 h-36 bg-gray-200 rounded-lg overflow-hidden flex-shrink-0 shadow-inner">
                                                    <img src="{{ $fotoUrl }}" alt="{{ $guru->judul }}" class="w-full h-full object-cover">
                                                </div>
                                                
                                                {{-- Info Guru --}}
                                                <div class="flex flex-col">
                                                    <h3 class="text-xl font-bold text-black leading-tight">{{ $guru->judul }}</h3>
                                                    <p class="text-gray-700 mt-1 font-medium">{{ $guru->isi }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                
                                {{-- Navigasi Bullets (Titik-titik di bawah) --}}
                                <div class="swiper-pagination !-bottom-10"></div>
                            </div>

                            {{-- Tombol Navigasi (Panah di Samping) --}}
                            <div class="swiper-button-prev !text-[#002060] !left-0 after:!text-3xl font-bold"></div>
                            <div class="swiper-button-next !text-[#002060] !right-0 after:!text-3xl font-bold"></div>
                        </div>
                    @else
                        <div class="text-center py-12">
                            <p class="text-gray-500">Data tenaga pengajar belum tersedia.</p>
                        </div>
                    @endif
                </div>
            </section>

           {{-- 4. PPDB --}}
        @if($kontenPPDB)
        <section id="ppdb" class="py-20 bg-white relative overflow-hidden">
            <div class="max-w-6xl mx-auto px-6 lg:px-8 relative z-10">
                
                {{-- HEADER: Judul dan Subjudul (Tengah) --}}
                <div class="text-center mb-12 lg:mb-16">
                    <h2 class="text-2xl md:text-3xl font-extrabold text-black mb-2">
                        {!! nl2br(e($kontenPPDB->judul)) !!}
                    </h2>
                    @if(isset($kontenPPDB->sub_judul) && $kontenPPDB->sub_judul)
                        <h3 class="text-xl md:text-2xl font-bold text-black">
                            {{ $kontenPPDB->sub_judul }}
                        </h3>
                    @endif
                </div>

                {{-- KONTEN UTAMA: Grid 2 Kolom --}}
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 items-center">
                    
                    {{-- KOLOM KIRI: Teks Ajakan & Tombol --}}
                    <div class="flex flex-col items-start text-left">
                        <h4 class="text-2xl md:text-3xl font-bold text-[#002060] mb-6 leading-tight">
                            Ayo bergabung dengan kami!
                        </h4>
                        
                        <a href="/pendaftaran" 
                        class="inline-flex items-center justify-center px-8 py-3 text-base font-bold text-white transition-all duration-200 bg-[#002060] rounded-xl hover:bg-blue-900 shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                            Daftar Sekarang
                        </a>
                    </div>

                    {{-- KOLOM KANAN: Kartu Syarat Pendaftaran --}}
                    <div class="bg-white p-8 rounded-xl shadow-[0_4px_30px_rgba(0,0,0,0.08)] border border-gray-100">
                        <h5 class="text-xl font-bold text-black mb-4">Syarat Pendaftaran :</h5>
                        
                        {{-- List Syarat (Dibuat Hardcode agar rapi sesuai gambar) --}}
                        <ol class="list-decimal list-outside ml-5 space-y-3 text-gray-800 font-medium">
                            <li>Mengisi formulir pendaftaran.</li>
                            <li>
                                Melengkapi berkas :
                                <ul class="list-disc list-outside ml-5 mt-1 space-y-1 text-gray-700">
                                    <li>Kartu Keluarga;</li>
                                    <li>Akte Kelahiran;</li>
                                    <li>Ijazah TK (Jika Ada);</li>
                                    <li>Pas Foto.</li>
                                </ul>
                            </li>
                            <li>Melakukan pembayaran administrasi.</li>
                        </ol>
                        
                        {{-- OPSI: Jika ingin tetap menampilkan teks dari database di bawah list hardcode, hapus komentar di bawah ini --}}
                        {{-- <div class="mt-4 prose prose-sm text-gray-600">
                            {!! nl2br(e($kontenPPDB->isi)) !!}
                        </div> --}}
                    </div>

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