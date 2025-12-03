<?php

namespace App\Http\Controllers;

use App\Models\KategoriKonten;
use Illuminate\Http\Request;

class LandingPageController extends Controller
{
    /**
     * Tampilkan landing page dengan semua data konten.
     */
    public function index()
    {
        // 1. Beranda (Tetap ambil 1 foto utama)
        $berandaContent = KategoriKonten::where('nama', 'Beranda') 
            ->with(['konten.media' => function ($query) { $query->orderBy('urutan', 'asc'); }])
            ->first();
        $kontenBeranda = $berandaContent && $berandaContent->konten->isNotEmpty() ? $berandaContent->konten->first() : null;
        
        // 2. Tentang Sekolah (Tetap)
        $tentangSekolahContent = KategoriKonten::where('nama', 'Tentang Sekolah')->with('konten')->first();
        $kontenTentangSekolah = $tentangSekolahContent ? $tentangSekolahContent->konten : collect();

        // 3. PPDB (Tetap ambil 1 foto utama jika ada)
        $ppdbContent = KategoriKonten::where('nama', 'LIKE', '%PPDB%')->with('konten')->first();
        $kontenPPDB = $ppdbContent && $ppdbContent->konten->isNotEmpty() ? $ppdbContent->konten->first() : null;

        // 4. Tenaga Pengajar (Tetap ambil 1 foto utama per guru)
        $tenagaPengajarContent = KategoriKonten::where('nama', 'Tenaga Pengajar')
            ->with(['konten.media' => function ($query) { $query->where('urutan', 0); }]) // Ambil foto profil saja
            ->first();
        $kontenTenagaPengajar = $tenagaPengajarContent ? $tenagaPengajarContent->konten : collect();

        // =================================================================
        // 5. PERBAIKAN DI SINI (EKSTRAKURIKULER)
        // =================================================================
        $ekstrakurikulerContent = KategoriKonten::where('nama', 'Ekstrakurikuler')
            ->with(['konten' => function ($query) {
                $query->orderBy('urutan', 'asc');
            }, 'konten.media' => function ($query) {
                $query->orderBy('urutan', 'asc'); 
                // HAPUS limit(1) dan where('urutan', 0) AGAR SEMUA FOTO DIAMBIL
            }])
            ->first();

        $kontenEkstrakurikuler = $ekstrakurikulerContent ? $ekstrakurikulerContent->konten : collect();

        return view('welcome', compact(
            'kontenBeranda', 'kontenTentangSekolah', 'kontenPPDB', 'kontenTenagaPengajar', 'kontenEkstrakurikuler'
        ));
    }
}