@extends('admin.layouts.app')

@section('title', 'Kelola Konten')

@section('content')
<div class="flex min-h-screen bg-gray-50">
    {{-- Pastikan Anda memiliki X-sidebar component --}}
    <x-sidebar /> 

    <main class="w-full overflow-y-auto p-8 lg:p-12">

        <h1 class="text-3xl font-bold text-black">Kelola Konten</h1>
        <p class="mt-2 text-black">Kelola seluruh konten halaman website sekolah.</p>

        <hr class="my-5 h-px border-0 bg-black">

        {{-- ALERT SUCCESS --}}
        @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
        @endif
        
        {{-- ALERT VALIDATION ERRORS --}}
        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <strong class="font-bold">Gagal!</strong>
                <span class="block sm:inline">Periksa kembali data yang Anda masukkan.</span>
                <ul class="mt-2 list-disc list-inside text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- LOOP KATEGORI --}}
        @foreach($kategori as $kat)
        <div class="rounded-2xl bg-white p-6 shadow-md mb-6">

            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-bold text-black">
                    {{ $kat->nama }}
                </h2>

                {{-- KONDISI UNTUK MENYEMBUNYIKAN TOMBOL TAMBAH --}}
                @if ($kat->nama !== 'Beranda' && $kat->nama !== 'Tentang Sekolah')
                <button 
                    class="text-sm bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg"
                    onclick="openTambahModal({{ $kat->id }}, '{{ $kat->nama }}')">
                    + Tambah
                </button>
                @endif
            </div>

            
            {{-- KONDISI 2: MEMBEDAKAN TAMPILAN KONTEN --}}

            {{-- ----------------------------------------------------- --}}
            {{-- START: TAMPILAN KHUSUS UNTUK KONTEN TUNGGAL (Sederhana) --}}
            {{-- ----------------------------------------------------- --}}
            @if ($kat->nama === 'Halaman Tentang Sekolah')
                @foreach($kat->konten as $konten)
                <div class="border border-gray-300 rounded-xl p-4 mb-4">
                    <div class="flex justify-between items-start">
                        <div>
                            {{-- Judul (Sejarah, Visi, Misi) --}}
                            <h3 class="text-xl font-bold text-black">{{ $konten->judul }}</h3> 
                            {{-- Isi --}}
                            <p class="mt-1 text-black text-base leading-relaxed">{{ $konten->isi }}</p>
                        </div>

                        {{-- Hanya tombol Edit --}}
                        <div class="flex gap-2">
                            <button 
                                class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded-lg text-sm"
                                onclick="openEditModal({{ $konten->id }})">
                                Edit
                            </button>
                        </div>
                    </div>
                    {{-- Di sini TIDAK ADA Media Tambahan/List --}}
                </div>
                @endforeach
            
            {{-- ------------------------------------------------------ --}}
            {{-- END: TAMPILAN KHUSUS --}}
            {{-- START: TAMPILAN DEFAULT UNTUK KONTEN LENGKAP (Kompleks) --}}
            {{-- ------------------------------------------------------ --}}
            @else

                {{-- Daftar konten (Tampilan Lama/Lengkap) --}}
                @foreach($kat->konten as $konten)
                <div class="border border-gray-300 rounded-xl p-4 mb-4">

                    {{-- HEADER & FOTO UTAMA --}}
                    <div class="flex justify-between items-start">
                        <div>
                            <h3 class="text-lg font-bold text-black">{{ $konten->judul }}</h3>
                            
                            {{-- Tampilkan foto utama --}}
                            @php
                                $foto_utama = $konten->media->where('urutan', 0)->first();
                            @endphp
                            @if ($foto_utama)
                            <img src="{{ asset('storage/' . $foto_utama->file_path) }}" alt="Foto Utama" class="h-16 w-16 object-cover rounded-md mt-2">
                            @endif
                        </div>

                        {{-- Tombol Edit & Hapus --}}
                        <div class="flex gap-2">
                            <button 
                                class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded-lg text-sm"
                                onclick="openEditModal({{ $konten->id }})">
                                Edit
                            </button>

                            {{-- Hanya tampilkan tombol Hapus jika bukan kategori tunggal (Beranda/Tentang Sekolah) --}}
                            @if ($kat->nama !== 'Beranda' && $kat->nama !== 'Tentang Sekolah')
                            <form action="{{ route('admin.konten.destroy', $konten->id) }}" method="POST">
                                @csrf @method('DELETE')
                                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded-lg text-sm"
                                    onclick="return confirm('Apakah Anda yakin ingin menghapus konten ini?')">
                                    Hapus
                                </button>
                            </form>
                            @endif
                        </div>
                    </div>

                    {{-- ISI --}}
                    <p class="mt-2 text-black text-sm leading-relaxed">
                        {{ $konten->isi }}
                    </p>

                    {{-- MEDIA TAMBAHAN --}}
                    <div class="grid grid-cols-4 gap-3 mt-4">
                        {{-- Loop media dengan urutan > 0 --}}
                        @foreach($konten->media->where('urutan', '>', 0) as $m)
                        <div class="border rounded-lg overflow-hidden relative">
                            {{-- Tombol Hapus Media Tambahan (Asumsi route admin.konten_media.destroy ada) --}}
                            <form action="{{ route('admin.konten_media.destroy', $m->id) }}" method="POST" class="absolute top-1 right-1">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-white bg-red-600 hover:bg-red-700 rounded-full p-1 leading-none text-xs"
                                    onclick="return confirm('Hapus media ini?')">
                                    &times;
                                </button>
                            </form>

                            <img 
                                src="{{ asset('storage/' . $m->file_path) }}" 
                                class="w-full h-28 object-cover">
                        </div>
                        @endforeach

                        {{-- KONDISI UNTUK MENGHILANGKAN KOTAK '+' DI BERANDA DAN TENTANG SEKOLAH --}}
                        @if ($kat->nama !== 'Beranda' && $kat->nama !== 'Tentang Sekolah')
                        <button 
                            onclick="openMediaModal({{ $konten->id }})"
                            class="border border-gray-300 rounded-lg flex items-center justify-center text-3xl text-gray-400 hover:bg-gray-100 h-28">
                            +
                        </button>
                        @endif
                    </div>

                    {{-- LIST --}}
                    @if($konten->list->count())
                    <ul class="mt-3 text-sm text-black list-disc pl-5">
                        @foreach($konten->list as $li)
                        <li>{{ $li->item }}</li> 
                        @endforeach
                    </ul>
                    @endif
                    
                    <div class="mt-3 text-right">
                        <button class="text-xs text-blue-600 hover:underline" onclick="openEditListModal({{ $konten->id }})">Kelola List</button>
                    </div>

                </div>
                @endforeach
            
            @endif
            {{-- END: TAMPILAN DEFAULT --}}

        </div>
        @endforeach

    </main>
