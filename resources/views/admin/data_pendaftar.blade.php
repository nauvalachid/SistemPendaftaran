@extends('admin.layouts.app') 

@section('title', 'Daftar Pendaftaran Siswa')

@section('content')
<div class="flex min-h-screen bg-gray-50">

    <x-sidebar />

    <main class="w-full overflow-y-auto p-8 lg:p-12">
        <div class="max-w-7xl mx-auto">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6 border border-gray-100">
                <h2 class="text-3xl font-bold mb-6 text-gray-800">Manajemen Pendaftaran Siswa</h2>

                {{-- Pesan Sukses/Error --}}
                @if (session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6" role="alert">
                        <span class="block sm:inline">{{ session('success') }}</span>
                    </div>
                @endif

                {{-- --- Form Pencarian dan Filter --- --}}
                <form action="{{ route('admin.pendaftaran.index') }}" method="GET" class="mb-8 p-4 bg-gray-50 rounded-lg shadow-inner border border-gray-200">
                    <div class="grid grid-cols-1 md:grid-cols-5 gap-4 items-end">
                        
                        {{-- 1. Kolom Pencarian Global --}}
                        <div class="md:col-span-2">
                            <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Cari (Nama/NISN/Sekolah)</label>
                            <input type="text" name="search" id="search" 
                                class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-2" 
                                placeholder="Masukkan nama, NISN, atau sekolah..."
                                value="{{ request('search') }}">
                        </div>

                        {{-- 2. Filter Status --}}
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                            <select name="status" id="status" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-2">
                                <option value="all">Semua Status</option>
                                {{-- $list_status datang dari Controller --}}
                                @foreach ($list_status as $status)
                                    <option value="{{ $status }}" {{ request('status') === $status ? 'selected' : '' }}>
                                        {{ $status }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- 3. Filter Asal Sekolah --}}
                        <div>
                            <label for="asal_sekolah" class="block text-sm font-medium text-gray-700 mb-1">Asal Sekolah</label>
                            <select name="asal_sekolah" id="asal_sekolah" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-2">
                                <option value="all">Semua Sekolah</option>
                                {{-- $list_sekolah datang dari Controller --}}
                                @foreach ($list_sekolah as $sekolah)
                                    <option value="{{ $sekolah }}" {{ request('asal_sekolah') === $sekolah ? 'selected' : '' }}>
                                        {{ $sekolah }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        {{-- 4. Filter Tanggal Daftar --}}
                        <div>
                            <label for="tanggal_daftar" class="block text-sm font-medium text-gray-700 mb-1">Tgl. Daftar</label>
                            <input type="date" name="tanggal_daftar" id="tanggal_daftar" 
                                class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-2" 
                                value="{{ request('tanggal_daftar') }}">
                        </div>
                    </div>
                    
                    {{-- Baris untuk Pengurutan dan Tombol Aksi --}}
                    <div class="grid grid-cols-1 md:grid-cols-5 gap-4 items-end mt-4">
                        {{-- 5. Pengurutan --}}
                        <div class="md:col-span-2">
                            <label for="sort_by" class="block text-sm font-medium text-gray-700 mb-1">Urutkan Berdasarkan</label>
                            <select name="sort_by" id="sort_by" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-2">
                                <option value="latest" {{ request('sort_by', 'latest') === 'latest' ? 'selected' : '' }}>Terbaru</option>
                                <option value="nama_asc" {{ request('sort_by') === 'nama_asc' ? 'selected' : '' }}>Nama Siswa (A-Z)</option>
                                <option value="nama_desc" {{ request('sort_by') === 'nama_desc' ? 'selected' : '' }}>Nama Siswa (Z-A)</option>
                            </select>
                        </div>
                        
                        <div class="md:col-span-3 flex justify-end gap-2">
                            {{-- Tombol Filter/Cari --}}
                            <button type="submit" class="w-full md:w-auto bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-md shadow-lg transition duration-150 ease-in-out">
                                <i class="fas fa-search mr-2"></i> Terapkan Filter
                            </button>
                            
                            {{-- Tombol Reset --}}
                            <a href="{{ route('admin.pendaftaran.index') }}" class="w-full md:w-auto bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-2 px-4 rounded-md shadow transition duration-150 ease-in-out text-center">
                                <i class="fas fa-undo mr-2"></i> Reset
                            </a>
                        </div>
                    </div>

                </form>
                {{-- --- Akhir Form Pencarian dan Filter --- --}}

                <div class="overflow-x-auto rounded-lg border">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Siswa</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email Pendaftar</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Asal Sekolah</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tgl Daftar</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($pendaftarans as $pendaftaran)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $pendaftaran->nama_siswa }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $pendaftaran->user->email ?? 'N/A' }} 
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $pendaftaran->asal_sekolah }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $pendaftaran->created_at->format('d M Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    @php
                                        $badgeColor = [
                                            'Pending' => 'bg-yellow-100 text-yellow-800',
                                            'Diterima' => 'bg-green-100 text-green-800',
                                            'Ditolak' => 'bg-red-100 text-red-800',
                                        ][$pendaftaran->status] ?? 'bg-gray-100 text-gray-800';
                                    @endphp
                                    <span class="inline-flex px-2 text-xs font-semibold leading-5 rounded-full {{ $badgeColor }}">
                                        {{ $pendaftaran->status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                    <a href="{{ route('admin.pendaftaran.show', $pendaftaran->id_pendaftaran) }}" 
                                       class="text-indigo-600 hover:text-indigo-900 font-semibold">
                                        Lihat Detail
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-6 py-8 text-center text-gray-500 bg-gray-50 italic">Tidak ada data</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-6">
                    {{-- Pastikan pagination mempertahankan query string filter --}}
                    {{ $pendaftarans->appends(request()->except('page'))->links() }}
                </div>
            </div>
        </div>
    </main>
</div>
@endsection