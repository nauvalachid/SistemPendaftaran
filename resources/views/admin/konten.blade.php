@extends('admin.layouts.app')

@section('title', 'Kelola Konten')

@section('content')

    {{-- LOAD CSS KUSTOM --}}
    @vite(['resources/css/custom-dropdown.css'])

    <div class="flex min-h-screen bg-gray-50">
        
        <x-sidebar /> 

        <main class="w-full overflow-y-auto p-6 lg:p-6">

            {{-- HEADER HALAMAN --}}
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-black">Kelola Konten</h1>
                <p class="mt-2 text-gray-600">Kelola seluruh konten halaman website sekolah di sini.</p>
                <hr class="my-5 border-gray-300">
            </div>

            {{-- ALERT SUCCESS --}}
            @if (session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded shadow-sm" role="alert">
                <p class="font-bold">Berhasil!</p>
                <p>{{ session('success') }}</p>
            </div>
            @endif
            
            {{-- ALERT ERROR --}}
            @if ($errors->any())
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded shadow-sm" role="alert">
                    <p class="font-bold">Gagal!</p>
                    <ul class="list-disc list-inside text-sm mt-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- LOOP KATEGORI --}}
            @foreach($kategori as $kat)
            
            <div class="rounded-2xl bg-white p-8 shadow-sm border border-gray-100 mb-8">

                {{-- HEADER KATEGORI --}}
                <div class="flex justify-between items-center mb-6 pb-4 border-b border-gray-100">
                    <h2 class="text-xl font-extrabold text-black">
                        {{ $kat->nama === 'Beranda' ? 'Halaman Beranda' : $kat->nama }}
                    </h2>

                    {{-- LOGIKA TOMBOL TAMBAH --}}
                    @php
                        $tampilTombol = true;
                        $opsiTersedia = []; 

                        // 1. KHUSUS BERANDA: Hanya boleh 1 data
                        if (($kat->nama === 'Halaman Beranda' || $kat->nama === 'Beranda') && $kat->konten->count() > 0) {
                            $tampilTombol = false;
                        }
                        
                        // 2. TENTANG SEKOLAH: Cek opsi sisa
                        if ($kat->nama === 'Halaman Tentang Sekolah' || $kat->nama === 'Tentang Sekolah') {
                            $sudahAda = $kat->konten->pluck('judul')->map(function($item) {
                                return strtolower(trim($item)); 
                            })->toArray();

                            $semuaOpsi = ['Sejarah', 'Visi', 'Misi'];
                            foreach ($semuaOpsi as $opsi) {
                                if (!in_array(strtolower($opsi), $sudahAda)) { $opsiTersedia[] = $opsi; }
                            }
                            if (empty($opsiTersedia)) { $tampilTombol = false; }
                        }
                    @endphp

                    @if ($tampilTombol)
                    <button 
                        class="text-sm font-semibold bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition shadow-sm flex items-center gap-2"
                        onclick='openTambahModal({{ $kat->id }}, "{{ $kat->nama }}", @json($opsiTersedia))'>
                        <span>+</span> Tambah
                    </button>
                    @endif
                </div>

                {{-- ISI KONTEN --}}
                
                {{-- 1. TAMPILAN KHUSUS: TENTANG SEKOLAH --}}
                @if ($kat->nama === 'Halaman Tentang Sekolah' || $kat->nama === 'Tentang Sekolah')
                    <div class="space-y-8"> 
                        @foreach($kat->konten as $konten)
                        <div class="flex flex-col md:flex-row justify-between items-start gap-4 group">
                            <div class="flex-1 pl-4 md:pl-6">
                                <h3 class="text-lg font-bold text-black mb-2">{{ $konten->judul }}</h3> 
                                <p class="text-gray-700 text-sm leading-relaxed text-justify">
                                    {{ $konten->isi }}
                                </p>
                            </div>
                            <div class="flex gap-2 flex-shrink-0">
                                <button class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-1.5 rounded-lg text-xs font-bold shadow-sm transition"
                                    onclick="openEditModal({{ $konten->id }})"> {{-- CUKUP ID SAJA --}}
                                    <i class="fas fa-pen mr-1"></i> Edit
                                </button>
                                
                                <form action="{{ route('admin.konten.destroy', $konten->id) }}" method="POST">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-1.5 rounded-lg text-xs font-bold shadow-sm transition"
                                        onclick="return confirm('Yakin hapus konten ini? Opsi akan kembali tersedia di tombol Tambah.')">
                                        <i class="fas fa-trash-alt mr-1"></i> Hapus
                                    </button>
                                </form>
                            </div>
                        </div>
                        @if(!$loop->last) <hr class="border-gray-100"> @endif
                        @endforeach
                    </div>

                {{-- 2. TAMPILAN UMUM --}}
                @else
                    <div class="space-y-6">
                        @foreach($kat->konten as $konten)
                        @php
                            $isBeranda = ($kat->nama === 'Halaman Beranda' || $kat->nama === 'Beranda');
                            $foto_utama = $konten->media->where('urutan', 0)->first();
                        @endphp

                        @if ($isBeranda)
                            {{-- BERANDA --}}
                            <div class="py-4 border-b border-gray-100 last:border-0">
                                <div class="flex flex-col md:flex-row justify-between items-start gap-8">
                                    <div class="flex-1 pl-4 md:pl-6">
                                        <h3 class="text-2xl font-extrabold text-black mb-4">{{ $konten->judul }}</h3>
                                        <p class="text-gray-700 text-base leading-relaxed mb-6">{{ $konten->isi }}</p>
                                        <button class="bg-yellow-500 hover:bg-yellow-600 text-white px-6 py-2.5 rounded-lg font-bold shadow-sm transition flex items-center gap-2"
                                            onclick="openEditModal({{ $konten->id }})"> {{-- CUKUP ID SAJA --}}
                                            <i class="fas fa-pen text-sm"></i> Edit
                                        </button>
                                    </div>
                                    @if ($foto_utama)
                                    <div class="w-full md:w-1/3 flex-shrink-0">
                                        <img src="{{ asset('storage/' . $foto_utama->file_path) }}" class="w-full h-48 object-cover rounded-lg shadow-md border border-gray-100 hover:shadow-lg transition">
                                    </div>
                                    @endif
                                </div>
                            </div>
                        @else
                            {{-- LAINNYA (CARD) --}}
                            <div class="border border-gray-200 rounded-xl p-5 hover:border-blue-300 transition duration-200 bg-gray-50">
                                <div class="flex justify-between items-start gap-4">
                                    <div class="flex-1">
                                        <h3 class="text-lg font-bold text-black">{{ $konten->judul }}</h3>
                                        <p class="mt-2 text-gray-600 text-sm leading-relaxed">{{ $konten->isi }}</p>
                                    </div>
                                    <div class="flex gap-2 flex-shrink-0">
                                        <button class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1.5 rounded-lg text-xs font-bold shadow-sm transition" 
                                            onclick="openEditModal({{ $konten->id }})">Edit</button>
                                        
                                        <form action="{{ route('admin.konten.destroy', $konten->id) }}" method="POST">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-3 py-1.5 rounded-lg text-xs font-bold shadow-sm transition" onclick="return confirm('Yakin hapus konten ini?')">Hapus</button>
                                        </form>
                                    </div>
                                </div>
                                <div class="mt-4 flex gap-4 overflow-x-auto pb-2">
                                    @if ($foto_utama)
                                    <div class="flex-shrink-0 relative group">
                                        <span class="absolute top-1 left-1 bg-black bg-opacity-60 text-white text-[10px] px-2 py-0.5 rounded">Utama</span>
                                        <img src="{{ asset('storage/' . $foto_utama->file_path) }}" class="h-24 w-40 object-cover rounded-lg border border-gray-200 shadow-sm">
                                    </div>
                                    @endif
                                    @foreach($konten->media->where('urutan', '>', 0) as $m)
                                    <div class="flex-shrink-0 relative w-24 h-24 group">
                                        <img src="{{ asset('storage/' . $m->file_path) }}" class="w-full h-full object-cover rounded-lg border border-gray-200 shadow-sm">
                                        <form action="{{ route('admin.konten_media.destroy', $m->id) }}" method="POST" class="absolute -top-2 -right-2 hidden group-hover:block">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="bg-red-600 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs shadow-md" onclick="return confirm('Hapus foto ini?')">&times;</button>
                                        </form>
                                    </div>
                                    @endforeach
                                    <button onclick="openMediaModal({{ $konten->id }})" class="flex-shrink-0 w-24 h-24 border-2 border-dashed border-gray-300 rounded-lg flex items-center justify-center text-gray-400 hover:border-blue-500 hover:text-blue-500 transition"><i class="fas fa-plus text-xl"></i></button>
                                </div>
                                @if($konten->list->count())
                                <div class="mt-4 bg-white p-3 rounded-lg border border-gray-100">
                                    <ul class="text-sm text-gray-800 list-disc pl-5">@foreach($konten->list as $li) <li>{{ $li->item }}</li> @endforeach</ul>
                                </div>
                                @endif
                                <div class="mt-2 text-right"><button class="text-xs text-blue-600 font-semibold hover:underline" onclick="openEditListModal({{ $konten->id }})">+ Kelola List Detail</button></div>
                            </div>
                        @endif 
                        @endforeach
                    </div>
                @endif
            </div>
            @endforeach
        </main>
    </div>

    {{-- MODAL EDIT KONTEN (DENGAN ID BARU) --}}
    <div id="modalEdit" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 backdrop-blur-sm">
        <div class="bg-white w-full max-w-md rounded-2xl p-6 shadow-2xl">
            {{-- Tambahkan ID ini untuk diubah lewat JS --}}
            <h2 id="modalEditTitle" class="text-xl font-bold text-gray-900 mb-5">Edit Konten</h2>
            <form id="formEdit" method="POST" enctype="multipart/form-data">
                @csrf @method('PUT')
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Judul</label>
                    <input type="text" id="editJudul" name="judul" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-yellow-400 transition" required>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Isi</label>
                    <textarea id="editIsi" name="isi" rows="5" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-yellow-400 transition" required></textarea>
                </div>
                <div id="editFotoGroup" class="mb-6 bg-gray-50 p-3 rounded-lg border border-gray-200">
                    <p class="text-xs font-bold text-gray-500 mb-2 uppercase">Foto Utama Saat Ini</p>
                    <img id="currentFotoUtama" src="" class="w-full h-32 object-cover rounded-lg mb-3 shadow-sm">
                    <label class="text-xs text-blue-600 cursor-pointer hover:underline font-bold">
                        <input type="file" name="file_utama" accept="image/*" class="hidden">
                        + Ganti Foto Baru
                    </label>
                </div>
                <div class="flex justify-end gap-3">
                    <button type="button" onclick="closeEditModal()" class="px-5 py-2.5 bg-gray-100 text-gray-700 rounded-xl font-medium hover:bg-gray-200">Batal</button>
                    <button type="submit" class="px-5 py-2.5 bg-yellow-500 text-white rounded-xl font-bold hover:bg-yellow-600 shadow-lg shadow-yellow-500/30">Update</button>
                </div>
            </form>
        </div>
    </div>

    {{-- MODAL TAMBAH & MEDIA (Sama seperti sebelumnya) --}}
    <div id="modalTambah" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 backdrop-blur-sm">
        <div class="bg-white w-full max-w-md rounded-2xl p-6 shadow-2xl transform transition-all scale-100">
            <div class="flex justify-between items-center mb-5">
                <h2 id="modalTitle" class="text-xl font-bold text-gray-900">Tambah Konten Baru</h2>
                <button onclick="closeTambahModal()" class="text-gray-400 hover:text-gray-600">&times;</button>
            </div>
            <form action="{{ route('admin.konten.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="kategori_konten_id" id="tambahKategoriID">
                <div class="mb-4">
                    <label id="labelJudul" class="block text-gray-700 text-sm font-bold mb-2">Judul Konten</label>
                    <input type="text" id="inputJudul" name="judul" class="w-full px-3 py-2 border border-gray-300 rounded-lg transition" placeholder="Contoh: Kegiatan Tari">
                    <div id="customDropdownContainer" class="custom-select-container hidden">
                        <input type="hidden" id="hiddenSelectValue" name="judul_select">
                        <div class="custom-select-trigger" id="customSelectTrigger">
                            <span id="customSelectText">-- Pilih Bagian --</span>
                            <svg class="arrow w-5 h-5 text-gray-400 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </div>
                        <div class="custom-select-options" id="customSelectOptions"></div>
                    </div>
                </div>
                <div class="mb-4">
                    <label id="labelIsi" class="block text-gray-700 text-sm font-bold mb-2">Isi / Deskripsi</label>
                    <textarea id="inputIsi" name="isi" rows="4" class="w-full px-3 py-2 border border-gray-300 rounded-lg transition" required></textarea>
                </div>
                <div id="tambahFotoGroup" class="mb-6">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Foto Utama (Cover)</label>
                    <div class="relative border-2 border-dashed border-gray-300 rounded-lg p-4 hover:bg-gray-50 transition text-center cursor-pointer">
                        <input type="file" name="file_utama" accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                        <div class="text-gray-500"><i class="fas fa-cloud-upload-alt text-2xl mb-1"></i><p class="text-xs">Klik atau seret foto ke sini</p></div>
                    </div>
                </div>
                <div class="flex justify-end gap-3">
                    <button type="button" onclick="closeTambahModal()" class="px-5 py-2.5 bg-gray-100 rounded-xl">Batal</button>
                    <button type="submit" class="px-5 py-2.5 bg-blue-600 text-white rounded-xl">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <div id="modalMedia" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 backdrop-blur-sm">
        <div class="bg-white w-96 rounded-2xl p-6 shadow-2xl">
            <h2 class="text-lg font-bold mb-4">Tambah Galeri Foto</h2>
            <form action="{{ route('admin.konten_media.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="konten_id" id="mediaKontenID">
                <div class="mb-4 border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:bg-gray-50 cursor-pointer relative">
                    <input type="file" name="file_path" accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" required>
                    <i class="fas fa-images text-3xl text-gray-400 mb-2"></i><p class="text-sm text-gray-500">Klik untuk upload foto</p>
                </div>
                <div class="flex justify-end gap-2">
                    <button type="button" onclick="closeMediaModal()" class="px-4 py-2 bg-gray-100 rounded-lg">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg">Upload</button>
                </div>
            </form>
        </div>
    </div>

    {{-- SCRIPT --}}
    <script>
    // 1. MODAL TAMBAH
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

        if (kategoriNama === 'Halaman Tentang Sekolah' || kategoriNama === 'Tentang Sekolah') {
            modalTitle.innerText = 'Tambah Tentang Sekolah';
            labelJudul.innerText = 'Bagian (Pilih: Sejarah / Visi / Misi)';
            inputJudul.classList.add('hidden');
            inputJudul.removeAttribute('name'); 
            dropdownContainer.classList.remove('hidden');
            hiddenSelectValue.setAttribute('name', 'judul');
            document.getElementById('customSelectText').innerText = '-- Pilih Bagian --';
            hiddenSelectValue.value = '';
            dropdownOptions.innerHTML = ''; 

            if (availableOptions.length === 0) {
                document.getElementById('customSelectText').innerText = 'Semua bagian sudah diisi';
            } else {
                availableOptions.forEach(opt => {
                    const optionDiv = document.createElement('div');
                    optionDiv.className = 'custom-select-option';
                    optionDiv.innerText = opt;
                    optionDiv.setAttribute('data-value', opt);
                    optionDiv.onclick = function(e) {
                        hiddenSelectValue.value = this.getAttribute('data-value');
                        document.getElementById('customSelectText').innerText = this.innerText;
                        dropdownContainer.classList.remove('open');
                        e.stopPropagation();
                    };
                    dropdownOptions.appendChild(optionDiv);
                });
            }
            dropdownTrigger.onclick = function(e) {
                if (availableOptions.length > 0) dropdownContainer.classList.toggle('open');
                e.stopPropagation();
            };
            labelIsi.innerText = 'Isi Penjelasan';
            inputIsi.placeholder = 'Tuliskan detailnya di sini...';
            fotoGroup.classList.add('hidden');
        } else {
            modalTitle.innerText = 'Tambah Konten Baru';
            labelJudul.innerText = 'Judul Konten';
            inputJudul.classList.remove('hidden');
            inputJudul.setAttribute('name', 'judul');
            dropdownContainer.classList.add('hidden');
            hiddenSelectValue.removeAttribute('name');
            labelIsi.innerText = 'Isi / Deskripsi';
            inputIsi.placeholder = 'Tulis deskripsi konten di sini...';
            fotoGroup.classList.remove('hidden');
        }
        document.getElementById('modalTambah').classList.remove('hidden');
    }

    // 2. MODAL EDIT (FIXED DENGAN FETCH DARI SERVER)
    function openEditModal(id) {
        // Debugging: Tampilkan ID yang diklik
        console.log("Mengambil data untuk ID:", id);

        fetch(`/admin/konten/json/${id}`)
            .then(res => res.json())
            .then(data => {
                // Debugging: Lihat data apa yang diterima dari server
                console.log("Data diterima:", data);

                const modalTitle = document.getElementById('modalEditTitle');
                const inputJudul = document.getElementById('editJudul');
                const inputIsi = document.getElementById('editIsi');
                const editFotoGroup = document.getElementById('editFotoGroup');
                const currentFoto = document.getElementById('currentFotoUtama');

                inputJudul.value = data.judul;
                inputIsi.value = data.isi;
                document.getElementById('formEdit').action = `/admin/konten/${id}`;
                
                // AMBIL DARI VARIABEL BARU YANG KITA BUAT DI CONTROLLER
                // Gunakan data.kategori_nama (dari controller) ATAU data.kategori.nama (bawaan relasi)
                let kategoriNama = data.kategori_nama || (data.kategori ? data.kategori.nama : '');
                
                // Bersihkan string
                kategoriNama = kategoriNama.toLowerCase().trim();

                console.log("Nama Kategori Terdeteksi:", kategoriNama); // Cek ini di Console!

                // LOGIKA PENGECEKAN
                if (kategoriNama.includes('tentang sekolah')) {
                    console.log("Mode: TENTANG SEKOLAH (Read-only)");
                    modalTitle.innerText = 'Edit ' + data.judul; 
                    
                    inputJudul.readOnly = true;
                    // Paksa styling lewat JS agar terlihat mati
                    inputJudul.style.backgroundColor = "#f3f4f6"; // Abu-abu
                    inputJudul.style.color = "#6b7280"; // Teks abu tua
                    inputJudul.style.cursor = "not-allowed";
                    
                    editFotoGroup.classList.add('hidden');
                } else {
                    console.log("Mode: UMUM (Editable)");
                    modalTitle.innerText = 'Edit Konten';
                    
                    inputJudul.readOnly = false;
                    // Reset styling
                    inputJudul.style.backgroundColor = "white";
                    inputJudul.style.color = "black";
                    inputJudul.style.cursor = "text";
                    
                    editFotoGroup.classList.remove('hidden');
                    
                    if (data.file_utama_url) {
                        currentFoto.src = data.file_utama_url;
                        currentFoto.classList.remove('hidden');
                    } else {
                        currentFoto.classList.add('hidden');
                    }
                }
            })
            .catch(err => console.error("Error Fetching:", err));

        document.getElementById('modalEdit').classList.remove('hidden');
    }

    // EVENT LISTENER GLOBAL
    window.addEventListener('click', function(e) {
        const dropdownContainer = document.getElementById('customDropdownContainer');
        if (dropdownContainer && !dropdownContainer.contains(e.target)) dropdownContainer.classList.remove('open');
    });

    function closeTambahModal() { document.getElementById('modalTambah').classList.add('hidden'); }
    function closeEditModal() { document.getElementById('modalEdit').classList.add('hidden'); }
    function openMediaModal(id) { 
        document.getElementById('mediaKontenID').value = id; 
        document.getElementById('modalMedia').classList.remove('hidden'); 
    }
    function closeMediaModal() { document.getElementById('modalMedia').classList.add('hidden'); }
    function openEditListModal(id) { alert('Fitur Kelola List akan segera hadir!'); }
    </script>

@endsection