@extends('admin.layouts.app')

{{-- Tambahkan meta tag CSRF di layout utama jika belum ada --}}
{{-- <meta name="csrf-token" content="{{ csrf_token() }}"> --}}

@section('title', 'Kelola Pendaftaran')

@section('content')
<div class="flex min-h-screen bg-gray-50">

    {{-- Asumsi: Komponen Sidebar didefinisikan --}}
    <x-sidebar />

    <main class="w-full overflow-y-auto p-6 md:p-12">
        {{-- Inject Font Awesome CDN untuk ikon (ideal ditempatkan di layout) --}}
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />

        {{-- Header Halaman --}}
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-900">Kelola Pendaftaran</h1>
            <p class="mt-1 text-gray-600">Kelola data pendaftar di sini!</p>
        </div>

        {{-- Garis Pemisah --}}
       <hr class="my-5 h-px border-0 bg-gray-200">

        {{-- Data Pendaftar Section --}}
        <div class="mb-6 flex justify-between items-center">
            <h2 class="text-xl font-semibold text-gray-900">Data Pendaftar</h2>
            
            {{-- Tombol Ekspor Data --}}
            {{-- Asumsi route('admin.export.pendaftaran') ada --}}
            <a href="{{ route('admin.export.pendaftaran') }}"
                class="inline-flex items-center gap-1.5 bg-white hover:bg-gray-100 border-2 border-gray-900 text-gray-900 
                    font-medium py-1.5 px-3.5 text-sm rounded-lg shadow-sm transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                </svg>
                Ekspor Data
            </a>
        </div>

        {{-- Filter Bar --}}
        <div class="mb-6 bg-white p-4 rounded-xl shadow-md border border-gray-200">
            <form id="filterForm" action="{{ route('admin.pendaftaran.index') }}" method="GET" class="flex flex-wrap items-center gap-3">

                {{-- Search --}}
                <div class="flex-1 min-w-[200px] max-w-sm">
                    <div class="relative">
                        <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                        <input type="text" name="search" placeholder="Cari"
                            value="{{ request('search') }}"
                            class="w-full pl-9 pr-3 py-2 rounded-lg border border-gray-300
                                focus:ring-blue-500 focus:border-blue-500 bg-white text-sm"
                            onchange="this.form.submit()">
                    </div>
                </div>

                {{-- Filter Sort Nama (Toggle Button) --}}
                <div class="inline-block">
                    <button type="button" id="toggleSortNama" 
                            class="py-2 px-4 rounded-lg border border-gray-300 bg-white text-gray-700 text-sm flex items-center gap-2 hover:bg-gray-50 transition">
                        <span>Nama</span>
                        <span id="sortNamaArrow" class="text-xs">
                            @if(request('sort_by') === 'nama_desc') &#x25B2; @else &#x25BC; @endif
                        </span>
                    </button>
                </div>

                {{-- Filter Status --}}
                <select name="status"
                    class="py-2 px-4 rounded-lg border border-gray-300 bg-white text-gray-700 text-sm
                        focus:ring-blue-500 focus:border-blue-500"
                    onchange="this.form.submit()">
                    <option value="">Status</option>
                    @foreach ($list_status as $status)
                        <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>
                            {{ ucfirst($status) }}
                        </option>
                    @endforeach
                </select>

                {{-- Filter Sort Tanggal (Toggle Button) --}}
                <div class="relative inline-block">
                    <button type="button" id="toggleSortTanggal" 
                            class="py-2 px-4 rounded-lg border border-gray-300 bg-white text-gray-700 text-sm flex items-center gap-2 hover:bg-gray-50 transition">
                        <span>Tanggal Daftar</span>
                        <span id="sortTanggalArrow" class="text-xs">
                            {{-- Default: Descending (Terbaru) jika tidak ada sort_by atau sort_by tidak spesifik --}}
                            @if(request('sort_by') === 'tanggal_asc') &#x25B2; @else &#x25BC; @endif
                        </span>
                    </button>
                </div>

                {{-- Filter Asal Sekolah --}}
                <select name="asal_sekolah"
                   class="py-2 px-4 rounded-lg border border-gray-300 bg-white text-gray-700 text-sm 
                        focus:ring-blue-500 focus:border-blue-500"
                    onchange="this.form.submit()">
                    <option value="">Asal Sekolah</option>
                    @foreach ($list_sekolah as $sekolah)
                        <option value="{{ $sekolah }}" {{ request('asal_sekolah') == $sekolah ? 'selected' : '' }}>
                            {{ $sekolah }}
                        </option>
                    @endforeach
                </select>

                {{-- Hidden input untuk menyimpan query sort yang lain --}}
                @if(request('search'))<input type="hidden" name="search" value="{{ request('search') }}">@endif
                @if(request('status'))<input type="hidden" name="status" value="{{ request('status') }}">@endif
                @if(request('asal_sekolah'))<input type="hidden" name="asal_sekolah" value="{{ request('asal_sekolah') }}">@endif

            </form>
        </div>

        {{-- Card Container --}}
        <div class="bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden">

            {{-- Table --}}
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Nama Lengkap</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">NISN</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Asal Sekolah</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Tanggal Daftar</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Status</th>
                            <th class="px-6 py-4 text-center text-sm font-semibold text-gray-700">Aksi</th>
                        </tr>
                    </thead>

                    <tbody class="bg-white divide-y divide-gray-100">

                        @forelse ($pendaftarans as $p)
                        <tr class="hover:bg-gray-50 transition">

                            <td class="px-6 py-4 text-sm font-medium text-gray-900 whitespace-nowrap">{{ $p->nama_siswa }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600 whitespace-nowrap">{{ $p->nisn ?? '-' }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600 whitespace-nowrap">{{ $p->asal_sekolah }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600 whitespace-nowrap">
                                {{ \Carbon\Carbon::parse($p->created_at)->locale('id')->isoFormat('dddd, D MMM YYYY') }}
                            </td>

                            {{-- Badge Status --}}
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    // Konfigurasi warna status
                                    $statusConfig = [
                                        'diterima' => ['bg' => 'bg-teal-100', 'text' => 'text-teal-700', 'label' => 'Diterima'],
                                        'pending' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-700', 'label' => 'Pending'],
                                        'ditolak' => ['bg' => 'bg-red-100', 'text' => 'text-red-700', 'label' => 'Ditolak'],
                                    ];
                                    $config = $statusConfig[strtolower($p->status)] ?? ['bg' => 'bg-gray-100', 'text' => 'text-gray-700', 'label' => ucfirst($p->status)];
                                @endphp

                                <span class="inline-flex items-center px-3 py-1.5 text-xs font-semibold rounded-full {{ $config['bg'] }} {{ $config['text'] }}">
                                    {{ $config['label'] }}
                                </span>
                            </td>

                            {{-- Aksi --}}
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex justify-center gap-2">
                                    {{-- Tombol Detail --}}
                                    <a href="{{ route('admin.pendaftaran.show', $p->id_pendaftaran) }}"
                                        class="inline-flex items-center bg-blue-500 hover:bg-blue-600 text-white text-xs font-medium px-4 py-2 rounded-lg transition">
                                        Detail
                                    </a>

                                    @php
                                        // Cek apakah tombol harus aktif (hanya jika status 'pending')
                                        $isPending = strtolower($p->status) === 'pending';
                                        $isDisabled = !$isPending;

                                        // Tentukan kelas CSS berdasarkan status saat ini
                                        $setujuClass = $isPending ? 'bg-teal-500 hover:bg-teal-600' : 'bg-gray-300 cursor-not-allowed';
                                        $tolakClass = $isPending ? 'bg-red-500 hover:bg-red-600' : 'bg-gray-300 cursor-not-allowed';

                                        // Jika sudah diterima/ditolak, berikan warna sesuai status tapi tetap non-aktif
                                        if (strtolower($p->status) === 'diterima') {
                                            $setujuClass = 'bg-gray-300 cursor-not-allowed';
                                        } elseif (strtolower($p->status) === 'ditolak') {
                                            $tolakClass = 'bg-gray-300 cursor-not-allowed';
                                        }
                                    @endphp
                                    
                                    {{-- Tombol Setuju (Aktif hanya jika Pending) --}}
                                    <button
                                        class="setuju-btn inline-flex items-center text-white text-xs font-medium px-4 py-2 rounded-lg transition {{ $setujuClass }}"
                                        onclick="handleAction(this, '{{ route('admin.pendaftaran.approve', $p->id_pendaftaran) }}', 'setuju', {{ $p->id_pendaftaran }})"
                                        {{ $isDisabled ? 'disabled' : '' }}>
                                        Setuju
                                    </button>

                                    {{-- Tombol Tolak (Aktif hanya jika Pending) --}}
                                    <button
                                        class="tolak-btn inline-flex items-center text-white text-xs font-medium px-4 py-2 rounded-lg transition {{ $tolakClass }}"
                                        onclick="handleAction(this, '{{ route('admin.pendaftaran.reject', $p->id_pendaftaran) }}', 'tolak', {{ $p->id_pendaftaran }})"
                                        {{ $isDisabled ? 'disabled' : '' }}>
                                        Tolak
                                    </button>
                                </div>
                            </td>
                        </tr>

                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center gap-2">
                                    <i class="fas fa-inbox text-4xl text-gray-300"></i>
                                    <p class="text-gray-500 font-medium">Tidak ada data pendaftaran yang sesuai dengan filter.</p>
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
                    {{ $pendaftarans->appends(request()->except('page'))->links() }}
                </div>
            </div>
            @endif

        </div>

    </main>

<script>
    // --- Logika Sorting Client-Side ---

    document.addEventListener('DOMContentLoaded', () => {
        const urlParams = new URLSearchParams(window.location.search);

        // 1. Logika Sort Nama
        const toggleSortNamaBtn = document.getElementById('toggleSortNama');
        toggleSortNamaBtn.addEventListener('click', () => {
            let currentSort = urlParams.get('sort_by');
            let newSort = 'nama_asc'; // Default: ascending

            if (currentSort === 'nama_asc') {
                newSort = 'nama_desc';
            }
            
            // Hapus semua parameter sort_by yang lain
            urlParams.delete('sort_by');
            urlParams.set('sort_by', newSort);

            window.location.href = window.location.pathname + '?' + urlParams.toString();
        });

        // 2. Logika Sort Tanggal
        const toggleSortTanggalBtn = document.getElementById('toggleSortTanggal');
        toggleSortTanggalBtn.addEventListener('click', () => {
            let currentSort = urlParams.get('sort_by');
            let newSort = 'tanggal_desc'; // Default: descending (Terbaru)

            if (currentSort === 'tanggal_desc') {
                newSort = 'tanggal_asc';
            }
            
            // Hapus semua parameter sort_by yang lain
            urlParams.delete('sort_by');
            urlParams.set('sort_by', newSort);

            window.location.href = window.location.pathname + '?' + urlParams.toString();
        });

    });


    // --- Logika Aksi Approve/Reject ---

    function handleAction(btn, url, actionType, idPendaftaran) {
        // Cek apakah tombol sudah dinonaktifkan dari Blade
        if (btn.disabled) {
            return; 
        }

        const actionLabel = actionType === 'setuju' ? 'Menerima' : 'Menolak';

        // Ganti alert bawaan dengan konfirmasi modal
        const confirmation = window.confirm(Anda yakin ingin ${actionLabel} pendaftar ini?);
        
        if (!confirmation) {
            return;
        }

        btn.disabled = true; // Nonaktifkan tombol saat proses berlangsung
        btn.innerHTML = <i class="fas fa-spinner fa-spin"></i> Proses; // Tampilkan loading

        fetch(url, {
            method: 'POST',
            headers: {
                // Ambil token CSRF dari meta tag di layout utama
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => {
            if (!response.ok) {
                // Lempar error jika respons bukan 2xx
                return response.json().then(errorData => {
                    throw new Error(errorData.message || HTTP error! status: ${response.status});
                });
            }
            return response.json();
        })
        .then(data => {
            if(data.success){
                // Muat ulang halaman agar data dan status tombol diperbarui
                window.location.reload(); 
            } else {
                alert('Aksi gagal! Pesan: ' + (data.message || 'Unknown Error'));
                // Kembalikan status tombol jika gagal
                btn.disabled = false;
                btn.innerHTML = actionLabel;
            }
        })
        .catch((error) => {
            console.error('Fetch error:', error);
            alert('Terjadi kesalahan koneksi atau server: ' + error.message);
            // Kembalikan status tombol jika terjadi kesalahan
            btn.disabled = false;
            btn.innerHTML = actionLabel;
        });
    }
</script>

</div>
@endsection