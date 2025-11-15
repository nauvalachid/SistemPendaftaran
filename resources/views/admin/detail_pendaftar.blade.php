@extends('admin.layouts.app')
{{-- Menggunakan layout utama yang mencakup body tag, dll. --}}

@section('title', 'Detail Pendaftaran Siswa')

@section('content')
    <div class="flex min-h-screen bg-gray-50">
        <!-- Sidebar component -->
        <x-sidebar />

        <main class="w-full overflow-y-auto p-8 lg:p-12">
            <div class="max-w-7xl mx-auto">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">

                    <!-- HEADER UTAMA -->
                    <div class="px-4 py-5 sm:px-6 bg-blue-700 text-white rounded-t-lg">
                        <h3 class="text-2xl leading-6 font-medium">
                            Informasi Lengkap Pendaftar: {{ $pendaftaran->nama_siswa }}
                        </h3>
                        <p class="mt-1 max-w-2xl text-sm opacity-90">
                            Data ini terkait dengan user: {{ $pendaftaran->user->email ?? 'N/A' }}
                        </p>
                    </div>

                    <!-- START: BAGIAN FOTO & DETAIL UTAMA -->
                    <div class="p-6 sm:p-8 flex flex-col md:flex-row gap-6 md:gap-8 bg-white border-t border-gray-200">

                        <!-- 1. Pas Foto (Kiri Atas) -->
                        <div class="flex-shrink-0">
                            <h4 class="text-sm font-medium text-gray-500 mb-2">Pas Foto Pendaftar</h4>
                            {{-- Tampilkan pas foto langsung. Gunakan path storage Laravel --}}
                            <img src="{{ asset('storage/' . $pendaftaran->foto) }}" alt="Pas Foto Siswa"
                                class="w-[250px] h-[250px] object-cover rounded-lg shadow-lg border-2 border-indigo-300 transform transition duration-300 hover:scale-105"
                                onerror="this.onerror=null; this.src='https://placehold.co/250x250/ef4444/ffffff?text=FOTO+KOSONG';">
                        </div>

                        <!-- 2. Detail Kunci (Di Sebelah Foto) -->
                        <div class="flex-grow">
                            <h4 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2">Detail Pendaftaran Kunci</h4>
                            <dl class="divide-y divide-gray-100 border rounded-lg">
                                <div class="bg-gray-50 px-3 py-3 sm:grid sm:grid-cols-3 sm:gap-4">
                                    <dt class="text-sm font-medium text-gray-500">ID Pendaftaran</dt>
                                    <dd class="mt-1 text-sm font-bold text-gray-900 sm:mt-0 sm:col-span-2">
                                        {{ $pendaftaran->id_pendaftaran }}</dd>
                                </div>
                                <div class="bg-white px-3 py-3 sm:grid sm:grid-cols-3 sm:gap-4">
                                    <dt class="text-sm font-medium text-gray-500">Tanggal Pendaftaran</dt>
                                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                        {{ \Carbon\Carbon::parse($pendaftaran->created_at)->format('d F Y, H:i') }}
                                    </dd>
                                </div>
                                <div class="bg-gray-50 px-3 py-3 sm:grid sm:grid-cols-3 sm:gap-4">
                                    <dt class="text-sm font-medium text-gray-500">Nama Lengkap Siswa</dt>
                                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2 font-semibold">
                                        {{ $pendaftaran->nama_siswa }}</dd>
                                </div>
                                <div class="bg-gray-50 px-3 py-3 sm:grid sm:grid-cols-3 sm:gap-4">
                                    <dt class="text-sm font-medium text-gray-500">Status</dt>
                                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2 font-semibold">
                                        {{ $pendaftaran->status }}</dd>
                                </div>
                            </dl>
                        </div>
                    </div>
                    <!-- END: BAGIAN FOTO & DETAIL UTAMA -->

                    <!-- START: DETAIL LANJUTAN (Menggantikan data siswa awal) -->
                    <div class="border-t border-gray-200">
                        <dl class="divide-y divide-gray-200">

                            <!-- === LANJUTAN DATA SISWA === -->
                            <div class="bg-gray-100 px-4 py-3 sm:px-6">
                                <h4 class="text-md font-bold text-gray-700">DATA PRIBADI SISWA</h4>
                            </div>
                            <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Asal Sekolah</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                    {{ $pendaftaran->asal_sekolah ?? '-' }}</dd>
                            </div>
                            <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Tempat, Tanggal Lahir</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                    {{ $pendaftaran->tempat_tgl_lahir ?? 'N/A' }}
                                </dd>
                            </div>
                            <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Alamat Lengkap</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                    {{ $pendaftaran->alamat ?? '-' }}</dd>
                            </div>

                            <!-- Tambahkan Pembatas Visual untuk Data Orang Tua -->
                            <div class="bg-blue-100 px-4 py-3 sm:px-6">
                                <h4 class="text-md font-bold text-blue-800">DATA ORANG TUA / WALI</h4>
                            </div>

                            <!-- === BAGIAN DATA ORANG TUA === -->
                            <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Nama Ayah</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                    {{ $pendaftaran->nama_ayah ?? '-' }}</dd>
                            </div>
                            <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Pekerjaan Ayah</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                    {{ $pendaftaran->pekerjaan_ayah ?? '-' }}</dd>
                            </div>
                            <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Nama Ibu</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                    {{ $pendaftaran->nama_ibu ?? '-' }}</dd>
                            </div>
                            <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Pekerjaan Ibu</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                    {{ $pendaftaran->pekerjaan_ibu ?? '-' }}</dd>
                            </div>
                            <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Nomor Telepon Orang Tua</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                    {{ $pendaftaran->no_telp ?? '-' }}</dd>
                            </div>

                            <!-- BARIS KONTAK USER -->
                            <div class="bg-gray-100 px-4 py-3 sm:px-6">
                                <h4 class="text-md font-bold text-gray-700">KONTAK & AKUN</h4>
                            </div>
                            <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Email User Akun</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                    {{ $pendaftaran->user->email ?? 'Tidak terhubung dengan akun user' }}</dd>
                            </div>

                            <!-- BARIS DOKUMEN -->
                            <div class="bg-gray-100 px-4 py-3 sm:px-6">
                                <h4 class="text-md font-bold text-gray-700">DOKUMEN PENDUKUNG</h4>
                            </div>
                            <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Dokumen Lain Terunggah</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                    <ul role="list" class="border border-gray-200 rounded-md divide-y divide-gray-200">
                                        @php
                                            // Daftar dokumen yang bisa diunduh (Pas Foto dihapus dari sini)
                                            $documents = [
                                                'Kartu Keluarga (KK)' => 'kk',
                                                'Akta Kelahiran' => 'akte',
                                                'Ijazah / SKL' => 'ijazah_sk',
                                                'Bukti Pembayaran' => 'bukti_bayar'
                                            ];
                                        @endphp

                                        @foreach ($documents as $label => $field)
                                            <li class="pl-3 pr-4 py-3 flex items-center justify-between text-sm">
                                                <div class="w-0 flex-1 flex items-center">
                                                    {{-- Icon Kertas/Dokumen --}}
                                                    <svg class="flex-shrink-0 h-5 w-5 text-gray-400"
                                                        xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                                        fill="currentColor" aria-hidden="true">
                                                        <path fill-rule="evenodd"
                                                            d="M8 4a3 3 0 00-3 3v4a5 5 0 0010 0V7a1 1 0 112 0v4a7 7 0 11-14 0V7a5 5 0 0110 0v.172c-.112-.043-.228-.063-.344-.078A5.004 5.004 0 0012 7V5a3 3 0 00-3-3z"
                                                            clip-rule="evenodd" />
                                                    </svg>
                                                    <span class="ml-2 flex-1 w-0 truncate">{{ $label }}</span>
                                                </div>
                                                <div class="ml-4 flex-shrink-0 flex space-x-2">
                                                    @if ($pendaftaran->$field)
                                                        <!-- Link untuk Melihat (View) -->
                                                        <a href="javascript:void(0)"
                                                            data-url="{{ route('admin.pendaftaran.download', ['pendaftaran' => $pendaftaran->id_pendaftaran, 'field' => $field, 'action' => 'view']) }}"
                                                            onclick="openDocumentModal(this.dataset.url)"
                                                            class="font-medium text-green-600 hover:text-green-500 cursor-pointer"
                                                            title="Lihat dokumen dalam pop-up">
                                                            Lihat
                                                        </a>
                                                        <span class="text-gray-300">|</span>
                                                        <!-- Link untuk Mengunduh (Download) -->
                                                        <a href="{{ route('admin.pendaftaran.download', ['pendaftaran' => $pendaftaran->id_pendaftaran, 'field' => $field, 'action' => 'download']) }}"
                                                            class="font-medium text-blue-600 hover:text-blue-500"
                                                            title="Unduh dokumen ke perangkat">
                                                            Unduh
                                                        </a>
                                                    @else
                                                        <span class="text-red-500">Belum Ada</span>
                                                    @endif
                                                </div>
                                            </li>
                                        @endforeach
                                    </ul>
                                </dd>
                            </div>

                            <!-- BARIS TERAKHIR: Aksi -->
                            <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Aksi</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2 flex space-x-3">
                                    <a href="{{ route('admin.pendaftaran.index') }}"
                                        class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                        </svg>
                                        Kembali ke Daftar
                                    </a>

                                    <!-- Tombol Setujui -->
                                    <button id="btnSetujui" data-id="{{ $pendaftaran->id_pendaftaran }}" type="button"
                                        class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        Setujui Pendaftaran
                                    </button>

                                    <!-- Tombol Tolak -->
                                    <button id="btnTolak" data-id="{{ $pendaftaran->id_pendaftaran }}" type="button"
                                        class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                        Tolak Pendaftaran
                                    </button>
                                </dd>
                            </div>
                        </dl>
                    </div>
                    {{-- END: DETAIL LANJUTAN --}}

                </div>
            </div>
        </main>
    </div>

    <!-- Modal Structure (Pop-up Pratinjau Dokumen) -->
    <div id="documentModal"
        class="fixed inset-0 z-50 hidden items-center justify-center bg-gray-900 bg-opacity-75 transition-opacity duration-300">
        <!-- Modal Content -->
        <div
            class="bg-white rounded-lg shadow-2xl w-11/12 h-5/6 max-w-5xl flex flex-col transform transition-all scale-100 ease-out duration-300">
            <!-- Header -->
            <div class="p-4 border-b flex justify-between items-center bg-gray-50">
                <h4 class="text-lg font-semibold text-gray-800">Pratinjau Dokumen Pendaftar</h4>
                <button onclick="closeDocumentModal()"
                    class="text-gray-500 hover:text-gray-700 focus:outline-none p-1 rounded-full hover:bg-gray-200">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <!-- Iframe Content Container -->
            <div class="flex-1 p-2">
                <!-- Iframe akan memuat dokumen (PDF, gambar, dll.) -->
                <iframe id="documentFrame" src="" frameborder="0"
                    class="w-full h-full rounded-md bg-white border border-gray-200"></iframe>
            </div>
            <!-- Footer -->
            <div class="p-4 border-t text-right bg-gray-50">
                <button onclick="closeDocumentModal()"
                    class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">Tutup
                    Pratinjau</button>
            </div>
        </div>
    </div>

    <script>
        /**
         * Membuka modal dokumen dan memuat URL dokumen ke iframe.
         * @param {string} url URL dokumen yang akan dimuat (dari route download?action=view)
         */
        function openDocumentModal(url) {
            const modal = document.getElementById('documentModal');
            const iframe = document.getElementById('documentFrame');

            // Atur URL dokumen ke iframe
            iframe.src = url;

            // Tampilkan modal
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        /**
         * Menutup modal dokumen dan mereset iframe src.
         */
        function closeDocumentModal() {
            const modal = document.getElementById('documentModal');
            const iframe = document.getElementById('documentFrame');

            // Sembunyikan modal
            modal.classList.add('hidden');
            modal.classList.remove('flex');

            // Hapus src dari iframe untuk menghentikan pemuatan
            iframe.src = '';
        }
    </script>

@endsection