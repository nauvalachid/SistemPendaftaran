<x-guest-layout>
    <div class="container mx-auto mt-10 p-5">

        {{-- Header --}}
        <div class="text-center mb-10">
            <h1 class="text-3xl font-bold text-gray-800">Pembayaran Biaya Sekolah</h1>
            <p class="text-lg text-gray-600 mt-2">SD Muhammadiyah 2 Ambarketawang - Tahun Pelajaran 2026/2027</p>
        </div>

        <div class="max-w-6xl mx-auto">

            {{-- NOTIFIKASI ERROR/SUKSES --}}
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-5 shadow-sm">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-5 shadow-sm">
                    {{ session('error') }}
                </div>
            @endif
            @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-5 shadow-sm">
                    <ul class="list-disc ml-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Bagian Rincian Administrasi --}}
            <div class="bg-white p-8 shadow-2xl rounded-xl border border-gray-100 mb-10">
                <h2 class="text-xl font-bold mb-4 text-gray-800 border-b pb-2">Rincian Administrasi Sistem Penerimaan Murid Baru 2026/2027</h2>
                @if($rincian_biaya->isNotEmpty())
                    <h3 class="font-semibold text-lg mb-3 text-center text-blue-800">
                        Siswa: {{ $rincian_biaya->first()->jenis_kelamin ?? '—' }}
                    </h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">No</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Uraian</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Biaya</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($rincian_biaya as $index => $item)
                                    <tr>
                                        <td class="px-6 py-3 text-sm text-gray-500">{{ $index + 1 }}</td>
                                        <td class="px-6 py-3 text-sm font-medium text-gray-900">{{ $item->uraian }}</td>
                                        <td class="px-6 py-3 text-sm text-right text-gray-700 font-mono">
                                            Rp. {{ number_format($item->jumlah_biaya, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @endforeach
                                <tr class="bg-gray-100 font-bold italic">
                                    <td colspan="2" class="px-6 py-3 text-sm text-right uppercase">Total Kewajiban</td>
                                    <td class="px-6 py-3 text-sm text-right text-blue-900">
                                        Rp. {{ number_format($rincian_biaya->sum('jumlah_biaya'), 0, ',', '.') }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-center text-gray-500 py-5">Rincian biaya administrasi belum tersedia.</p>
                @endif
            </div>

            {{-- Ringkasan Tagihan & Riwayat --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-10">
                
                {{-- KARTU RINGKASAN TAGIHAN --}}
                <div class="bg-white p-6 shadow-2xl rounded-xl border border-gray-100 col-span-1">
                    <h3 class="text-xl font-bold mb-4 text-gray-800">Ringkasan Tagihan</h3>
                    @if(isset($tagihan))
                        <div class="space-y-4">
                            <div class="flex justify-between items-center text-gray-700">
                                <span class="text-sm">Total Tagihan</span>
                                <span class="font-bold text-gray-900">Rp. {{ number_format($tagihan->total_tagihan, 0, ',', '.') }}</span>
                            </div>

                            {{-- LOGIKA STATUS LUNAS --}}
                            @if($tagihan->sisa_tagihan <= 0)
                                <div class="bg-green-100 border border-green-500 text-green-700 p-4 rounded-lg text-center font-bold animate-pulse">
                                    <p class="text-lg">TAGIHAN ANDA SUDAH LUNAS</p>
                                    <p class="text-xs font-normal italic mt-1">Data pembayaran telah terverifikasi sistem.</p>
                                </div>
                            @else
                                <div class="flex justify-between items-center text-green-700 border-t pt-2 border-green-200">
                                    <span class="text-sm">Total Sudah Dibayar</span>
                                    @php
                                       $sudah_dibayar = $riwayat_cicilan->whereIn('status_konfirmasi', ['Dikonfirmasi', 'Menunggu Verifikasi'])
                                         ->sum('nominal_bayar');
                                    @endphp
                                    <span class="font-bold font-mono text-base">Rp. {{ number_format($sudah_dibayar, 0, ',', '.') }}</span>
                                </div>

                                <div class="flex justify-between items-center bg-blue-50 p-3 rounded-lg border border-blue-200">
                                    <span class="font-semibold text-gray-700">Sisa Tagihan</span>
                                    <span class="font-bold text-xl text-blue-800 font-mono">
                                        Rp. {{ number_format($tagihan->sisa_tagihan, 0, ',', '.') }}
                                    </span>
                                </div>
                            @endif
                        </div>
                    @else
                        <p class="text-center text-gray-500 py-5 font-italic">Data tagihan tidak ditemukan.</p>
                    @endif
                </div>

                {{-- RIWAYAT CICILAN --}}
                <div class="lg:col-span-2 bg-white p-6 shadow-2xl rounded-xl border border-gray-100">
                    <h3 class="text-xl font-bold mb-4 text-gray-800">Riwayat Pembayaran</h3>
                    <div class="max-h-80 overflow-y-auto pr-2">
                        @forelse($riwayat_cicilan as $cicilan)
                            <div class="p-4 border-b last:border-b-0 hover:bg-gray-50 transition">
                                <div class="flex justify-between items-start">
                                    <span class="font-semibold text-gray-800">{{ $cicilan->keterangan_cicilan }}</span>
                                    <span class="px-3 py-1 text-xs font-bold rounded-full {{ $cicilan->status_konfirmasi == 'Dikonfirmasi' ? 'bg-green-100 text-green-700 border border-green-200' : 'bg-yellow-100 text-yellow-700 border border-yellow-200' }}">
                                        {{ $cicilan->status_konfirmasi }}
                                    </span>
                                </div>
                                <div class="flex justify-between items-center mt-3">
                                    <div>
                                        <p class="text-lg font-bold text-gray-900 font-mono">Rp. {{ number_format($cicilan->nominal_bayar, 0, ',', '.') }}</p>
                                        <p class="text-xs text-gray-400 italic">{{ \Carbon\Carbon::parse($cicilan->tanggal_bayar)->translatedFormat('d F Y') }}</p>
                                    </div>
                                    <a href="{{ asset('storage/' . $cicilan->bukti_transfer) }}" target="_blank"
                                        class="flex items-center text-sm text-blue-600 font-bold hover:text-blue-800 group">
                                        Lihat Bukti 
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        @empty
                            <p class="text-center text-gray-500 py-10">Belum ada transaksi pembayaran yang dilakukan.</p>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- FORM UPLOAD (Hanya muncul jika belum lunas) --}}
            @if(isset($tagihan) && $tagihan->sisa_tagihan > 0)
                <div class="bg-white p-8 shadow-2xl rounded-xl border border-gray-100">
                    <h3 class="text-xl font-bold mb-6 text-gray-800 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-blue-800" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        Kirim Bukti Pembayaran
                    </h3>
                    
                    <form action="{{ route('pembayaran.submit') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-6 p-4 bg-gray-50 rounded-lg border border-gray-200">
                            <h4 class="font-bold text-gray-800 mb-1">Informasi Rekening Tujuan:</h4>
                            <p class="text-sm text-gray-700">Bank Rakyat Indonesia (BRI)</p>
                            <p class="text-lg font-bold text-blue-900 tracking-wider">7123-4567-890</p>
                            <p class="text-sm text-gray-600 italic">A.N. Andini Septi Andri</p>
                            <input type="hidden" name="tagihan_id" value="{{ $tagihan->id }}">
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            {{-- Input Nominal --}}
                            <div class="mb-6">
                                <label class="block text-sm font-bold text-gray-700 mb-2 uppercase">Nominal yang Dibayarkan</label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-500 font-bold">Rp.</span>
                                    <input type="number" name="nominal_bayar" required
                                        max="{{ $tagihan->sisa_tagihan }}"
                                        class="w-full pl-12 border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 shadow-sm" 
                                        placeholder="Contoh: 500000">
                                </div>
                                <p class="mt-2 text-xs text-gray-500 italic font-medium">* Maksimal pembayaran: Rp. {{ number_format($tagihan->sisa_tagihan, 0, ',', '.') }}</p>
                            </div>

                            {{-- Input File --}}
                            <div class="mb-6">
                                <label class="block text-sm font-bold text-gray-700 mb-2 uppercase text-center md:text-left">Upload Bukti Transfer (JPG/PNG/PDF)</label>
                                <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-blue-500 hover:bg-blue-50 transition-all cursor-pointer group" onclick="document.getElementById('bukti_input').click()">
                                    <input type="file" name="bukti_transfer" id="bukti_input" class="hidden"
                                        accept=".jpg,.jpeg,.png,.pdf" required
                                        onchange="document.getElementById('file_name').innerText = 'File terpilih: ' + this.files[0].name">
                                    
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 mx-auto text-gray-400 group-hover:text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                                    </svg>
                                    <p id="file_name" class="mt-2 text-sm font-bold text-blue-600 uppercase tracking-tight">Klik untuk pilih file</p>
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end mt-4">
                            <button type="submit"
                                class="w-full md:w-auto bg-blue-800 text-white px-10 py-4 rounded-xl font-extrabold uppercase hover:bg-blue-900 transition-all shadow-lg hover:shadow-2xl flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                                </svg>
                                Konfirmasi Pembayaran
                            </button>
                        </div>
                    </form>
                </div>
            @else
                {{-- FOOTER JIKA LUNAS --}}
                <div class="bg-gray-100 p-8 rounded-xl border border-gray-200 text-center">
                    <div class="inline-block p-4 bg-white rounded-full shadow-md mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800">Administrasi Anda Selesai</h3>
                    <p class="text-gray-600 mt-2 max-w-md mx-auto italic">Terima kasih sudah menyelesaikan seluruh kewajiban administrasi. Silakan simpan riwayat ini sebagai bukti pelunasan.</p>
                </div>
            @endif

        </div>
    </div>
</x-guest-layout>