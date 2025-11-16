@extends('admin.layouts.app')

@section('title', 'Kelola Pendaftaran')

@section('content')
<div class="flex min-h-screen bg-white">

    <x-sidebar />

    <main class="w-full overflow-y-auto p-8 lg:p-12">

        {{-- Judul Halaman --}}
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-900">Kelola Pendaftaran</h1>
            <p class="mt-1 text-gray-600">Kelola data pendaftar di sini!</p>
        </div>

        {{-- Garis Pemisah --}}
       <hr class="my-5 h-px border-0 bg-black">

        {{-- Data Pendaftar Section --}}
        <div class="mb-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Data Pendaftar</h2>
            
            {{-- Tombol Ekspor Data --}}
            <a href="{{ route('admin.export.pendaftaran') }}"
                class="inline-flex items-center gap-1.5 bg-white hover:bg-gray-50 border-2 border-gray-900 text-gray-900 
                    font-medium py-1.5 px-3.5 text-sm rounded-[5px] shadow-sm transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                </svg>
                Ekspor Data
            </a>
        </div>

        {{-- Filter Bar (otomatis submit) --}}
        <div class="mb-6">
            <form id="filterForm" action="{{ route('admin.pendaftaran.index') }}" method="GET" class="flex flex-wrap items-center gap-3">

                {{-- Search --}}
                <div class="flex-1 min-w-[200px]">
                    <div class="relative">
                        <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                        <input type="text" name="search" placeholder="Cari"
                            value="{{ request('search') }}"
                            class="w-full pl-9 pr-3 py-1.5 rounded-md border-2 border-black
                                focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white text-sm"
                            oninput="this.form.submit()">
                    </div>
                </div>

                {{-- Nama Sort --}}
               <div class="inline-block">
                <button type="button" id="toggleSort" 
                        class="py-1.5 px-5 rounded-md border-2 border-black bg-white text-gray-700 text-sm flex items-center gap-2">
                    <span>Nama</span>
                    <span id="sortArrow">&#x25BC;</span> {{-- ▼ default = ascending --}}
                </button>
            </div>

                <script>
                    const toggleSortBtn = document.getElementById('toggleSort');
                    const sortArrow = document.getElementById('sortArrow');

                    // Ambil query string saat ini
                    const params = new URLSearchParams(window.location.search);
                    let isAsc = params.get('sort_by') !== 'nama_desc'; // default ascending

                    // Set panah saat load
                    sortArrow.innerHTML = isAsc ? '&#x25BC;' : '&#x25B2;';

                    toggleSortBtn.addEventListener('click', () => {
                        isAsc = !isAsc;

                        // Ganti panah
                        sortArrow.innerHTML = isAsc ? '&#x25BC;' : '&#x25B2;';

                        // Submit form dengan query string baru
                        params.set('sort_by', isAsc ? 'nama_asc' : 'nama_desc');

                        // Tetap pertahankan filter lain jika ada
                        const url = window.location.origin + window.location.pathname + '?' + params.toString();
                        window.location.href = url;
                    });
                </script>

                {{-- Status --}}
                <select name="status"
                    class="py-1.5 px-5.5 rounded-md border-2 border-black bg-white text-gray-700 text-sm
                        focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    onchange="this.form.submit()">
                    <option value="">Status</option>
                    @foreach ($list_status as $status)
                        <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>
                            {{ ucfirst($status) }}
                        </option>
                    @endforeach
                </select>

                {{-- Tanggal --}}
              <div class="relative inline-block">
                <button type="button" id="toggleTanggal" 
                        class="py-1.5 px-3 rounded-md border-2 border-black bg-white text-gray-700 text-sm flex items-center gap-2">
                    <span>Tanggal Daftar</span>
                    <span id="tanggalArrow">&#x25BC;</span> {{-- ▼ default --}}
                </button>
            </div>

                <script>
                document.addEventListener('DOMContentLoaded', () => {
                    const toggleBtn = document.getElementById('toggleTanggal');
                    const arrow = document.getElementById('tanggalArrow');

                    const params = new URLSearchParams(window.location.search);
                    let isLatest = params.get('sort_by') !== 'tanggal_asc'; // default terbaru

                    arrow.innerHTML = isLatest ? '▼' : '▲';

                    toggleBtn.addEventListener('click', () => {
                        isLatest = !isLatest;
                        arrow.innerHTML = isLatest ? '▼' : '▲';

                        const url = new URL(window.location.href);
                        url.searchParams.set('sort_by', isLatest ? 'tanggal_desc' : 'tanggal_asc');
                        window.location.href = url.toString();
                    });
                });
                </script>

                {{-- Asal Sekolah --}}
                <select name="asal_sekolah"
                   class="py-1.5 px-5.5 rounded-md border-2 border-black bg-white text-gray-700 text-sm 
                        focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    onchange="this.form.submit()">
                    <option value="">Asal Sekolah</option>
                    @foreach ($list_sekolah as $sekolah)
                        <option value="{{ $sekolah }}" {{ request('asal_sekolah') == $sekolah ? 'selected' : '' }}>
                            {{ $sekolah }}
                        </option>
                    @endforeach
                </select>

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
                                <button
                                class="setuju-btn inline-flex items-center text-white text-xs font-medium px-4 py-2 rounded-lg transition
                                    {{ $p->status === 'diterima' ? 'bg-teal-500' : 'bg-gray-300 hover:bg-teal-500' }}"
                                onclick="handleAction(this, '{{ route('admin.pendaftaran.approve', $p->id_pendaftaran) }}', 'setuju', {{ $p->id_pendaftaran }})">
                                Setuju
                            </button>

                                    {{-- Tombol Tolak --}}
                                    <button
                                class="tolak-btn inline-flex items-center text-white text-xs font-medium px-4 py-2 rounded-lg transition
                                    {{ $p->status === 'ditolak' ? 'bg-red-500' : 'bg-gray-300 hover:bg-red-500' }}"
                                onclick="handleAction(this, '{{ route('admin.pendaftaran.reject', $p->id_pendaftaran) }}', 'tolak', {{ $p->id_pendaftaran }})">
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