</div>

{{-- MODAL TAMBAH KONTEN --}}
<div id="modalTambah" class="hidden fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50">
    <div class="bg-white w-96 rounded-xl p-6 shadow-lg">
        <h2 class="text-xl font-bold mb-4">Tambah Konten</h2>

        <form action="{{ route('admin.konten.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="kategori_konten_id" id="tambahKategoriID">

            <label class="text-black font-semibold text-sm">Judul</label>
            <input type="text" name="judul" class="w-full border p-2 rounded-lg mb-3" required>

            <label class="text-black font-semibold text-sm">Isi</label>
            <textarea name="isi" rows="4" class="w-full border p-2 rounded-lg mb-3" required></textarea>

            {{-- FOTO UTAMA DIBUNGKUS DALAM DIV DENGAN ID (Diatur visibility-nya oleh JS) --}}
            <div id="tambahFotoGroup">
                <label class="text-black font-semibold text-sm">Foto Utama (Opsional)</label>
                <input type="file" name="file_utama" accept="image/*" class="w-full border p-2 rounded-lg mb-4">
            </div>

            <div class="flex justify-end gap-2">
                <button type="button" onclick="closeTambahModal()" class="px-4 py-2 bg-gray-300 rounded-lg">
                    Batal
                </button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>

{{-- MODAL EDIT KONTEN --}}
<div id="modalEdit" class="hidden fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50">
    <div class="bg-white w-96 rounded-xl p-6 shadow-lg">
        <h2 class="text-xl font-bold mb-4">Edit Konten</h2>

        <form id="formEdit" method="POST" enctype="multipart/form-data">
            @csrf @method('PUT')

            <label class="text-black font-semibold text-sm">Judul</label>
            <input type="text" id="editJudul" name="judul" class="w-full border p-2 rounded-lg mb-3" required>

            <label class="text-black font-semibold text-sm">Isi</label>
            <textarea id="editIsi" name="isi" rows="4" class="w-full border p-2 rounded-lg mb-3" required></textarea>
            
            {{-- FOTO UTAMA DI MODAL EDIT (Diatur visibility-nya oleh JS) --}}
            <div id="editFotoGroup">
                <p class="text-black font-semibold text-sm mt-3">Foto Utama Saat Ini:</p>
                <img id="currentFotoUtama" src="" alt="Foto Utama" class="w-full h-32 object-cover rounded-lg mb-3 border">
                
                <label class="text-black font-semibold text-sm">Ganti Foto Utama (Opsional)</label>
                <input type="file" name="file_utama" accept="image/*" class="w-full border p-2 rounded-lg mb-4">
            </div>

            <div class="flex justify-end gap-2">
                <button type="button" onclick="closeEditModal()" class="px-4 py-2 bg-gray-300 rounded-lg">
                    Batal
                </button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg">
                    Update
                </button>
            </div>
        </form>
    </div>
