<x-guest-layout>

    {{-- Container Utama --}}
    <div class="container mx-auto mt-10 p-5">

        {{-- Header Status Pendaftaran --}}
        <div class="text-center mb-10">
            <h1 class="text-3xl font-bold text-gray-800">Status Pendaftaran PPDB</h1>
            <p class="text-lg text-gray-600 mt-2">SD Muhammadiyah 2 Ambarketawang - Tahun Pelajaran 2025/2026</p>
        </div>

        {{-- Logika Tampilan Data Pendaftaran --}}
        {{-- Asumsi variabel $pendaftaran sudah berisi data pendaftaran pengguna yang sedang login --}}
        @if(isset($pendaftaran) && $pendaftaran)

            @php
                $status_color = match ($pendaftaran->status ?? 'Pending') {
                    'Diterima' => 'bg-green-100 text-green-700 border-green-400',
                    'Ditolak' => 'bg-red-100 text-red-700 border-red-400',
                    default => 'bg-yellow-100 text-yellow-700 border-yellow-400',
                };
            @endphp

            <div class="max-w-4xl mx-auto mb-10">

                {{-- KOTAK STATUS ATAS --}}
                <div class="inline-block px-3 py-1 font-semibold text-sm rounded-lg border-2 {{ $status_color }} mb-6">
                    Status Anda: {{ $pendaftaran->status ?? 'Pending' }}
                </div>

                {{-- DETAIL DATA PENDAFTARAN --}}
                <div class="bg-white p-8 shadow-2xl rounded-xl border border-gray-100 text-gray-800">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-y-4 gap-x-8">

                        {{-- Kolom Kiri (Label) --}}
                        <div class="font-semibold space-y-3">
                            <p>Nomor Pendaftaran</p> {{-- Tambahkan Nomor Pendaftaran untuk referensi --}}
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
                            <p class="font-bold">: {{ $pendaftaran->id_pendaftaran ?? '—' }}</p> {{-- Asumsi ada field
                            nomor_pendaftaran --}}
                            <p class="font-bold">: {{ $pendaftaran->nama_siswa }}</p>
                            <p>: {{ $pendaftaran->nisn ?? '—' }}</p>
                            <p>: {{ $pendaftaran->created_at ? $pendaftaran->created_at->translatedFormat('l, d F Y') : '—' }}
                            </p>
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
                    <a href="{{ route('pendaftaran.pdf', $pendaftaran->id_pendaftaran) }}"
                        class="inline-flex items-center mt-6 px-4 py-3 bg-blue-800 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">

                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                        </svg>

                        Unduh Bukti Pendaftaran
                    </a>
                @endif
            </div>
        @else
            {{-- TAMPILAN JIKA PENGGUNA SUDAH LOGIN TAPI DATA PENDAFTARANNYA BELUM ADA --}}
            <div
                class="max-w-4xl mx-auto p-10 bg-gray-50 border border-gray-300 rounded-xl text-center text-gray-700 mb-10 shadow-lg">
                <svg class="w-16 h-16 mx-auto mb-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.3 16c-.77 1.333.192 3 1.732 3z">
                    </path>
                </svg>
                <p class="text-xl font-bold text-gray-800">Data Pendaftaran Tidak Ditemukan</p>
                <p class="text-gray-600 mt-2">Anda belum melakukan pendaftaran atau data pendaftaran Anda belum terhubung
                    dengan akun Anda saat ini.</p>
                {{-- Anda bisa menambahkan tombol untuk mengarahkan ke halaman pendaftaran di sini jika perlu --}}
                <a href="{{ route('pendaftaran.create') }}"
                    class="mt-4 inline-block bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-6 rounded-lg transition duration-150">Isi
                    Formulir Pendaftaran</a>
            </div>
        @endif

    </div>
</x-guest-layout>