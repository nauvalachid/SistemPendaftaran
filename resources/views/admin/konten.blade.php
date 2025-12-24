@extends('admin.layouts.app')

@section('title', 'Kelola Konten')

@section('content')

    {{-- LOAD CSS KUSTOM --}}
    @vite(['resources/css/custom-dropdown.css', 'resources/css/animations.css'])

    <div class="flex min-h-screen bg-gray-50">
        
        {{-- SIDEBAR --}}
        <div class="h-screen sticky top-0 shrink-0">
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
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded shadow-sm">
                <p class="font-bold">Berhasil!</p> <p>{{ session('success') }}</p>
            </div>
            @endif

            {{-- LOOP KATEGORI --}}
            @foreach($kategori as $kat)
            <div class="rounded-2xl bg-white p-8 shadow-sm border border-gray-100 mb-8 hover:shadow-md transition-shadow duration-300">

                @php
                    $namaKat = $kat->nama;
                    $isTentang = (str_contains(strtolower($namaKat), 'tentang sekolah'));
                    $isEskul = (str_contains(strtolower($namaKat), 'ekstrakurikuler'));
                    $isGuru = (str_contains(strtolower($namaKat), 'tenaga pengajar'));
                    $isPPDB = (str_contains(strtolower($namaKat), 'ppdb'));
                    $isBeranda = (str_contains(strtolower($namaKat), 'beranda'));

                    // Logika Tombol Tambah
                    $tampilTombol = true;
                    $opsiTersedia = []; 

                    if (($isBeranda || $isPPDB) && $kat->konten->count() > 0) {
                        $tampilTombol = false;
                    }
                    
                    if ($isTentang) {
                        $sudahAda = $kat->konten->pluck('judul')->map(fn($i) => strtolower(trim($i)))->toArray();
                        $semuaOpsi = ['Sejarah', 'Visi', 'Misi'];
                        foreach ($semuaOpsi as $opsi) { 
                            if (!in_array(strtolower($opsi), $sudahAda)) { $opsiTersedia[] = $opsi; } 
                        }
                        if (empty($opsiTersedia)) { $tampilTombol = false; }
                    }
                @endphp

                <div class="flex items-center mb-6 pb-4 border-b border-gray-100 justify-between">
                    <h2 class="text-xl font-extrabold text-black">
                        {{ str_starts_with($namaKat, 'Halaman') ? $namaKat : 'Halaman ' . $namaKat }}
                    </h2>

                    @if ($tampilTombol)
                    <button class="text-sm font-semibold bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition shadow-sm flex items-center gap-2 active:scale-95 transform"
                        onclick='openTambahModal({{ $kat->id }}, "{{ $namaKat }}", @json($opsiTersedia))'>
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
                            
                            {{-- Preview Media Jika Ada --}}
                            @if($konten->media->count() > 0)
                            <div class="flex gap-2 mt-4 overflow-x-auto pb-2">
                                @foreach($konten->media as $m)
                                <div class="relative shrink-0">
                                    <img src="{{ asset('storage/' . $m->file_path) }}" class="h-20 w-20 object-cover rounded-lg border">
                                    <form action="{{ route('admin.konten_media.destroy', $m->id) }}" method="POST" class="absolute -top-1 -right-1">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="bg-red-500 text-white rounded-full w-4 h-4 text-[10px] flex items-center justify-center shadow" onclick="return confirm('Hapus foto?')">&times;</button>
                                    </form>
                                </div>
                                @endforeach
                            </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
            @endforeach
        </main>
    </div>

    {{-- ========================================================== --}}
    {{-- MODAL TAMBAH --}}
    {{-- ========================================================== --}}
    <div id="modalTambah" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">
        <div id="modalTambahBackdrop" class="absolute inset-0 bg-black/60 backdrop-blur-sm opacity-0 transition-opacity duration-300"></div>
        <div id="modalTambahContent" class="bg-white w-full max-w-2xl rounded-2xl p-8 shadow-2xl relative transform scale-95 opacity-0 transition-all duration-300 z-10">
            <h2 id="modalTitle" class="text-2xl font-bold text-gray-900 mb-6 text-center">Tambah Konten</h2>
            
            <form action="{{ route('admin.konten.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                @csrf
                <input type="hidden" name="kategori_konten_id" id="tambahKategoriID">
                
                {{-- Field Judul / Dropdown --}}
                <div>
                    <label id="labelJudul" class="block text-sm font-bold text-gray-700 mb-2">Judul</label>
                    <input type="text" id="inputJudul" name="judul" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-100 outline-none transition-all">
                    
                    {{-- Dropdown Khusus Tentang Sekolah --}}
                    <div id="customDropdownContainer" class="hidden relative">
                        <input type="hidden" id="hiddenSelectValue" name="judul_select">
                        <div class="border border-gray-300 rounded-xl px-4 py-3 flex justify-between items-center cursor-pointer hover:border-blue-500" id="customSelectTrigger">
                            <span id="customSelectText" class="text-gray-500">-- Pilih Bagian --</span>
                            <i class="fas fa-chevron-down text-gray-400"></i>
                        </div>
                        <div id="customSelectOptions" class="absolute w-full z-20 bg-white border border-gray-200 rounded-xl mt-2 shadow-xl hidden overflow-hidden">
                            {{-- Opsi diisi via JS --}}
                        </div>
                    </div>
                </div>

                <div id="tambahGroupSubJudul" class="hidden">
                    <label class="block text-sm font-bold text-gray-700 mb-2">Sub Judul</label>
                    <input type="text" name="sub_judul" class="w-full px-4 py-3 border border-gray-300 rounded-xl">
                </div>

                <div>
                    <label id="labelIsi" class="block text-sm font-bold text-gray-700 mb-2">Deskripsi</label>
                    <textarea name="isi" id="inputIsi" rows="4" class="w-full px-4 py-3 border border-gray-300 rounded-xl outline-none focus:ring-2 focus:ring-blue-100 transition-all" required></textarea>
                </div>

                <div id="tambahFotoGroup">
                    <label class="block text-sm font-bold text-gray-700 mb-2">Foto Utama</label>
                    <input type="file" name="file_utama" accept="image/*" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                </div>

                <div class="flex justify-end gap-3 pt-4">
                    <button type="button" onclick="closeModalAnimation('modalTambah')" class="px-6 py-2.5 rounded-xl border border-gray-300 font-bold hover:bg-gray-50">Batal</button>
                    <button type="submit" class="px-6 py-2.5 rounded-xl bg-blue-600 text-white font-bold hover:bg-blue-700 shadow-lg">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    {{-- MODAL EDIT --}}
    <div id="modalEdit" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">
        <div id="modalEditBackdrop" class="absolute inset-0 bg-black/60 backdrop-blur-sm opacity-0 transition-opacity duration-300"></div>
        <div id="modalEditContent" class="bg-white w-full max-w-2xl rounded-2xl p-8 shadow-2xl relative transform scale-95 opacity-0 transition-all duration-300 z-10">
            <h2 id="modalEditTitle" class="text-2xl font-bold text-gray-900 mb-6 text-center">Edit Konten</h2>
            <form id="formEdit" method="POST" enctype="multipart/form-data" class="space-y-5">
                @csrf @method('PUT')
                <div>
                    <label id="editLabelJudul" class="block text-sm font-bold text-gray-700 mb-2">Judul</label>
                    <input type="text" id="editJudul" name="judul" class="w-full px-4 py-3 border border-gray-300 rounded-xl outline-none focus:ring-2 focus:ring-blue-100" required>
                </div>
                <div id="editGroupSubJudul" class="hidden">
                    <label class="block text-sm font-bold text-gray-700 mb-2">Sub Judul</label>
                    <input type="text" id="editSubJudul" name="sub_judul" class="w-full px-4 py-3 border border-gray-300 rounded-xl">
                </div>
                <div>
                    <label id="editLabelIsi" class="block text-sm font-bold text-gray-700 mb-2">Deskripsi</label>
                    <textarea id="editIsi" name="isi" rows="4" class="w-full px-4 py-3 border border-gray-300 rounded-xl outline-none" required></textarea>
                </div>
                <div id="editFotoGroup">
                    <label class="block text-sm font-bold text-gray-700 mb-2">Ganti Foto Utama</label>
                    <input type="file" name="file_utama" accept="image/*" class="w-full text-sm">
                </div>
                <div class="flex justify-end gap-3 pt-4">
                    <button type="button" onclick="closeModalAnimation('modalEdit')" class="px-6 py-2.5 rounded-xl border border-gray-300 font-bold hover:bg-gray-50">Batal</button>
                    <button type="submit" class="px-6 py-2.5 rounded-xl bg-yellow-500 text-white font-bold hover:bg-yellow-600">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>

    {{-- MODAL HAPUS --}}
    <div id="modalHapus" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">
        <div id="modalHapusBackdrop" class="absolute inset-0 bg-black/60 backdrop-blur-sm opacity-0 transition-opacity duration-300"></div>
        <div id="modalHapusContent" class="bg-white w-full max-w-md rounded-2xl p-8 shadow-2xl relative transform scale-95 opacity-0 transition-all duration-300 z-10 text-center">
            <div class="w-16 h-16 bg-red-100 text-red-600 rounded-full flex items-center justify-center mx-auto mb-4 text-2xl">
                <i class="fas fa-trash"></i>
            </div>
            <h2 class="text-xl font-bold mb-2">Hapus Data?</h2>
            <p class="text-gray-500 mb-6">Data yang dihapus tidak dapat dikembalikan.</p>
            <form id="formHapus" method="POST" class="flex justify-center gap-3">
                @csrf @method('DELETE')
                <button type="button" onclick="closeModalAnimation('modalHapus')" class="px-6 py-2 rounded-xl border border-gray-300 font-bold">Batal</button>
                <button type="submit" class="px-6 py-2 rounded-xl bg-red-600 text-white font-bold">Ya, Hapus</button>
            </form>
        </div>
    </div>

    {{-- MODAL MEDIA (GALERI) --}}
    <div id="modalMedia" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">
        <div id="modalMediaBackdrop" class="absolute inset-0 bg-black/60 opacity-0 transition-opacity duration-300"></div>
        <div id="modalMediaContent" class="bg-white w-full max-w-md rounded-2xl p-8 relative scale-95 opacity-0 transition-all duration-300 z-10">
            <h2 class="text-xl font-bold mb-4">Tambah Galeri Foto</h2>
            <form action="{{ route('admin.konten_media.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="konten_id" id="mediaKontenID">
                <div class="border-2 border-dashed border-gray-300 rounded-xl p-6 text-center hover:bg-gray-50 mb-4">
                    <input type="file" name="file_path" accept="image/*" class="w-full" required>
                </div>
                <div class="flex justify-end gap-2">
                    <button type="button" onclick="closeModalAnimation('modalMedia')" class="px-4 py-2 border rounded-lg">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg">Upload</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Animasi Dasar Modal
        function openModalAnimation(modalId) {
            const modal = document.getElementById(modalId);
            const backdrop = document.getElementById(modalId + 'Backdrop');
            const content = document.getElementById(modalId + 'Content');
            modal.classList.remove('hidden');
            setTimeout(() => {
                backdrop.classList.add('opacity-100');
                content.classList.remove('opacity-0', 'scale-95');
                content.classList.add('opacity-100', 'scale-100');
            }, 10);
        }

        function closeModalAnimation(modalId) {
            const backdrop = document.getElementById(modalId + 'Backdrop');
            const content = document.getElementById(modalId + 'Content');
            backdrop.classList.remove('opacity-100');
            content.classList.remove('opacity-100', 'scale-100');
            content.classList.add('opacity-0', 'scale-95');
            setTimeout(() => { document.getElementById(modalId).classList.add('hidden'); }, 300);
        }

        // --- Logika Modal Tambah ---
        function openTambahModal(id, kategoriNama, availableOptions = []) {
            document.getElementById('tambahKategoriID').value = id;
            
            const isTentang = kategoriNama.toLowerCase().includes('tentang');
            const isPPDB = kategoriNama.toLowerCase().includes('ppdb');
            const isGuru = kategoriNama.toLowerCase().includes('pengajar');
            const isEskul = kategoriNama.toLowerCase().includes('ekstra');

            // Reset Fields
            const inputJudul = document.getElementById('inputJudul');
            const dropdown = document.getElementById('customDropdownContainer');
            const subJudulGroup = document.getElementById('tambahGroupSubJudul');
            const fotoGroup = document.getElementById('tambahFotoGroup');
            const labelJudul = document.getElementById('labelJudul');
            const optionsBox = document.getElementById('customSelectOptions');
            const trigger = document.getElementById('customSelectTrigger');

            // Default State
            inputJudul.classList.remove('hidden');
            inputJudul.name = "judul";
            dropdown.classList.add('hidden');
            subJudulGroup.classList.add('hidden');
            fotoGroup.classList.remove('hidden');
            labelJudul.innerText = "Judul";

            if (isTentang) {
                labelJudul.innerText = "Bagian Halaman";
                inputJudul.classList.add('hidden');
                inputJudul.name = ""; // Jangan pakai name agar tidak konflik
                dropdown.classList.remove('hidden');
                document.getElementById('hiddenSelectValue').name = "judul"; // Name pindah ke dropdown
                fotoGroup.classList.add('hidden');

                // Render Opsi Dropdown
                optionsBox.innerHTML = '';
                availableOptions.forEach(opt => {
                    const div = document.createElement('div');
                    div.className = "px-4 py-3 hover:bg-blue-50 cursor-pointer border-b last:border-0";
                    div.innerText = opt;
                    div.onclick = () => {
                        document.getElementById('hiddenSelectValue').value = opt;
                        document.getElementById('customSelectText').innerText = opt;
                        document.getElementById('customSelectText').className = "text-black font-bold";
                        optionsBox.classList.add('hidden');
                    };
                    optionsBox.appendChild(div);
                });

                trigger.onclick = (e) => {
                    e.stopPropagation();
                    optionsBox.classList.toggle('hidden');
                };
            } else if (isPPDB) {
                subJudulGroup.classList.remove('hidden');
                fotoGroup.classList.add('hidden');
            } else if (isGuru) {
                labelJudul.innerText = "Nama Guru";
                document.getElementById('labelIsi').innerText = "Jabatan";
            } else if (isEskul) {
                labelJudul.innerText = "Nama Ekstrakurikuler";
            }

            openModalAnimation('modalTambah');
        }

        // --- Logika Modal Edit ---
        function openEditModal(id) {
            fetch(`/admin/konten/json/${id}`)
                .then(res => res.json())
                .then(data => {
                    const form = document.getElementById('formEdit');
                    form.action = `/admin/konten/${id}`;
                    
                    document.getElementById('editJudul').value = data.judul;
                    document.getElementById('editIsi').value = data.isi;
                    
                    const subGroup = document.getElementById('editGroupSubJudul');
                    if (data.sub_judul || data.kategori_nama.includes('PPDB')) {
                        subGroup.classList.remove('hidden');
                        document.getElementById('editSubJudul').value = data.sub_judul || '';
                    } else {
                        subGroup.classList.add('hidden');
                    }

                    // Proteksi judul untuk Tentang Sekolah agar tidak diubah sembarang
                    if (data.kategori_nama.toLowerCase().includes('tentang')) {
                        document.getElementById('editJudul').readOnly = true;
                        document.getElementById('editJudul').classList.add('bg-gray-100');
                    } else {
                        document.getElementById('editJudul').readOnly = false;
                        document.getElementById('editJudul').classList.remove('bg-gray-100');
                    }

                    openModalAnimation('modalEdit');
                });
        }

        function openHapusModal(url) {
            document.getElementById('formHapus').action = url;
            openModalAnimation('modalHapus');
        }

        function openMediaModal(id) {
            document.getElementById('mediaKontenID').value = id;
            openModalAnimation('modalMedia');
        }

        // Close dropdown when clicking outside
        window.addEventListener('click', () => {
            document.getElementById('customSelectOptions').classList.add('hidden');
        });
    </script>
@endsection