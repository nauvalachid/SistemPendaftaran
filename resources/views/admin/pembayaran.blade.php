@extends('admin.layouts.app')

@section('title', 'Kelola Pembayaran')

@section('content')

    {{-- 1. LOAD ALPINE.JS --}}
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        [x-cloak] {
            display: none !important;
        }

        /* Styling Dropdown Custom */
        .custom-select-container {
            position: relative;
            min-width: 160px;
            cursor: pointer;
        }

        .custom-select-trigger {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0.6rem 1rem;
            border: 1px solid #d1d5db;
            border-radius: 0.5rem;
            background: white;
            font-size: 0.875rem;
        }

        .custom-select-options {
            position: absolute;
            top: 110%;
            left: 0;
            right: 0;
            background: white;
            border: 1px solid #d1d5db;
            border-radius: 0.5rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            z-index: 50;
            display: none;
            max-height: 200px;
            overflow-y: auto;
        }

        .custom-select-container.active .custom-select-options {
            display: block;
        }

        .custom-select-option {
            padding: 0.5rem 1rem;
            font-size: 0.875rem;
        }

        .custom-select-option:hover {
            background-color: #f3f4f6;
        }

        .custom-select-option.selected {
            background-color: #eff6ff;
            color: #2563eb;
            font-weight: 600;
        }

        .arrow {
            transition: transform 0.2s;
            font-size: 0.75rem;
            color: #9ca3af;
        }

        .custom-select-container.active .arrow {
            transform: rotate(180deg);
        }
    </style>

    <div class="flex min-h-screen bg-white" x-data="{ openModal: false, selectedData: { riwayat: [] } }">
        {{-- Sidebar Component --}}
        <x-sidebar />

        <main class="w-full overflow-y-auto p-6">
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
            <meta name="csrf-token" content="{{ csrf_token() }}">

            <div class="max-w-7xl mx-auto">
                <x-pageheadersatu title="Kelola Pembayaran"
                    description="Verifikasi bukti transfer dan pantau tagihan siswa di sini!" />

                {{-- Filter Bar --}}
                <div class="mb-6 mt-8">
                    <form id="filterForm" action="{{ route('admin.pembayaran.index') }}" method="GET"
                        class="flex flex-wrap items-center gap-3">
                        {{-- Search --}}
                        <div class="flex-1 min-w-[200px] max-w-3xl relative">
                            <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                            <input type="text" name="search" placeholder="Cari nama, NISN, atau sekolah..."
                                value="{{ request('search') }}"
                                class="w-full pl-10 pr-4 py-2.5 rounded-lg border border-gray-300 bg-white focus:ring-blue-500 focus:border-blue-500 text-sm shadow-sm">
                        </div>

                        {{-- Dropdown Status --}}
                        <div class="custom-select-container" id="dropdownStatus">
                            <input type="hidden" name="status" value="{{ request('status') }}">
                            <div class="custom-select-trigger">
                                <span>{{ request('status') ? ucfirst(request('status')) : 'Semua Status' }}</span>
                                <i class="fas fa-chevron-down arrow"></i>
                            </div>
                            <div class="custom-select-options">
                                <div class="custom-select-option" data-value="">Semua Status</div>
                                @foreach ($list_status as $status)
                                    <div class="custom-select-option" data-value="{{ $status }}">{{ ucfirst($status) }}</div>
                                @endforeach
                            </div>
                        </div>

                        {{-- Dropdown Sekolah --}}
                        <div class="custom-select-container" id="dropdownSekolah">
                            <input type="hidden" name="asal_sekolah" value="{{ request('asal_sekolah') }}">
                            <div class="custom-select-trigger">
                                <span
                                    class="truncate max-w-[150px]">{{ request('asal_sekolah') ? request('asal_sekolah') : 'Semua Sekolah' }}</span>
                                <i class="fas fa-chevron-down arrow"></i>
                            </div>
                            <div class="custom-select-options">
                                <div class="custom-select-option" data-value="">Semua Sekolah</div>
                                @foreach ($list_sekolah as $sekolah)
                                    <div class="custom-select-option" data-value="{{ $sekolah }}">{{ $sekolah }}</div>
                                @endforeach
                            </div>
                        </div>
                    </form>
                </div>

                {{-- Table --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase">Nama Lengkap
                                    </th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase">NISN</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase">Total Tagihan
                                    </th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase">Sudah Dibayar
                                    </th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase">Sisa Tagihan
                                    </th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase">Status</th>
                                    <th class="px-6 py-4 text-center text-xs font-bold text-gray-500 uppercase">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-100">
                                @forelse ($datas as $d)
                                                            <tr class="hover:bg-gray-50 transition">
                                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                                    {{ $d->pendaftaran->nama_siswa }}</td>
                                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                                    {{ $d->pendaftaran->nisn ?? '-' }}</td>
                                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-700">Rp
                                                                    {{ number_format($d->total_tagihan, 0, ',', '.') }}</td>
                                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-emerald-600">Rp
                                                                    {{ number_format($d->total_tagihan - $d->sisa_tagihan, 0, ',', '.') }}</td>
                                                                <td
                                                                    class="px-6 py-4 whitespace-nowrap text-sm font-bold {{ $d->sisa_tagihan > 0 ? 'text-rose-600' : 'text-gray-400' }}">
                                                                    Rp {{ number_format($d->sisa_tagihan, 0, ',', '.') }}</td>
                                                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                                    <span
                                                                        class="px-3 py-1 rounded-full text-xs font-bold border {{ $d->status_pembayaran == 'lunas' ? 'bg-emerald-100 text-emerald-700 border-emerald-500' : 'bg-yellow-100 text-yellow-700 border-yellow-500' }}">
                                                                        {{ ucfirst($d->status_pembayaran) }}
                                                                    </span>
                                                                </td>
                                                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                                                    <button @click="openModal = true; selectedData = { 
                                        nama: '{{ addslashes($d->pendaftaran->nama_siswa) }}',
                                        nisn: '{{ $d->pendaftaran->nisn ?? '-' }}',
                                        /* Ganti pembayaran_details menjadi pembayaran */
                                        total_cicilan: '{{ $d->pembayaran->count() }}x pembayaran', 
                                        total: '{{ number_format($d->total_tagihan, 0, ',', '.') }}',
                                        terbayar: '{{ number_format($d->total_tagihan - $d->sisa_tagihan, 0, ',', '.') }}',
                                        sisa: '{{ number_format($d->sisa_tagihan, 0, ',', '.') }}',
                                        id: '{{ $d->id }}',
                                        /* Ganti pembayaran_details menjadi pembayaran */
                                        riwayat: {{ ($d->pembayaran ?? collect())->map(function ($item, $index) {
                                        return [
                                            'nama' => 'Cicilan ' . ($index + 1),
                                            'tanggal' => $item->created_at->format('d/m/Y'),
                                            'jumlah' => number_format($item->nominal_bayar, 0, ',', '.'),
                                            'status' => $item->status_konfirmasi, // Sesuaikan dengan nama kolom di tabel pembayaran Anda
                                            'bukti_url' => route('admin.pembayaran.view-bukti', $item->id)
                                        ];
                                    })->toJson() }}
                                    }" class="px-4 py-1.5 bg-indigo-50 text-indigo-600 border border-indigo-200 rounded-lg text-xs font-bold hover:bg-indigo-600 hover:text-white transition">
                                                                        Detail
                                                                    </button>
                                                                </td>
                                                            </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-6 py-12 text-center text-gray-400">Tidak ada data pembayaran.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>

        {{-- MODAL DETAIL --}}
        <div x-show="openModal" x-cloak class="fixed inset-0 z-[999] overflow-y-auto flex items-center justify-center p-4">

            <div class="fixed inset-0 bg-black/50 transition-opacity" @click="openModal = false"></div>

            <div x-show="openModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100"
                class="relative bg-white w-full max-w-2xl rounded-[20px] shadow-2xl p-8 overflow-hidden">

                <h3 class="text-2xl font-extrabold text-center text-gray-900 mb-8">Detail Pembayaran</h3>

                {{-- Info Siswa --}}
                <div class="bg-white p-6 rounded-xl shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07)] mb-8 border border-gray-100">
                    <div class="grid grid-cols-3 gap-4">
                        <div>
                            <p class="text-xs text-gray-400 font-bold uppercase mb-1">Nama Siswa</p>
                            <p class="text-base font-bold text-gray-900" x-text="selectedData.nama"></p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 font-bold uppercase mb-1">NISN</p>
                            <p class="text-base font-bold text-gray-900" x-text="selectedData.nisn"></p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 font-bold uppercase mb-1">Total Cicilan</p>
                            <p class="text-base font-bold text-gray-900" x-text="selectedData.total_cicilan"></p>
                        </div>
                    </div>
                </div>

                {{-- Ringkasan Tagihan --}}
                <div class="bg-gray-50 p-6 rounded-xl border border-gray-200 mb-8">
                    <h4 class="font-bold text-gray-900 mb-4">Ringkasan Tagihan</h4>
                    <div class="space-y-3">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Total Tagihan</span>
                            <span class="font-bold text-gray-900">Rp. <span x-text="selectedData.total"></span></span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Total Sudah Dibayar</span>
                            <span class="font-bold text-emerald-500">Rp. <span x-text="selectedData.terbayar"></span></span>
                        </div>
                        <div class="border-t border-gray-200 pt-3 flex justify-between">
                            <span class="text-gray-900 font-bold">Sisa Tagihan</span>
                            <span class="font-bold text-rose-600">Rp. <span x-text="selectedData.sisa"></span></span>
                        </div>
                    </div>
                </div>

                {{-- Riwayat Cicilan --}}
                <div class="mb-8">
                    <h4 class="font-bold text-gray-900 mb-4">Riwayat Cicilan</h4>
                    <div class="space-y-4 max-h-60 overflow-y-auto pr-2">
                        <template x-for="item in selectedData.riwayat">
                            <div class="p-4 border border-gray-200 rounded-xl flex justify-between items-center bg-white">
                                <div>
                                    <p class="font-bold text-gray-900" x-text="item.nama"></p>
                                    <p class="text-xs text-gray-400 mt-1" x-text="item.tanggal"></p>
                                    <a :href="item.bukti_url" target="_blank"
                                        class="text-xs text-blue-500 underline mt-1 inline-block font-semibold">
                                        Lihat Bukti Transfer
                                    </a>
                                </div>
                                <div class="text-right">
                                    <p class="font-bold text-gray-900">Rp. <span x-text="item.jumlah"></span></p>
                                    <span
                                        class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-400 text-white mt-2">
                                        <i class="fas fa-check mr-1 text-[8px]"></i> <span x-text="item.status"></span>
                                    </span>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

                <div class="flex justify-end">
                    <button @click="openModal = false"
                        class="px-10 py-2 border-2 border-indigo-900 text-indigo-900 font-bold rounded-xl hover:bg-indigo-50 transition">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- JS UNTUK DROPDOWN --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const dropdowns = document.querySelectorAll('.custom-select-container');
            const filterForm = document.getElementById('filterForm');

            dropdowns.forEach(dropdown => {
                const trigger = dropdown.querySelector('.custom-select-trigger');
                const options = dropdown.querySelectorAll('.custom-select-option');
                const hiddenInput = dropdown.querySelector('input[type="hidden"]');

                trigger.addEventListener('click', (e) => {
                    e.stopPropagation();
                    dropdowns.forEach(d => { if (d !== dropdown) d.classList.remove('active'); });
                    dropdown.classList.toggle('active');
                });

                options.forEach(option => {
                    option.addEventListener('click', () => {
                        hiddenInput.value = option.getAttribute('data-value');
                        filterForm.submit();
                    });
                });
            });

            window.addEventListener('click', () => dropdowns.forEach(d => d.classList.remove('active')));
        });
    </script>

@endsection