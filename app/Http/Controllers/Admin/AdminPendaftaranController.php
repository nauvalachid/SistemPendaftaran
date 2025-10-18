<?php

namespace App\Http\Controllers\Admin; // PENTING: Gunakan namespace Admin

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
    public function index()
    {
        // Ambil semua data pendaftaran, urutkan dari yang terbaru, dan sertakan data User
        $pendaftarans = Pendaftaran::with('user')->latest()->paginate(15); 
        
        return view('admin.data_pendaftar', compact('pendaftarans'));
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
     * Mengunduh file dokumen dari pendaftaran.
     * URL: /admin/pendaftaran/{id}/download/{field}
     */
    public function download(Pendaftaran $pendaftaran, string $field)
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

        // 3. Paksa Unduh File
        return Storage::disk('public')->download($filePath);
    }

    // Fungsi create, store, edit, update, destroy (yang tidak perlu) tidak didefinisikan
}