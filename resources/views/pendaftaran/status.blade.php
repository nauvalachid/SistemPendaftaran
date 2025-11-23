<x-guest-layout>
    
    {{-- Container Utama --}}
    <div class="container mx-auto mt-10 p-5">
        
        {{-- Header Status Pendaftaran --}}
        <div class="text-center mb-10">
            <h1 class="text-3xl font-bold text-gray-800">Status Pendaftaran PPDB</h1>
            <p class="text-lg text-gray-600 mt-2">SD Muhammadiyah 2 Ambarketawang - Tahun Pelajaran 2025/2026</p>
        </div>

        {{-- Bagian Pencarian Status (Diperlebar menjadi max-w-4xl) --}}
        <div class="max-w-4xl mx-auto mb-12">
            <form action="{{ route('pendaftaran.index') }}" method="GET" class="flex shadow-lg rounded-lg">
                <div class="relative flex-grow">
                    <svg class="absolute left-4 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    <input 
                        type="search" 
                        name="search" 
                        placeholder="Masukkan nama lengkap" 
                        class="pl-12 pr-4 w-full p-4 border border-gray-300 rounded-l-lg focus:ring-blue-500 focus:border-blue-500"
                        value="{{ request('search') }}"
                    >
                </div>
                <button 
                    type="submit" 
                    class="bg-blue-800 text-white font-semibold py-4 px-6 rounded-r-lg hover:bg-blue-900 transition duration-150"
                >
                    Cek Status
                </button>
            </form>
            <p class="text-center text-gray-500 text-sm mt-4">Silakan masukkan nama lengkap siswa pada form di atas untuk melihat status pendaftaran Anda.</p>
        </div>

        {{-- Bagian Hasil Pencarian (Diperlebar menjadi max-w-4xl) --}}
        @if(isset($pendaftaran_search) && $pendaftaran_search)
            
            @php
                $pendaftaran = $pendaftaran_search;
                $status_color = match($pendaftaran->status ?? 'Pending') {
                    'Diterima' => 'bg-green-100 text-green-700 border-green-400',
                    'Ditolak' => 'bg-red-100 text-red-700 border-red-400',
                    default => 'bg-yellow-100 text-yellow-700 border-yellow-400',
                };
            @endphp

            <div class="max-w-4xl mx-auto mb-10">
                
                {{-- KOTAK STATUS ATAS --}}
                <div class="inline-block px-3 py-1 font-semibold text-sm rounded-lg border-2 {{ $status_color }} mb-6">
                    {{ $pendaftaran->status ?? 'Pending' }}
                </div>

                {{-- DETAIL DATA PENDAFTARAN --}}
                <div class="bg-white p-8 shadow-2xl rounded-xl border border-gray-100 text-gray-800">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-y-4 gap-x-8">
                        
                        {{-- Kolom Kiri (Label) --}}
                        <div class="font-semibold space-y-3">
                            <p>Nama Lengkap</p>
                            <p>NISN</p>
                            <p>Tanggal Daftar</p>
                            <p>Asal Sekolah</p>
                            <p>Tempat, Tanggal Lahir</p>
                            <p>Jenis Kelamin</p>
                            <p>Agama</p>
                            <p>Alamat</p>
                            <p>Nama Ayah</p>
                            <p>Pendidikan Terakhir Ayah</p>
                            <p>Pekerjaan Ayah</p>
                            <p>Nama Ibu</p>
                            <p>Pendidikan Terakhir Ibu</p>
                            <p>Pekerjaan Ibu</p>
                            <p>Nomor Telepon</p>
                        </div>

                        {{-- Kolom Kanan (Nilai) --}}
                        <div class="space-y-3">
                            <p class="font-bold">: {{ $pendaftaran->nama_siswa }}</p>
                            <p>: {{ $pendaftaran->nisn ?? '—' }}</p>
                            <p>: {{ $pendaftaran->created_at ? $pendaftaran->created_at->translatedFormat('l, d F Y') : '—' }}</p>
                            <p>: {{ $pendaftaran->asal_sekolah }}</p>
                            <p>: {{ $pendaftaran->tempat_tgl_lahir }}</p>
                            <p>: {{ $pendaftaran->jenis_kelamin }}</p>
                            <p>: {{ $pendaftaran->agama }}</p>
                            <p>: {{ $pendaftaran->alamat }}</p>
                            <p>: {{ $pendaftaran->nama_ayah ?? '—' }}</p>
                            <p>: {{ $pendaftaran->pendidikan_terakhir_ayah ?? '—' }}</p>
                            <p>: {{ $pendaftaran->pekerjaan_ayah ?? '—' }}</p>
                            <p>: {{ $pendaftaran->nama_ibu ?? '—' }}</p>
                            <p>: {{ $pendaftaran->pendidikan_terakhir_ibu ?? '—' }}</p>
                            <p>: {{ $pendaftaran->pekerjaan_ibu ?? '—' }}</p>
                            <p>: {{ $pendaftaran->no_telp ?? '—' }}</p>
                        </div>
                    </div>
                </div>

                {{-- Tombol Unduh Bukti --}}
                @if(($pendaftaran->status ?? 'Pending') == 'Diterima')
                    <a href="#" class="inline-flex items-center mt-6 px-4 py-3 bg-blue-800 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                        Unduh Bukti Pendaftaran
                    </a>
                @endif
            </div>
        @elseif(request('search'))
            {{-- TAMPILAN JIKA PENCARIAN DILAKUKAN TAPI HASILNYA KOSONG --}}
             <div class="max-w-4xl mx-auto p-8 bg-red-50 border border-red-300 rounded-xl text-center text-red-700 mb-10">
                <p class="font-bold">Data pendaftaran untuk nama "**{{ request('search') }}**" tidak ditemukan.</p>
                <p class="text-sm mt-1">Pastikan nama sudah dieja dengan benar.</p>
            </div>
        @endif


        <hr class="mb-10">

        {{-- Bagian Cek Status dan Unduh Bukti (Cards) --}}
        @if(!isset($pendaftaran_search) || !$pendaftaran_search)
            <div class="max-w-4xl mx-auto mt-8 grid grid-cols-1 md:grid-cols-2 gap-8">
                {{-- Card Cek Status --}}
                <div class="bg-white p-10 rounded-xl shadow-lg border border-gray-100 text-center flex flex-col items-center">
                    <div class="bg-blue-800 p-4 rounded-full mb-4">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold mb-2 text-gray-800">Cek Status</h3>
                    <p class="text-gray-600">Masukkan nama lengkap untuk melihat status pendaftaran terkini</p>
                </div>

                {{-- Card Unduh Bukti --}}
                <div class="bg-white p-10 rounded-xl shadow-lg border border-gray-100 text-center flex flex-col items-center">
                    <div class="bg-blue-800 p-4 rounded-full mb-4">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold mb-2 text-gray-800">Unduh Bukti</h3>
                    <p class="text-gray-600">Unduh bukti pendaftaran setelah status tersedia</p>
                </div>
            </div>
        @endif

    </div>
</x-guest-layout>