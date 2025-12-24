@extends('admin.layouts.app')

@section('title', 'Kelola Konten')

@section('content')

    {{-- LOAD CSS KUSTOM --}}
    @vite(['resources/css/custom-dropdown.css', 'resources/css/animations.css'])

   {{-- PERUBAHAN 1: Hapus class 'animate-fade-in-up' dari sini agar Sidebar diam --}}
   <div class="flex min-h-screen bg-gray-50">
        
        {{-- ========================================================== --}}
        {{-- SIDEBAR WRAPPER (FIXED)                                    --}}
        {{-- ========================================================== --}}
        {{-- 
            Perbaikan:
            1. 'w-64' DIHAPUS -> Agar lebar mengikuti bawaan sidebar asli (Pendaftaran).
            2. 'shrink-0' -> Agar sidebar tidak gepeng/mengecil.
            3. 'h-screen sticky top-0' -> Agar tinggi full dan tidak menggantung.
        --}}
        <div class="h-screen sticky top-0 ">
            <x-sidebar /> 
        </div>

        {{-- KONTEN UTAMA --}}
        <main class="flex-1 w-full overflow-y-auto p-6 lg:p-6 animate-fade-in-up">

            {{-- HEADER HALAMAN --}}
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-black">Kelola Konten</h1>
                <p class="mt-2 text-gray-600">Kelola seluruh konten halaman website sekolah di sini.</p>
                <hr class="my-5 border-gray-300">
            </div>

            {{-- ALERT SUCCESS & ERROR --}}
            @if (session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded shadow-sm" role="alert">
                <p class="font-bold">Berhasil!</p> <p>{{ session('success') }}</p>
            </div>
            @endif
            @if ($errors->any())
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded shadow-sm" role="alert">
                    <p class="font-bold">Gagal!</p>
                    <ul class="list-disc list-inside text-sm mt-1">@foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach</ul>
                </div>
            @endif

            {{-- LOOP KATEGORI --}}
            @foreach($kategori as $kat)
            
            <div class="rounded-2xl bg-white p-8 shadow-sm border border-gray-100 mb-8 hover:shadow-md transition-shadow duration-300">

                {{-- HEADER KATEGORI --}}
                @php
                    $isCustomLayout = ($kat->nama === 'Halaman Ekstrakurikuler' || $kat->nama === 'Ekstrakurikuler' || 
                                       $kat->nama === 'Halaman Tenaga Pengajar' || $kat->nama === 'Tenaga Pengajar' ||
                                       $kat->nama === 'Halaman Informasi PPDB'  || $kat->nama === 'Informasi PPDB');
                @endphp

                <div class="flex items-center mb-6 pb-4 border-b border-gray-100 {{ $isCustomLayout ? 'justify-start gap-4' : 'justify-between' }}">
                    <h2 class="text-xl font-extrabold text-black">
                        @if($kat->nama === 'Beranda') Halaman Beranda
                        @elseif($kat->nama === 'Tentang Sekolah') Halaman Tentang Sekolah
                        @elseif($kat->nama === 'Ekstrakurikuler') Halaman Ekstrakurikuler
                        @elseif($kat->nama === 'Tenaga Pengajar') Halaman Tenaga Pengajar
                        @elseif($kat->nama === 'Informasi PPDB') Halaman Informasi PPDB
                        @else {{ $kat->nama }} @endif
                    </h2>

                    {{-- LOGIKA TOMBOL TAMBAH --}}
                    @php
                        $tampilTombol = true;
                        $opsiTersedia = []; 

                        if ( ($kat->nama === 'Halaman Beranda' || $kat->nama === 'Beranda' || 
                              $kat->nama === 'Halaman Informasi PPDB' || $kat->nama === 'Informasi PPDB') 
                              && $kat->konten->count() > 0 ) {
                            $tampilTombol = false;
                        }
                        
                        if ($kat->nama === 'Halaman Tentang Sekolah' || $kat->nama === 'Tentang Sekolah') {
                            $sudahAda = $kat->konten->pluck('judul')->map(function($item) { return strtolower(trim($item)); })->toArray();
                            $semuaOpsi = ['Sejarah', 'Visi', 'Misi'];
                            foreach ($semuaOpsi as $opsi) { if (!in_array(strtolower($opsi), $sudahAda)) { $opsiTersedia[] = $opsi; } }
                            if (empty($opsiTersedia)) { $tampilTombol = false; }
                        }
                    @endphp

                    @if ($tampilTombol)
                    <button class="text-sm font-semibold bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition shadow-sm flex items-center gap-2 active:scale-95 transform duration-150"
                        onclick='openTambahModal({{ $kat->id }}, "{{ $kat->nama }}", @json($opsiTersedia))'>
                        <span>+</span> Tambah
                    </button>
                    @endif
                </div>

                {{-- ISI KONTEN2 --}}
                
                {{-- 1. TENTANG SEKOLAH --}}
                @if ($kat->nama === 'Halaman Tentang Sekolah' || $kat->nama === 'Tentang Sekolah')
                    <div class="space-y-8"> 
                        @foreach($kat->konten as $konten)
                        <div class="flex flex-col md:flex-row justify-between items-start gap-4 group">
                            <div class="flex-1 pl-4 md:pl-6">
                                <h3 class="text-lg font-bold text-black mb-2">{{ $konten->judul }}</h3> 
                                <p class="text-gray-700 text-sm leading-relaxed text-justify">{{ $konten->isi }}</p>
                            </div>
                            <div class="flex gap-2 flex-shrink-0 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                <button class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-1.5 rounded-lg text-xs font-bold shadow-sm transition transform hover:scale-105"
                                    onclick="openEditModal({{ $konten->id }})"><i class="fas fa-pen mr-1"></i> Edit</button>
                                <form action="{{ route('admin.konten.destroy', $konten->id) }}" method="POST">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-1.5 rounded-lg text-xs font-bold shadow-sm transition transform hover:scale-105"
                                        onclick="return confirm('Yakin hapus konten ini?')"><i class="fas fa-trash-alt mr-1"></i> Hapus</button>
                                </form>
                            </div>
                        </div>
                        @if(!$loop->last) <hr class="border-gray-100"> @endif
                        @endforeach
                    </div>

                {{-- 2. EKSTRAKURIKULER --}}
                @elseif ($kat->nama === 'Halaman Ekstrakurikuler' || $kat->nama === 'Ekstrakurikuler')
                    <div class="space-y-2">
                        @foreach($kat->konten as $konten)
                        <div class="flex flex-col md:flex-row justify-between items-start gap-6 py-8 border-b border-gray-100 last:border-0 hover:bg-gray-50/50 transition duration-300 rounded-lg px-2 -mx-2">
                            <div class="flex-1">
                                <h3 class="text-xl font-bold text-black mb-2">{{ $konten->judul }}</h3>
                                <p class="text-gray-600 text-sm leading-relaxed mb-5 max-w-2xl">{{ $konten->isi }}</p>
                                <div class="flex gap-3">
                                    <button onclick="openEditModal({{ $konten->id }})" class="flex items-center gap-2 bg-yellow-500 hover:bg-yellow-600 text-white px-5 py-2 rounded-lg font-bold shadow-sm transition transform hover:-translate-y-0.5"><i class="fas fa-pen text-sm"></i> Edit</button>
                                    <button type="button" onclick="openHapusModal('{{ route('admin.konten.destroy', $konten->id) }}', 'Hapus Ekstrakurikuler', 'Yakin untuk menghapus ekstrakurikuler?')" class="flex items-center gap-2 bg-red-600 hover:bg-red-700 text-white px-5 py-2 rounded-lg font-bold shadow-sm transition transform hover:-translate-y-0.5"><i class="fas fa-trash-alt text-sm"></i> Hapus</button>
                                </div>
                            </div>
                            <div class="flex-shrink-0">
                                <div class="flex flex-wrap justify-end gap-3 max-w-md">
                                    @foreach($konten->media as $media)
                                    <div class="relative w-24 h-40 group">
                                        <img src="{{ asset('storage/' . $media->file_path) }}" class="w-full h-full object-cover rounded-lg bg-gray-100 border border-gray-100">
                                        <form action="{{ route('admin.konten_media.destroy', $media->id) }}" method="POST" class="absolute -top-2 -right-2 hidden group-hover:block z-10">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="bg-red-600 text-white rounded-full w-5 h-5 flex items-center justify-center text-xs shadow-md transform hover:scale-110 transition" onclick="return confirm('Hapus foto ini?')">&times;</button>
                                        </form>
                                    </div>
                                    @endforeach
                                    @if($konten->media->count() == 0) <div class="w-24 h-40 rounded-lg bg-gray-50 border border-gray-100"></div> @endif
                                    <button onclick="openMediaModal({{ $konten->id }})" class="w-24 h-40 rounded-lg bg-gray-50 hover:bg-gray-100 border border-gray-100 flex items-center justify-center transition group transform hover:scale-105"><i class="fas fa-plus text-xl text-gray-400 group-hover:text-gray-600"></i></button>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                
                {{-- 3. TENAGA PENGAJAR & PPDB --}}
                @elseif ($kat->nama === 'Halaman Tenaga Pengajar' || $kat->nama === 'Tenaga Pengajar' || $kat->nama === 'Halaman Informasi PPDB' || $kat->nama === 'Informasi PPDB')
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-10">
                        @foreach($kat->konten as $konten)
                        @php
                            $foto = $konten->media->where('urutan', 0)->first();
                            $fotoUrl = $foto ? asset('storage/' . $foto->file_path) : null;
                            $isPPDB = str_contains(strtolower($kat->nama), 'ppdb');
                        @endphp
                        
                        <div class="flex items-start gap-5">
                            {{-- Jika PPDB, Sembunyikan Foto di Card --}}
                            @if(!$isPPDB)
                            <div class="flex-shrink-0 w-24 h-24">
                                @if($fotoUrl)
                                    <img src="{{ $fotoUrl }}" class="w-full h-full object-cover rounded-lg bg-gray-50 border border-gray-100 shadow-sm">
                                @else
                                    <div class="w-full h-full bg-gray-100 rounded-lg flex items-center justify-center text-gray-400 border border-gray-200">
                                        <i class="fas fa-plus text-2xl"></i>
                                    </div>
                                @endif
                            </div>
                            @endif

                            <div class="flex-1 min-w-0">
                                <h3 class="text-lg font-bold text-black truncate mb-1">{{ $konten->judul }}</h3>
                                @if(isset($konten->sub_judul) && $konten->sub_judul)
                                    <p class="text-sm text-blue-600 font-semibold mb-1">{{ $konten->sub_judul }}</p>
                                @endif
                                <p class="text-sm text-gray-500 mb-4 line-clamp-2">{{ $konten->isi }}</p>
                                
                                <div class="flex gap-2">
                                    <button onclick="openEditModal({{ $konten->id }})" class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-1.5 rounded-lg text-xs font-bold shadow-sm transition flex items-center gap-1"><i class="fas fa-pen"></i> Edit</button>
                                    
                                    @if(!$isPPDB)
                                    <button type="button" onclick="openHapusModal('{{ route('admin.konten.destroy', $konten->id) }}', 'Hapus Data', 'Yakin ingin menghapus data ini?')" class="bg-red-600 hover:bg-red-700 text-white px-4 py-1.5 rounded-lg text-xs font-bold shadow-sm transition flex items-center gap-1"><i class="fas fa-trash-alt"></i> Hapus</button>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                {{-- 4. DEFAULT (BERANDA & LAINNYA) --}}
                @else
                    <div class="space-y-6">
                        @foreach($kat->konten as $konten)
                        @php
                            $isBeranda = ($kat->nama === 'Halaman Beranda' || $kat->nama === 'Beranda');
                            $foto_utama = $konten->media->where('urutan', 0)->first();
                        @endphp
                        @if ($isBeranda)
                            <div class="py-4 border-b border-gray-100 last:border-0">
                                <div class="flex flex-col md:flex-row justify-between items-start gap-8">
                                    <div class="flex-1 pl-4 md:pl-6">
                                        <h3 class="text-2xl font-extrabold text-black mb-4">{{ $konten->judul }}</h3>
                                        <p class="text-gray-700 text-base leading-relaxed mb-6">{{ $konten->isi }}</p>
                                        <button class="bg-yellow-500 hover:bg-yellow-600 text-white px-6 py-2.5 rounded-lg font-bold shadow-sm transition transform hover:-translate-y-0.5 flex items-center gap-2" onclick="openEditModal({{ $konten->id }})"><i class="fas fa-pen text-sm"></i> Edit</button>
                                    </div>
                                    @if ($foto_utama)
                                    <div class="w-full md:w-1/3 flex-shrink-0">
                                        {{-- FIX: object-cover diganti menjadi object-contain agar foto tidak terpotong --}}
                                        <img src="{{ asset('storage/' . $foto_utama->file_path) }}" 
                                            class="w-full h-48 object-contain rounded-lg shadow-md border border-gray-100 hover:shadow-lg transition transform hover:scale-[1.02]">
                                    </div>
                                    @endif
                                </div>
                            </div>
                        @else
                            <div class="border border-gray-200 rounded-xl p-5 hover:border-blue-300 transition duration-300 bg-gray-50 hover:bg-white hover:shadow-md transform hover:-translate-y-1">
                                <div class="flex justify-between items-start gap-4">
                                    <div class="flex-1">
                                        <h3 class="text-lg font-bold text-black">{{ $konten->judul }}</h3>
                                        <p class="mt-2 text-gray-600 text-sm leading-relaxed">{{ $konten->isi }}</p>
                                    </div>
                                    <div class="flex gap-2 flex-shrink-0">
                                        <button class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1.5 rounded-lg text-xs font-bold shadow-sm transition" onclick="openEditModal({{ $konten->id }})">Edit</button>
                                        <button type="button" class="bg-red-600 hover:bg-red-700 text-white px-3 py-1.5 rounded-lg text-xs font-bold shadow-sm transition" onclick="openHapusModal('{{ route('admin.konten.destroy', $konten->id) }}', 'Hapus Konten', 'Yakin untuk menghapus konten ini?')">Hapus</button>
                                    </div>
                                </div>
                            </div>
                        @endif 
                        @endforeach
                    </div>
                @endif
            </div>
            @endforeach
        </main>
    </div>

    {{-- MODAL EDIT --}}
    <div id="modalEdit" class="hidden fixed inset-0 z-50 flex items-center justify-center">
        <div id="modalEditBackdrop" class="absolute inset-0 bg-black/60 backdrop-blur-sm opacity-0 modal-backdrop-transition"></div>
        <div id="modalEditContent" class="bg-white w-full max-w-2xl rounded-2xl p-8 shadow-2xl relative transform scale-95 opacity-0 modal-content-transition z-10">
            <div class="text-center mb-8">
                <h2 id="modalEditTitle" class="text-2xl font-bold text-gray-900 tracking-tight">Edit Konten</h2>
            </div>
            <form id="formEdit" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf @method('PUT')
                <div>
                    <label id="editLabelJudul" class="block text-gray-900 text-sm font-bold mb-2">Judul</label>
                    <input type="text" id="editJudul" name="judul" class="w-full px-4 py-3 border border-gray-300 rounded-xl text-gray-900 focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-100 transition-all" required>
                </div>
                <div id="editGroupSubJudul" class="hidden">
                    <label id="editLabelSubJudul" class="block text-gray-900 text-sm font-bold mb-2">Sub Judul</label>
                    <input type="text" id="editSubJudul" name="sub_judul" class="w-full px-4 py-3 border border-gray-300 rounded-xl text-gray-900 focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-100 transition-all">
                </div>
                <div>
                    <label id="editLabelIsi" class="block text-gray-900 text-sm font-bold mb-2">Deskripsi</label>
                    <textarea id="editIsi" name="isi" rows="4" class="w-full px-4 py-3 border border-gray-300 rounded-xl text-gray-900 focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-100 transition-all resize-none" required></textarea>
                </div>
                <div id="editFotoGroup">
                    <label class="block text-gray-900 text-sm font-bold mb-2">Foto</label>
                    <div class="flex items-center border border-gray-300 rounded-xl p-2 bg-gray-50/50 file-input-wrapper transition-colors">
                        <input type="file" name="file_utama" accept="image/*" class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-bold file:bg-[#003366] file:text-white hover:file:bg-blue-900 cursor-pointer">
                        <a id="linkLihatFoto" href="#" target="_blank" class="hidden text-sm text-[#003366] font-bold hover:underline whitespace-nowrap mr-4">Lihat foto saat ini</a>
                    </div>
                </div>
                <div class="flex justify-end gap-3 mt-8 pt-4">
                    <button type="button" onclick="closeModalAnimation('modalEdit')" class="px-6 py-2.5 rounded-xl border border-gray-300 text-gray-700 font-bold bg-white hover:bg-gray-50 hover:border-gray-400 transition-all active:scale-95">Batal</button>
                    <button type="submit" class="px-6 py-2.5 rounded-xl bg-[#003366] text-white font-bold hover:bg-blue-900 shadow-lg shadow-blue-900/20 transition-all active:scale-95">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>

    {{-- MODAL TAMBAH --}}
    <div id="modalTambah" class="hidden fixed inset-0 z-50 flex items-center justify-center">
        <div id="modalTambahBackdrop" class="absolute inset-0 bg-black/60 backdrop-blur-sm opacity-0 modal-backdrop-transition"></div>
        <div id="modalTambahContent" class="bg-white w-full max-w-2xl rounded-2xl p-8 shadow-2xl relative transform scale-95 opacity-0 modal-content-transition z-10">
            <div class="text-center mb-8">
                <h2 id="modalTitle" class="text-2xl font-bold text-gray-900 tracking-tight">Tambah Konten Baru</h2>
            </div>
            <form action="{{ route('admin.konten.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                <input type="hidden" name="kategori_konten_id" id="tambahKategoriID">
                <div>
                    <label id="labelJudul" class="block text-gray-900 text-sm font-bold mb-2">Nama Ekstrakurikuler</label>
                    <input type="text" id="inputJudul" name="judul" class="w-full px-4 py-3 border border-gray-300 rounded-xl text-gray-900 focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-100 transition-all placeholder-gray-400">
                    <div id="customDropdownContainer" class="custom-select-container hidden relative">
                        <input type="hidden" id="hiddenSelectValue" name="judul_select">
                        <div class="custom-select-trigger border border-gray-300 rounded-xl px-4 py-3 flex justify-between items-center cursor-pointer hover:border-blue-500 transition-colors" id="customSelectTrigger">
                            <span id="customSelectText" class="text-gray-700 font-medium">-- Pilih Bagian --</span>
                            <svg class="arrow w-5 h-5 text-gray-400 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </div>
                        <div class="custom-select-options absolute w-full z-20 bg-white border border-gray-200 rounded-xl mt-2 shadow-xl overflow-hidden hidden" id="customSelectOptions"></div>
                    </div>
                </div>
                <div id="tambahGroupSubJudul" class="hidden">
                    <label id="labelSubJudul" class="block text-gray-900 text-sm font-bold mb-2">Sub Judul</label>
                    <input type="text" id="inputSubJudul" name="sub_judul" class="w-full px-4 py-3 border border-gray-300 rounded-xl text-gray-900 focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-100 transition-all placeholder-gray-400">
                </div>
                <div>
                    <label id="labelIsi" class="block text-gray-900 text-sm font-bold mb-2">Deskripsi</label>
                    <textarea id="inputIsi" name="isi" rows="3" class="w-full px-4 py-3 border border-gray-300 rounded-xl text-gray-900 focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-100 transition-all resize-none placeholder-gray-400" required></textarea>
                </div>
                <div id="tambahFotoGroup">
                    <label class="block text-gray-900 text-sm font-bold mb-2">Foto</label>
                    <div class="flex items-center border border-gray-300 rounded-xl p-2 bg-gray-50/50 file-input-wrapper transition-colors">
                        <input type="file" name="file_utama" accept="image/*" class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-bold file:bg-[#003366] file:text-white hover:file:bg-blue-900 cursor-pointer">
                    </div>
                </div>
                <div class="flex justify-end gap-3 mt-8 pt-4">
                    <button type="button" onclick="closeModalAnimation('modalTambah')" class="px-6 py-2.5 rounded-xl border border-gray-300 text-gray-700 font-bold bg-white hover:bg-gray-50 hover:border-gray-400 transition-all active:scale-95">Batal</button>
                    <button type="submit" class="px-6 py-2.5 rounded-xl bg-[#003366] text-white font-bold hover:bg-blue-900 shadow-lg shadow-blue-900/20 transition-all active:scale-95">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    {{-- MODAL HAPUS --}}
    <div id="modalHapus" class="hidden fixed inset-0 z-50 flex items-center justify-center">
        <div id="modalHapusBackdrop" class="absolute inset-0 bg-black/60 backdrop-blur-sm opacity-0 modal-backdrop-transition"></div>
        <div id="modalHapusContent" class="bg-white w-full max-w-md rounded-2xl p-8 shadow-2xl relative transform scale-95 opacity-0 modal-content-transition z-10 text-center">
            <div class="w-20 h-20 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-6">
                <i class="fas fa-trash-alt text-3xl text-red-600"></i>
            </div>
            <h2 id="modalHapusTitle" class="text-2xl font-bold text-gray-900 mb-3">Hapus Konten</h2>
            <p id="modalHapusMessage" class="text-gray-600 text-lg mb-8 leading-relaxed">Yakin ingin menghapus item ini? Tindakan ini tidak dapat dibatalkan.</p>
            <form id="formHapus" method="POST" class="flex justify-center gap-3">
                @csrf @method('DELETE')
                <button type="button" onclick="closeModalAnimation('modalHapus')" class="px-6 py-2.5 rounded-xl border border-gray-300 text-gray-700 font-bold bg-white hover:bg-gray-50 transition-all active:scale-95">Batal</button>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-red-600 text-white font-bold hover:bg-red-700 shadow-lg shadow-red-600/30 transition-all active:scale-95">Hapus</button>
            </form>
        </div>
    </div>

    {{-- MODAL MEDIA --}}
    <div id="modalMedia" class="hidden fixed inset-0 z-50 flex items-center justify-center">
        <div id="modalMediaBackdrop" class="absolute inset-0 bg-black/60 backdrop-blur-sm opacity-0 modal-backdrop-transition"></div>
        <div id="modalMediaContent" class="bg-white w-96 rounded-2xl p-8 shadow-2xl relative transform scale-95 opacity-0 modal-content-transition z-10">
            <h2 class="text-xl font-bold mb-6 text-gray-900">Tambah Galeri Foto</h2>
            <form action="{{ route('admin.konten_media.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="konten_id" id="mediaKontenID">
                <div class="mb-6 border-2 border-dashed border-gray-300 rounded-xl p-8 text-center hover:bg-gray-50 hover:border-blue-400 transition-all cursor-pointer relative group">
                    <input type="file" name="file_path" accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" required>
                    <div class="group-hover:scale-110 transition-transform duration-300">
                        <i class="fas fa-cloud-upload-alt text-4xl text-gray-400 mb-3 group-hover:text-blue-500"></i>
                    </div>
                    <p class="text-sm text-gray-500 font-medium">Klik untuk upload foto</p>
                </div>
                <div class="flex justify-end gap-3">
                    <button type="button" onclick="closeModalAnimation('modalMedia')" class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg font-bold hover:bg-gray-50">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-[#003366] text-white rounded-lg font-bold hover:bg-blue-900 shadow-md">Upload</button>
                </div>
            </form>
        </div>
    </div>

    {{-- SCRIPT JAVASCRIPT --}}
    <script>
    function openModalAnimation(modalId) {
        const modal = document.getElementById(modalId);
        const backdrop = document.getElementById(modalId + 'Backdrop');
        const content = document.getElementById(modalId + 'Content');
        modal.classList.remove('hidden');
        setTimeout(() => {
            backdrop.classList.remove('opacity-0');
            content.classList.remove('opacity-0', 'scale-95');
            content.classList.add('scale-100');
        }, 10);
    }
    function closeModalAnimation(modalId) {
        const modal = document.getElementById(modalId);
        const backdrop = document.getElementById(modalId + 'Backdrop');
        const content = document.getElementById(modalId + 'Content');
        backdrop.classList.add('opacity-0');
        content.classList.remove('scale-100');
        content.classList.add('opacity-0', 'scale-95');
        setTimeout(() => { modal.classList.add('hidden'); }, 300);
    }

    // --- LOGIKA UTAMA ---

    function openTambahModal(id, kategoriNama, availableOptions = []) {
        document.getElementById('tambahKategoriID').value = id;
        
        const modalTitle = document.getElementById('modalTitle');
        const labelJudul = document.getElementById('labelJudul');
        const inputJudul = document.getElementById('inputJudul');
        const dropdownContainer = document.getElementById('customDropdownContainer');
        const hiddenSelectValue = document.getElementById('hiddenSelectValue');
        const dropdownOptions = document.getElementById('customSelectOptions');
        const dropdownTrigger = document.getElementById('customSelectTrigger');
        const labelIsi   = document.getElementById('labelIsi');
        const inputIsi   = document.getElementById('inputIsi');
        const fotoGroup  = document.getElementById('tambahFotoGroup');
        const groupSubJudul = document.getElementById('tambahGroupSubJudul');
        const inputSubJudul = document.getElementById('inputSubJudul');

        inputJudul.value = '';
        inputIsi.value = '';
        inputSubJudul.value = '';
        hiddenSelectValue.value = '';
        document.getElementById('customSelectText').innerText = '-- Pilih Bagian --';
        
        groupSubJudul.classList.add('hidden');
        fotoGroup.classList.remove('hidden');

        if (kategoriNama === 'Halaman Tentang Sekolah' || kategoriNama === 'Tentang Sekolah') {
            modalTitle.innerText = 'Tambah Tentang Sekolah';
            labelJudul.innerText = 'Bagian';
            inputJudul.classList.add('hidden'); inputJudul.removeAttribute('name'); 
            dropdownContainer.classList.remove('hidden'); hiddenSelectValue.setAttribute('name', 'judul');
            dropdownOptions.innerHTML = ''; 
            if (availableOptions.length === 0) {
                document.getElementById('customSelectText').innerText = 'Semua bagian sudah diisi';
            } else {
                availableOptions.forEach(opt => {
                    const optionDiv = document.createElement('div');
                    optionDiv.className = 'px-4 py-3 hover:bg-blue-50 cursor-pointer text-gray-700 border-b border-gray-100 last:border-0 transition-colors';
                    optionDiv.innerText = opt;
                    optionDiv.setAttribute('data-value', opt);
                    optionDiv.onclick = function(e) {
                        hiddenSelectValue.value = this.getAttribute('data-value');
                        document.getElementById('customSelectText').innerText = this.innerText;
                        document.getElementById('customSelectText').classList.add('text-gray-900', 'font-bold');
                        dropdownContainer.classList.remove('open');
                        document.getElementById('customSelectOptions').classList.add('hidden');
                        e.stopPropagation();
                    };
                    dropdownOptions.appendChild(optionDiv);
                });
            }
            dropdownTrigger.onclick = function(e) {
                if (availableOptions.length > 0) {
                    document.getElementById('customSelectOptions').classList.toggle('hidden');
                    dropdownTrigger.classList.toggle('ring-2');
                    dropdownTrigger.classList.toggle('ring-blue-100');
                }
                e.stopPropagation();
            };
            labelIsi.innerText = 'Isi Penjelasan';
            inputIsi.placeholder = 'Tuliskan detailnya di sini...';
            fotoGroup.classList.add('hidden');

        } else if (kategoriNama.includes('Ekstrakurikuler')) {
            modalTitle.innerText = 'Tambah Ekstrakurikuler';
            labelJudul.innerText = 'Nama Ekstrakurikuler';
            inputJudul.placeholder = 'Contoh: Pramuka';
            inputJudul.classList.remove('hidden'); inputJudul.setAttribute('name', 'judul');
            dropdownContainer.classList.add('hidden'); hiddenSelectValue.removeAttribute('name');
            labelIsi.innerText = 'Deskripsi';
            inputIsi.placeholder = 'Melatih kemandirian...';
            fotoGroup.classList.remove('hidden');

        } else if (kategoriNama.includes('Tenaga Pengajar')) {
            modalTitle.innerText = 'Tambah Tenaga Pengajar';
            labelJudul.innerText = 'Nama Guru';
            inputJudul.placeholder = 'Contoh: Ahmad Dahlan';
            inputJudul.classList.remove('hidden'); inputJudul.setAttribute('name', 'judul');
            dropdownContainer.classList.add('hidden'); hiddenSelectValue.removeAttribute('name');
            labelIsi.innerText = 'Jabatan';
            inputIsi.placeholder = 'Contoh: Kepala Sekolah / Guru Matematika';
            fotoGroup.classList.remove('hidden');

        } else if (kategoriNama.includes('PPDB')) {
            modalTitle.innerText = 'Tambah Informasi PPDB';
            labelJudul.innerText = 'Judul';
            inputJudul.placeholder = 'Contoh: Alur Pendaftaran';
            inputJudul.classList.remove('hidden'); inputJudul.setAttribute('name', 'judul');
            dropdownContainer.classList.add('hidden'); hiddenSelectValue.removeAttribute('name');
            groupSubJudul.classList.remove('hidden'); inputSubJudul.placeholder = 'Contoh: Tahun Ajaran 2025/2026';
            labelIsi.innerText = 'Isi / Keterangan';
            inputIsi.placeholder = 'Jelaskan detail informasi di sini...';
            fotoGroup.classList.add('hidden');

        } else {
            modalTitle.innerText = 'Tambah Konten Baru';
            labelJudul.innerText = 'Judul Konten';
            inputJudul.classList.remove('hidden'); inputJudul.setAttribute('name', 'judul');
            dropdownContainer.classList.add('hidden'); hiddenSelectValue.removeAttribute('name');
            labelIsi.innerText = 'Isi / Deskripsi';
            inputIsi.placeholder = 'Tulis deskripsi konten di sini...';
            fotoGroup.classList.remove('hidden');
        }
        
        openModalAnimation('modalTambah');
    }

    function openEditModal(id) {
        document.getElementById('formEdit').reset();

        fetch(`/admin/konten/json/${id}`)
            .then(res => res.json())
            .then(data => {
                const modalTitle = document.getElementById('modalEditTitle');
                const labelJudul = document.getElementById('editLabelJudul');
                const inputJudul = document.getElementById('editJudul');
                const labelIsi = document.getElementById('editLabelIsi');
                const inputIsi = document.getElementById('editIsi');
                const editFotoGroup = document.getElementById('editFotoGroup');
                const linkLihatFoto = document.getElementById('linkLihatFoto');
                const groupSubJudul = document.getElementById('editGroupSubJudul');
                const inputSubJudul = document.getElementById('editSubJudul');

                inputJudul.value = data.judul;
                inputIsi.value = data.isi;
                if(data.sub_judul) inputSubJudul.value = data.sub_judul; else inputSubJudul.value = '';
                document.getElementById('formEdit').action = `/admin/konten/${id}`;
                
                let kategoriNama = data.kategori_nama || (data.kategori ? data.kategori.nama : '');
                kategoriNama = kategoriNama.toLowerCase().trim();

                groupSubJudul.classList.add('hidden');
                editFotoGroup.classList.remove('hidden');
                inputJudul.readOnly = false; inputJudul.classList.remove('bg-gray-100', 'text-gray-500', 'cursor-not-allowed');

                if (kategoriNama.includes('ekstrakurikuler')) {
                    modalTitle.innerText = 'Edit Ekstrakurikuler';
                    labelJudul.innerText = 'Nama Ekstrakurikuler';
                    labelIsi.innerText = 'Deskripsi';
                
                } else if (kategoriNama.includes('tentang sekolah')) {
                    modalTitle.innerText = 'Edit Tentang Sekolah';
                    labelJudul.innerText = 'Bagian';
                    labelIsi.innerText = 'Isi';
                    inputJudul.readOnly = true; inputJudul.classList.add('bg-gray-100', 'text-gray-500', 'cursor-not-allowed');
                    editFotoGroup.classList.add('hidden');
                
                } else if (kategoriNama.includes('tenaga pengajar')) {
                    modalTitle.innerText = 'Edit Tenaga Pengajar';
                    labelJudul.innerText = 'Nama Guru';
                    labelIsi.innerText = 'Jabatan';

                } else if (kategoriNama.includes('ppdb')) {
                    modalTitle.innerText = 'Edit Informasi PPDB';
                    labelJudul.innerText = 'Judul';
                    groupSubJudul.classList.remove('hidden');
                    labelIsi.innerText = 'Isi / Keterangan';
                    editFotoGroup.classList.add('hidden');

                } else if (kategoriNama.includes('beranda')) {
                    modalTitle.innerText = 'Edit Halaman Beranda';
                    labelJudul.innerText = 'Judul';
                    labelIsi.innerText = 'Deskripsi';
                
                } else {
                    modalTitle.innerText = 'Edit Konten';
                    labelJudul.innerText = 'Judul';
                    labelIsi.innerText = 'Deskripsi';
                }

                if (!editFotoGroup.classList.contains('hidden')) {
                    if (data.file_utama_url) {
                        linkLihatFoto.href = data.file_utama_url;
                        linkLihatFoto.classList.remove('hidden');
                    } else {
                        linkLihatFoto.href = '#';
                        linkLihatFoto.classList.add('hidden');
                    }
                }
            })
            .catch(err => console.error("Error Fetching:", err));

        openModalAnimation('modalEdit');
    }

    function openHapusModal(url, title, message) {
        const form = document.getElementById('formHapus');
        const titleEl = document.getElementById('modalHapusTitle');
        const msgEl = document.getElementById('modalHapusMessage');
        form.action = url;
        if (title) titleEl.innerText = title;
        if (message) msgEl.innerText = message;
        openModalAnimation('modalHapus');
    }
    
    function openMediaModal(id) { 
        document.getElementById('mediaKontenID').value = id; 
        openModalAnimation('modalMedia'); 
    }

    window.addEventListener('click', function(e) {
        const dropdownOptions = document.getElementById('customSelectOptions');
        const dropdownTrigger = document.getElementById('customSelectTrigger');
        if (dropdownOptions && !dropdownOptions.contains(e.target) && !dropdownTrigger.contains(e.target)) {
            dropdownOptions.classList.add('hidden');
        }
        if(e.target.id.includes('Backdrop')) {
            const modalId = e.target.id.replace('Backdrop', '');
            closeModalAnimation(modalId);
        }
    });
    </script>

@endsection