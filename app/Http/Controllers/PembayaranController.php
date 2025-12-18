<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Pendaftaran; // Pastikan Model User sudah dibuat
use App\Models\InformasiPembayaran; // Pastikan Model sudah dibuat
use App\Models\Tagihan; // Pastikan Model sudah dibuat
use App\Models\Pembayaran; // Pastikan Model sudah dibuat
use Illuminate\Support\Facades\Storage;


class PembayaranController extends Controller
{
    public function index()
    {
        if (!Auth::check()) {
            return redirect('/login')->with('error', 'Silakan login untuk mengakses halaman pembayaran.');
        }

        $id_user = Auth::id();
        $siswa = Pendaftaran::where('id_user', $id_user)->first();

        if (!$siswa) {
            return redirect()->route('pendaftaran.create')->with('warning', 'Anda belum melengkapi formulir pendaftaran.');
        }

        $id_pendaftaran = $siswa->id_pendaftaran;

        // --- 1. Konversi Jenis Kelamin ---
        $jenis_kelamin_raw = $siswa->jenis_kelamin;

        // Konversi nilai ENUM ('Laki-laki', 'Perempuan') ke Key Biaya ('Putra', 'Putri')
        if (strtolower($jenis_kelamin_raw) == 'laki-laki') {
            $jenis_kelamin_key = 'Putra';
        } elseif (strtolower($jenis_kelamin_raw) == 'perempuan') {
            $jenis_kelamin_key = 'Putri';
        } else {
            // Jika jenis kelamin tidak terdefinisi, default ke Putri (atau yang paling umum)
            $jenis_kelamin_key = 'Putri';
        }

        // --- 2. Mengambil Rincian Biaya ---
        $rincian_biaya = InformasiPembayaran::where('jenis_kelamin', $jenis_kelamin_key)
            ->orWhere('jenis_kelamin', 'Umum')
            ->orderBy('id')
            ->get();

        // --- Cek Tambahan: Jika rincian_biaya masih kosong setelah query ---
        if ($rincian_biaya->isEmpty()) {
            // Ini terjadi jika SEEDER belum dijalankan atau tidak ada biaya 'Umum'
            return view('pembayaran.index', [
                'rincian_biaya' => collect(), // Kirim koleksi kosong
                'tagihan' => (object) ['total_tagihan' => 0, 'sisa_tagihan' => 0, 'status_pembayaran' => 'Belum Lunas', 'id' => null],
                'riwayat_cicilan' => collect(),
            ])->with('warning', 'Rincian biaya administrasi untuk ' . $jenis_kelamin_key . ' belum diatur oleh Admin.');
        }


        // --- 3. Mengambil Ringkasan Tagihan ---
        $tagihan = Tagihan::where('id_pendaftaran', $id_pendaftaran)->first();

        if (!$tagihan) {
            $total_tagihan_default = $rincian_biaya->sum('jumlah_biaya');
            $tagihan = Tagihan::create([
                'id_pendaftaran' => $id_pendaftaran,
                'total_tagihan' => $total_tagihan_default,
                'sisa_tagihan' => $total_tagihan_default,
                'status_pembayaran' => 'Belum Lunas',
            ]);
        } 
        
        // --- 4. Mengambil Riwayat Cicilan ---
        $riwayat_cicilan = collect();
        if ($tagihan->id) {
            $riwayat_cicilan = Pembayaran::where('tagihan_id', $tagihan->id)
                ->orderBy('tanggal_bayar', 'desc')
                ->get();
        }

        // --- 5. Menampilkan View ---
        return view('pembayaran.index', [
            'rincian_biaya' => $rincian_biaya,
            'tagihan' => $tagihan,
            'riwayat_cicilan' => $riwayat_cicilan,
        ]);
    }

    /**
     * Langkah 1: Tambahkan properti untuk konsistensi upload
     */
    protected $documentFields = ['bukti_transfer'];

    public function submit(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'tagihan_id' => 'required|exists:tagihan,id',
            'nominal_bayar' => 'required|numeric|min:1000',
            'bukti_transfer' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        // 2. Ambil data Tagihan & Siswa
        $siswa = Pendaftaran::where('id_user', Auth::id())->first();
        $tagihan = Tagihan::find($request->tagihan_id);

        // Keamanan: Pastikan tagihan ini milik user yang sedang login
        if (!$siswa || !$tagihan || $tagihan->id_pendaftaran != $siswa->id_pendaftaran) {
            return redirect()->back()->with('error', 'Data tagihan tidak valid.');
        }

        // 3. Logika Perhitungan (Total - Bayar = Sisa)
        $nominal_bayar = $request->nominal_bayar;
        $sisa_baru = $tagihan->sisa_tagihan - $nominal_bayar;

        // Proteksi: Jangan biarkan sisa menjadi minus (melebihi tagihan)
        if ($sisa_baru < 0) {
            return redirect()->back()->with('error', 'Nominal bayar melebihi sisa tagihan. Sisa Anda: Rp. ' . number_format($tagihan->sisa_tagihan, 0, ',', '.'));
        }

        // 4. Proses Simpan dengan Transaction
        DB::beginTransaction();
        try {
            $filePaths = $this->uploadDocuments($request);

            // A. Simpan ke riwayat pembayaran
            Pembayaran::create([
                'tagihan_id' => $tagihan->id,
                'nominal_bayar' => $nominal_bayar,
                'tanggal_bayar' => now(),
                'keterangan_cicilan' => ($sisa_baru == 0) ? 'Pelunasan' : 'Cicilan',
                'bukti_transfer' => $filePaths['bukti_transfer'],
                'status_konfirmasi' => 'Menunggu Verifikasi',
            ]);

            // B. Update data di tabel Tagihan (Sisa & Status)
            $tagihan->update([
                'sisa_tagihan' => $sisa_baru,
                'status_pembayaran' => ($sisa_baru == 0) ? 'Lunas' : 'Belum Lunas'
            ]);

            DB::commit();

            $pesan = ($sisa_baru == 0)
                ? 'Terima kasih! Pembayaran Anda sudah LUNAS.'
                : 'Pembayaran berhasil dikirim. Sisa tagihan Anda: Rp. ' . number_format($sisa_baru, 0, ',', '.');

            return redirect()->route('pembayaran.index')->with('success', $pesan);

        } catch (\Exception $e) {
            DB::rollback();
            if (isset($filePaths['bukti_transfer'])) {
                Storage::disk('public')->delete($filePaths['bukti_transfer']);
            }
            return redirect()->back()->with('error', 'Terjadi kesalahan sistem.');
        }
    }
    /**
     * Helper Upload (Meniru gaya pendaftaran Anda agar konsisten)
     */
    protected function uploadDocuments(Request $request): array
    {
        $filePaths = [];
        foreach ($this->documentFields as $field) {
            if ($request->hasFile($field)) {
                $file = $request->file($field);
                $originalName = $file->getClientOriginalName();
                $fileNameToStore = time() . '_' . $originalName;

                // Disimpan di folder 'bukti_pembayaran' di dalam disk public
                $path = $file->storeAs('bukti_pembayaran', $fileNameToStore, 'public');
                $filePaths[$field] = $path;
            }
        }
        return $filePaths;
    }
}