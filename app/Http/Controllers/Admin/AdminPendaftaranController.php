<?php

namespace App\Http\Controllers\Admin; 

use App\Http\Controllers\Controller;
use App\Models\Pendaftaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AdminPendaftaranController extends Controller
{
    /**
     * Array nama kolom file dokumen.
     * @var array
     */
    protected array $documentFields = ['kk', 'akte', 'foto', 'ijazah_sk', 'bukti_bayar'];

    /**
     * Menampilkan daftar semua pendaftaran.
     */
    public function index(Request $request)
    {
        // 1. Inisialisasi query dengan eager loading relasi User
        $pendaftarQuery = Pendaftaran::with('user');

        // 2. Filter Global (Input Cari)
        if ($request->filled('search')) {
            $search = $request->input('search');
            $pendaftarQuery->where(function ($query) use ($search) {
                // Pencarian di Nama Siswa, NISN, dan Asal Sekolah
                $query->where('nama_siswa', 'like', '%' . $search . '%')
                      ->orWhere('nisn', 'like', '%' . $search . '%')
                      ->orWhere('asal_sekolah', 'like', '%' . $search . '%');
            });
        }

        // 3. Filter Status
        if ($request->filled('status') && $request->input('status') !== 'all') {
            $pendaftarQuery->where('status', $request->input('status'));
        }

        // 4. Filter Tanggal Daftar (Berdasarkan created_at)
        if ($request->filled('tanggal_daftar')) {
            // Memfilter berdasarkan tanggal tertentu (YYYY-MM-DD)
            $date = $request->input('tanggal_daftar');
            $pendaftarQuery->whereDate('created_at', $date);
        }

        // 5. Filter Asal Sekolah
        if ($request->filled('asal_sekolah') && $request->input('asal_sekolah') !== 'all') {
            $pendaftarQuery->where('asal_sekolah', $request->input('asal_sekolah'));
        }
        
        // 6. Pengurutan
        $sortBy = $request->input('sort_by', 'latest'); // Default: terbaru
        
        if ($sortBy === 'nama_asc') {
            $pendaftarQuery->orderBy('nama_siswa', 'asc');
        } elseif ($sortBy === 'nama_desc') {
             $pendaftarQuery->orderBy('nama_siswa', 'desc');
        } else {
             // Default: Pengurutan berdasarkan ID terbaru (paling baru mendaftar)
             $pendaftarQuery->latest('id_pendaftaran'); 
        }

        // Ambil data yang sudah difilter dan lakukan pagination
        $pendaftarans = $pendaftarQuery->paginate(15); 
        
        // Ambil daftar unik Asal Sekolah untuk dropdown filter di View
        $list_sekolah = Pendaftaran::select('asal_sekolah')
                                   ->distinct()
                                   ->whereNotNull('asal_sekolah')
                                   ->pluck('asal_sekolah')
                                   ->sort()
                                   ->toArray();

        // Daftar status yang mungkin untuk filter
        $list_status = ['Pending', 'Diterima', 'Ditolak'];

        // Kirim data yang sudah difilter, list filter, dan nilai request saat ini
        // Nilai request (query string) dikirim agar form filter/pencarian tetap terisi
        return view('admin.data_pendaftar', compact('pendaftarans', 'list_sekolah', 'list_status'));
    }


    /**
     * Menampilkan detail lengkap satu pendaftaran.
     */
    public function show(Pendaftaran $pendaftaran)
    {
        // Eager load relasi User untuk menampilkan data pendaftar.
        $pendaftaran->load('user');
        
        return view('admin.detail_pendaftar', compact('pendaftaran'));
    }
    
    /**
     * Mengunduh atau menampilkan file dokumen dari pendaftaran berdasarkan parameter 'action'.
     * URL: /admin/pendaftaran/{id}/download/{field}?action={view/download}
     */
    public function download(Request $request, Pendaftaran $pendaftaran, string $field)
    {
        // 1. Validasi Field: Pastikan field yang diminta ada dalam daftar dokumen yang sah
        if (!in_array($field, $this->documentFields)) {
            abort(404, 'Dokumen yang diminta tidak valid.');
        }

        $filePath = $pendaftaran->$field;
        
        // 2. Cek Keberadaan File: Pastikan path file ada dan file ada di storage
        if (empty($filePath) || !Storage::disk('public')->exists($filePath)) {
            abort(404, 'File tidak ada atau telah dihapus.');
        }

        // Ambil parameter action dari query string (default: download)
        $action = $request->query('action', 'download');

        // 3. Tentukan Aksi (View atau Download)
        if ($action === 'view') {
            // Gunakan response() untuk menampilkan file (browser akan menangani MIME type)
            // Ini akan membuat file seperti PDF atau gambar dibuka di tab baru.
            return Storage::disk('public')->response($filePath);
        }

        // Default: Gunakan download() untuk memaksa unduh
        return Storage::disk('public')->download($filePath);
    }

     public function update(Request $request, $id)
    {
        // Validasi input
        $validated = $request->validate([
            'status' => 'required|in:Pending,Diterima,Ditolak',
        ]);

        // Ambil data pendaftaran
        $pendaftaran = Pendaftaran::findOrFail($id);

        // Update status
        $pendaftaran->status = $validated['status'];
        $pendaftaran->save();

        // Jika request dari AJAX
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Status pendaftaran berhasil diperbarui',
                'status' => $pendaftaran->status,
            ]);
        }

        // Jika dari form biasa
        return redirect()
            ->back()
            ->with('success', 'Status pendaftaran berhasil diubah menjadi ' . $pendaftaran->status);
    }
}
