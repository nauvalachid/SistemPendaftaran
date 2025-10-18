@extends('admin.layouts.app') 

@section('title', 'Detail Pendaftaran Siswa')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">

            <div class="px-4 py-5 sm:px-6 bg-blue-700 text-white rounded-t-lg">
                <h3 class="text-lg leading-6 font-medium">
                    Informasi Lengkap Pendaftar: {{ $pendaftaran->nama_siswa }}
                </h3>
                <p class="mt-1 max-w-2xl text-sm opacity-90">
                    Data ini terkait dengan user: {{ $pendaftaran->user->email ?? 'N/A' }}
                </p>
            </div>

            <div class="border-t border-gray-200">
                <dl class="divide-y divide-gray-200">
                    
                    <!-- BARIS 1: ID Pendaftaran dan Nama Siswa -->
                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">ID Pendaftaran</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $pendaftaran->id_pendaftaran }}</dd>
                    </div>
                    <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Nama Lengkap Siswa</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $pendaftaran->nama_siswa }}</dd>
                    </div>
                    
                    <!-- BARIS 2: Data Kontak -->
                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Email User</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $pendaftaran->user->email ?? 'Tidak terhubung dengan akun user' }}</dd>
                    </div>
                    
                    <!-- BARIS 3: Dokumen (Menghubungkan ke Route Download) -->
                    <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Dokumen Terunggah</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            <ul role="list" class="border border-gray-200 rounded-md divide-y divide-gray-200">
                                @php
                                    // Daftar dokumen yang bisa diunduh
                                    $documents = [
                                        'Kartu Keluarga (KK)' => 'kk', 
                                        'Akta Kelahiran' => 'akte', 
                                        'Pas Foto' => 'foto',
                                        'Ijazah / SKL' => 'ijazah_sk',
                                        'Bukti Pembayaran' => 'bukti_bayar'
                                    ];
                                @endphp
                                
                                @foreach ($documents as $label => $field)
                                    <li class="pl-3 pr-4 py-3 flex items-center justify-between text-sm">
                                        <div class="w-0 flex-1 flex items-center">
                                            <svg class="flex-shrink-0 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                <path fill-rule="evenodd" d="M8 4a3 3 0 00-3 3v4a5 5 0 0010 0V7a1 1 0 112 0v4a7 7 0 11-14 0V7a5 5 0 0110 0v.172c-.112-.043-.228-.063-.344-.078A5.004 5.004 0 0012 7V5a3 3 0 00-3-3z" clip-rule="evenodd" />
                                            </svg>
                                            <span class="ml-2 flex-1 w-0 truncate">{{ $label }}</span>
                                        </div>
                                        <div class="ml-4 flex-shrink-0">
                                            @if ($pendaftaran->$field)
                                                <a href="{{ route('admin.pendaftaran.download', ['pendaftaran' => $pendaftaran->id_pendaftaran, 'field' => $field]) }}" 
                                                   class="font-medium text-blue-600 hover:text-blue-500"
                                                   target="_blank">
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
                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Aksi</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            <a href="{{ route('admin.pendaftaran.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Kembali ke Daftar
                            </a>
                            <!-- Tambahkan tombol Aksi (misal: Setujui/Tolak) di sini -->
                        </dd>
                    </div>
                </dl>
            </div>
        </div>
    </div>
</div>
@endsection