</div>

{{-- MODAL TAMBAH MEDIA TAMBAHAN --}}
<div id="modalMedia" class="hidden fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50">
    <div class="bg-white w-96 rounded-xl p-6 shadow-lg">
        <h2 class="text-xl font-bold mb-4">Tambah Media Tambahan</h2>
        
        <form action="{{ route('admin.konten_media.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="konten_id" id="mediaKontenID">

            <label class="text-black font-semibold text-sm">File</label>
            <input type="file" name="file_path" accept="image/*,video/*" class="w-full border p-2 rounded-lg mb-3" required>

            <label class="text-black font-semibold text-sm">Urutan</label>
            <input type="number" name="urutan" value="100" class="w-full border p-2 rounded-lg mb-4"> 

            <div class="flex justify-end gap-2">
                <button type="button" onclick="closeMediaModal()" class="px-4 py-2 bg-gray-300 rounded-lg">
                    Batal
                </button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openTambahModal(id, kategoriNama) {
    document.getElementById('tambahKategoriID').value = id;
    
    const fotoGroup = document.getElementById('tambahFotoGroup');

    // Cek jika kategori adalah 'Halaman Tentang Sekolah' atau 'Tentang Sekolah'
    if (kategoriNama === 'Halaman Tentang Sekolah' || kategoriNama === 'Tentang Sekolah') {
        fotoGroup.classList.add('hidden');
    } else {
        // Pastikan ditampilkan untuk kategori lain
        fotoGroup.classList.remove('hidden');
    }

    document.getElementById('modalTambah').classList.remove('hidden');
}

function closeTambahModal() {
    document.getElementById('modalTambah').classList.add('hidden');
}

function openMediaModal(id) {
    document.getElementById('mediaKontenID').value = id;
    document.getElementById('modalMedia').classList.remove('hidden');
}

function closeMediaModal() {
    document.getElementById('modalMedia').classList.add('hidden');
}

function openEditModal(id) {
    // Sesuaikan URL ini dengan rute JSON (pastikan mengembalikan data.kategori.nama)
    fetch(`/admin/konten/json/${id}`) 
        .then(res => res.json())
        .then(data => {
            document.getElementById('editJudul').value = data.judul;
            document.getElementById('editIsi').value = data.isi;
            document.getElementById('formEdit').action = `/admin/konten/${id}`;
            
            const kategoriNama = data.kategori.nama; 
            const editFotoGroup = document.getElementById('editFotoGroup');
            const fotoUtamaElement = document.getElementById('currentFotoUtama');

            // Logika untuk Menyembunyikan Foto di Modal Edit
            if (kategoriNama === 'Halaman Tentang Sekolah' || kategoriNama === 'Tentang Sekolah') {
                editFotoGroup.classList.add('hidden');
            } else {
                // Tampilkan semua elemen terkait foto untuk konten normal
                editFotoGroup.classList.remove('hidden');
                
                // Isi src foto utama jika ada
                if (data.file_utama_url) {
                    fotoUtamaElement.src = data.file_utama_url;
                } else {
                    fotoUtamaElement.src = '[https://via.placeholder.com/350x150?text=Tidak+Ada+Foto+Utama](https://via.placeholder.com/350x150?text=Tidak+Ada+Foto+Utama)'; 
                }
            }
        });

    document.getElementById('modalEdit').classList.remove('hidden');
}

function closeEditModal() {
    document.getElementById('modalEdit').classList.add('hidden');
}

function openEditListModal(id) {
    alert('Fungsi Kelola List belum diimplementasikan.');
}
</script>

@endsection