<script>
function handleAction(btn, url, actionType, idPendaftaran) {
    btn.disabled = true;

    fetch(url, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if(data.success){
            btn.disabled = false;

            const parent = btn.closest('div');
            const setujuBtn = parent.querySelector('.setuju-btn');
            const tolakBtn = parent.querySelector('.tolak-btn');

            // reset tombol lawan ke abu
            if(actionType === 'setuju'){
                setujuBtn.classList.remove('bg-gray-300');
                setujuBtn.classList.add('bg-teal-500');
                tolakBtn.classList.remove('bg-red-500');
                tolakBtn.classList.add('bg-gray-300');
            } else if(actionType === 'tolak'){
                tolakBtn.classList.remove('bg-gray-300');
                tolakBtn.classList.add('bg-red-500');
                setujuBtn.classList.remove('bg-teal-500');
                setujuBtn.classList.add('bg-gray-300');
            }

            // update badge status
            const row = btn.closest('tr');
            const statusCell = row.querySelector('td:nth-child(5)');
            let label = '';
            let bgClass = '';
            let textClass = '';

            if(actionType === 'setuju'){
                label = 'Diterima';
                bgClass = 'bg-teal-100';
                textClass = 'text-teal-700';
            } else if(actionType === 'tolak'){
                label = 'Ditolak';
                bgClass = 'bg-red-100';
                textClass = 'text-red-700';
            }

            statusCell.innerHTML = `<span class="inline-flex items-center px-3 py-1.5 text-xs font-semibold rounded-full ${bgClass} ${textClass}">${label}</span>`;

        } else {
            alert('Terjadi kesalahan!');
            btn.disabled = false;
        }
    })
    .catch(() => {
        alert('Terjadi kesalahan!');
        btn.disabled = false;
    });
}



</script>


</div>
@endsection