<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detail Data Pendaftaran Anda') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6 md:p-10 max-w-4xl mx-auto">

                <h1 class="text-2xl font-bold text-indigo-700 mb-6 border-b pb-2">Data Pendaftaran yang Telah Anda Kirim</h1>
                <p class="text-gray-600 mb-8">Berikut adalah detail lengkap pendaftaran yang Anda kirim pada tanggal **{{ $pendaftaran->created_at->format('d M Y H:i') }}**.</p>

                <!-- 1. Data Calon Siswa -->
                <div class="mb-8">
                    <h3 class="text-lg font-semibold text-indigo-600 mb-4 border-l-4 border-indigo-500 pl-3">1. Data Calon Siswa</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4 text-gray-700">
                        <!-- Menggunakan komponen detail-item -->
                        <x-detail-item label="Nama Lengkap Siswa" value="{{ $pendaftaran->nama_siswa }}" />
                        <x-detail-item label="Tempat, Tanggal Lahir" value="{{ $pendaftaran->tempat_tgl_lahir }}" />
                        <x-detail-item label="Jenis Kelamin" value="{{ $pendaftaran->jenis_kelamin }}" />
                        <x-detail-item label="Agama" value="{{ $pendaftaran->agama }}" />
                        <x-detail-item label="Asal Sekolah" value="{{ $pendaftaran->asal_sekolah }}" />
                        <x-detail-item label="Alamat Lengkap" value="{{ $pendaftaran->alamat }}" class="md:col-span-2" />
                    </div>
                </div>

                <!-- 2. Data Orang Tua / Wali -->
                <div class="mb-8">
                    <h3 class="text-lg font-semibold text-indigo-600 mb-4 border-l-4 border-indigo-500 pl-3">2. Data Orang Tua / Wali</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4 text-gray-700">
                        <x-detail-item label="Nama Ayah" value="{{ $pendaftaran->nama_ayah ?? '-' }}" />
                        <x-detail-item label="Nama Ibu" value="{{ $pendaftaran->nama_ibu ?? '-' }}" />
                        <x-detail-item label="Pendidikan Terakhir Ayah" value="{{ $pendaftaran->pendidikan_terakhir_ayah ?? '-' }}" />
                        <x-detail-item label="Pendidikan Terakhir Ibu" value="{{ $pendaftaran->pendidikan_terakhir_ibu ?? '-' }}" />
                        <x-detail-item label="Pekerjaan Ayah" value="{{ $pendaftaran->pekerjaan_ayah ?? '-' }}" />
                        <x-detail-item label="Pekerjaan Ibu" value="{{ $pendaftaran->pekerjaan_ibu ?? '-' }}" />
                    </div>
                </div>

                <!-- 3. Dokumen Pendukung -->
                <div class="mb-8">
                    <h3 class="text-lg font-semibold text-indigo-600 mb-4 border-l-4 border-indigo-500 pl-3">3. Dokumen Pendukung</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4 text-gray-700">
                        @include('pendaftaran.partials.document-list', ['pendaftaran' => $pendaftaran])
                    </div>
                </div>
                
                <div class="flex justify-end pt-4 border-t">
                     <a href="{{ route('dashboard') }}" class="px-6 py-3 bg-gray-500 text-white font-semibold rounded-lg shadow-md hover:bg-gray-600 focus:outline-none focus:ring-4 focus:ring-gray-500 focus:ring-opacity-50 transition duration-150 ease-in-out">
                        Kembali ke Dashboard
                    </a>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
