@extends('admin.layouts.app')

@section('title', 'Kelola Pendaftaran')

@section('content')
    <div class="flex min-h-screen bg-gray-50">

        <x-sidebar />

    <main class="w-full overflow-y-auto p-8 lg:p-12">

        {{-- Judul Halaman --}}
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-900">Kelola Pendaftaran</h1>
            <p class="mt-1 text-gray-600">Kelola data pendaftar di sini!</p>
        </div>

        {{-- Garis Pemisah --}}
        <div class="border-b-2 border-gray-900 mb-6"></div>

        {{-- Data Pendaftar Section --}}
        <div class="mb-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Data Pendaftar</h2>
            
            {{-- Tombol Ekspor Data --}}
            <a href="{{ route('admin.export.pendaftaran') }}"
                class="inline-flex items-center gap-2 bg-white hover:bg-gray-50 border-2 border-gray-900 text-gray-900 font-medium py-2.5 px-5 rounded-lg shadow-sm transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                </svg>
                Ekspor Data
            </a>
        </div>

        {{-- Filter Bar (di luar card) --}}
        <div class="mb-6">
            <form action="{{ route('admin.pendaftaran.index') }}" method="GET" class="flex flex-wrap items-center gap-3">

                {{-- Search --}}
                <div class="flex-1 min-w-[250px]">
                    <div class="relative">
                        <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                        <input type="text" name="search" placeholder="Cari"
                            value="{{ request('search') }}"
                            class="w-full pl-11 pr-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white">
                    </div>
                </div>

                {{-- Nama Sort --}}
                <select name="sort_by"
                    class="py-3.0 px-5 rounded-lg border border-gray-300 bg-white text-gray-700 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="nama_asc" {{ request('sort_by') == 'nama_asc' ? 'selected' : '' }}>Nama </option>
                    <option value="nama_desc" {{ request('sort_by') == 'nama_desc' ? 'selected' : '' }}>Nama</option>
                    <option value="latest" {{ request('sort_by') == 'latest' ? 'selected' : '' }}>Terbaru</option>
                </select>

                {{-- Status --}}
                <select name="status"
                    class="py-2.5 px-4 rounded-lg border border-gray-300 bg-white text-gray-700 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Status</option>
                    @foreach ($list_status as $status)
                        <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>
                            {{ ucfirst($status) }}
                        </option>
                    @endforeach
                </select>

                {{-- Tanggal --}}
                <input type="date" name="tanggal_daftar"
                    value="{{ request('tanggal_daftar') }}"
                    placeholder="Tanggal Daftar"
                    class="py-2.5 px-4 rounded-lg border border-gray-300 bg-white text-gray-700 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">

                {{-- Asal Sekolah --}}
                <select name="asal_sekolah"
                    class="py-2.5 px-4 rounded-lg border border-gray-300 bg-white text-gray-700 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Asal Sekolah</option>
                    @foreach ($list_sekolah as $sekolah)
                        <option value="{{ $sekolah }}" {{ request('asal_sekolah') == $sekolah ? 'selected' : '' }}>
                            {{ $sekolah }}
                        </option>
                    @endforeach
                </select>

                {{-- Tombol Apply (optional, bisa dihapus jika auto-submit) --}}
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2.5 px-6 rounded-lg transition">
                    Terapkan
                </button>

            </form>
        </div>

        {{-- Card Container --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">

            {{-- Table --}}
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead class="bg-white border-b-2 border-gray-200">
                        <tr>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Nama Lengkap</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">NISN</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Asal Sekolah</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Tanggal Daftar</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Status</th>
                            <th class="px-6 py-4 text-center text-sm font-semibold text-gray-900">Aksi</th>
                        </tr>
                    </thead>

                    <tbody class="bg-white divide-y divide-gray-200">

                        @forelse ($pendaftarans as $p)
                        <tr class="hover:bg-gray-50 transition">

                            <td class="px-6 py-4 text-sm text-gray-900">{{ $p->nama_siswa }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $p->nisn ?? '-' }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $p->asal_sekolah }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                {{ $p->created_at->format('l, d M Y') }}
                            </td>

                            {{-- Badge Status --}}
                            <td class="px-6 py-4">
                                @php
                                    $statusConfig = [
                                        'diterima' => ['bg' => 'bg-teal-100', 'text' => 'text-teal-700', 'label' => 'Diterima'],
                                        'pending' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-700', 'label' => 'Pending'],
                                        'ditolak' => ['bg' => 'bg-red-100', 'text' => 'text-red-700', 'label' => 'Ditolak'],
                                    ];
                                    $config = $statusConfig[$p->status] ?? ['bg' => 'bg-gray-100', 'text' => 'text-gray-700', 'label' => ucfirst($p->status)];
                                @endphp

                                <span class="inline-flex items-center px-3 py-1.5 text-xs font-semibold rounded-full {{ $config['bg'] }} {{ $config['text'] }}">
                                    {{ $config['label'] }}
                                </span>
                            </td>

                            {{-- Aksi --}}
                            <td class="px-6 py-4">
                                <div class="flex justify-center gap-2">

                                    <a href="{{ route('admin.pendaftaran.show', $p->id_pendaftaran) }}"
                                        class="inline-flex items-center bg-blue-500 hover:bg-blue-600 text-white text-xs font-medium px-4 py-2 rounded-lg transition">
                                        Detail
                                    </a>

                                    {{-- Tombol Setuju --}}
                                    @if($p->status !== 'diterima')
                                    <a href="{{ route('admin.pendaftaran.approve', $p->id_pendaftaran) }}"
                                        class="inline-flex items-center bg-teal-500 hover:bg-teal-600 text-white text-xs font-medium px-4 py-2 rounded-lg transition">
                                        Setuju
                                    </a>
                                    @endif

                                    {{-- Tombol Tolak --}}
                                    @if($p->status !== 'ditolak')
                                    <a href="{{ route('admin.pendaftaran.reject', $p->id_pendaftaran) }}"
                                        class="inline-flex items-center bg-red-500 hover:bg-red-600 text-white text-xs font-medium px-4 py-2 rounded-lg transition">
                                        Tolak
                                    </a>
                                    @endif

                                </div>
                            </td>

                        </tr>
                        @empty

                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center gap-2">
                                    <i class="fas fa-inbox text-4xl text-gray-300"></i>
                                    <p class="text-gray-500 font-medium">Tidak ada data pendaftaran</p>
                                </div>
                            </td>
                        </tr>

                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if($pendaftarans->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                <div class="flex justify-center">
                    {{ $pendaftarans->links() }}
                </div>
            </div>
            @endif

        </div>

    </main>
</div>
        <main class="w-full overflow-y-auto p-8 lg:p-12">
            <div class="max-w-7xl mx-auto">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6 border border-gray-100">
                    <h2 class="text-3xl font-bold mb-6 text-gray-800">Manajemen Pendaftaran Siswa</h2>

                    {{-- Pesan Sukses/Error --}}
                    @if (session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6"
                            role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif

                    {{-- --- Form Pencarian dan Filter --- --}}
                    <form action="{{ route('admin.pendaftaran.index') }}" method="GET"
                        class="mb-8 p-4 bg-gray-50 rounded-lg shadow-inner border border-gray-200">
                        <div class="grid grid-cols-1 md:grid-cols-5 gap-4 items-end">
                            <div class="md:col-span-2">
                                <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Cari
                                    (Nama/NISN/Sekolah)</label>
                                <input type="text" name="search" id="search"
                                    class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-2"
                                    placeholder="Masukkan nama, NISN, atau sekolah..." value="{{ request('search') }}">
                            </div>

                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                                <select name="status" id="status"
                                    class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-2">
                                    <option value="all">Semua Status</option>
                                    @foreach ($list_status as $status)
                                        <option value="{{ $status }}" {{ request('status') === $status ? 'selected' : '' }}>
                                            {{ $status }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="asal_sekolah" class="block text-sm font-medium text-gray-700 mb-1">Asal
                                    Sekolah</label>
                                <select name="asal_sekolah" id="asal_sekolah"
                                    class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-2">
                                    <option value="all">Semua Sekolah</option>
                                    @foreach ($list_sekolah as $sekolah)
                                        <option value="{{ $sekolah }}" {{ request('asal_sekolah') === $sekolah ? 'selected' : '' }}>
                                            {{ $sekolah }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="tanggal_daftar" class="block text-sm font-medium text-gray-700 mb-1">Tgl.
                                    Daftar</label>
                                <input type="date" name="tanggal_daftar" id="tanggal_daftar"
                                    class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-2"
                                    value="{{ request('tanggal_daftar') }}">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-5 gap-4 items-end mt-4">
                            <div class="md:col-span-2">
                                <label for="sort_by" class="block text-sm font-medium text-gray-700 mb-1">Urutkan
                                    Berdasarkan</label>
                                <select name="sort_by" id="sort_by"
                                    class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-2">
                                    <option value="latest" {{ request('sort_by', 'latest') === 'latest' ? 'selected' : '' }}>
                                        Terbaru</option>
                                    <option value="nama_asc" {{ request('sort_by') === 'nama_asc' ? 'selected' : '' }}>Nama
                                        Siswa (A-Z)</option>
                                    <option value="nama_desc" {{ request('sort_by') === 'nama_desc' ? 'selected' : '' }}>Nama
                                        Siswa (Z-A)</option>
                                </select>
                            </div>

                            <div class="md:col-span-3 flex justify-end gap-2">
                                <button type="submit"
                                    class="w-full md:w-auto bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-md shadow-lg transition duration-150 ease-in-out">
                                    <i class="fas fa-search mr-2"></i> Terapkan Filter
                                </button>
                                <a href="{{ route('admin.pendaftaran.index') }}"
                                    class="w-full md:w-auto bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-2 px-4 rounded-md shadow transition duration-150 ease-in-out text-center">
                                    <i class="fas fa-undo mr-2"></i> Reset
                                </a>
                            </div>
                        </div>
                    </form>
                    {{-- --- Akhir Form Pencarian dan Filter --- --}}

                    {{-- 🔽 Tombol Export Data --}}
                    <div class="flex justify-end mb-4">
                        <a href="{{ route('admin.export.pendaftaran') }}"
                            class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-md shadow-lg transition duration-150 ease-in-out inline-flex items-center">
                            <i class="fas fa-file-excel mr-2"></i> Export Data ke Excel
                        </a>
                    </div>

                    <div class="overflow-x-auto rounded-lg border">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Nama Siswa</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Email Pendaftar</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Asal Sekolah</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Tgl Daftar</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Status</th>
                                    <th
                                        class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($pendaftarans as $pendaftaran)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $pendaftaran->nama_siswa }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $pendaftaran->user->email ?? 'N/A' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $pendaftaran->asal_sekolah }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $pendaftaran->created_at->format('d M Y') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            @php
                                                $colors = [
                                                    'pending' => 'bg-yellow-100 text-yellow-800 shadow-inner ring-1 ring-yellow-200/60',
                                                    'diterima' => 'bg-green-100 text-green-800 shadow-inner ring-1 ring-green-200/60',
                                                    'ditolak' => 'bg-red-100 text-red-800 shadow-inner ring-1 ring-red-200/60',
                                                ];

                                                $status = strtolower(trim($pendaftaran->status));
                                            @endphp

                                            <span
                                                class="inline-flex px-3 py-1 text-xs font-semibold leading-5 rounded-full {{ $colors[$status] ?? 'bg-gray-100 text-gray-800 shadow-inner ring-1 ring-gray-200/60' }}">
                                                {{ ucfirst($status) }}
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
                                        <td colspan="6" class="px-6 py-8 text-center text-gray-500 bg-gray-50 italic">Tidak ada
                                            data</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-6">
                        {{ $pendaftarans->appends(request()->except('page'))->links() }}
                    </div>
                </div>
            </div>
        </main>
    </div>
@endsection