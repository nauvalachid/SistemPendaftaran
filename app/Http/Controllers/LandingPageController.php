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
        // 1. Ambil konten untuk bagian Beranda (Hero Section).
        $berandaContent = KategoriKonten::where('nama', 'Beranda') 
            ->with(['konten' => function ($query) {
                $query->orderBy('urutan', 'asc')->limit(1);
            }, 'konten.media' => function ($query) {
                $query->orderBy('urutan', 'asc');
            }])
            ->first();
            
        $kontenBeranda = $berandaContent && $berandaContent->konten->isNotEmpty() 
                             ? $berandaContent->konten->first() 
                             : null;
        
        // 2. Ambil konten untuk bagian Tentang Sekolah (Sejarah, Visi, Misi).
        $tentangSekolahContent = KategoriKonten::where('nama', 'Tentang Sekolah')
            ->with(['konten' => function ($query) {
                $query->orderBy('urutan', 'asc');
            }])
            ->first();

        $kontenTentangSekolah = $tentangSekolahContent
                                ? $tentangSekolahContent->konten 
                                : collect();

        // 3. Ambil konten untuk bagian PPDB (Penerimaan Peserta Didik Baru).
        // Kita asumsikan PPDB adalah konten tunggal (seperti Beranda).
        $ppdbContent = KategoriKonten::where('nama', 'PPDB')
            ->with(['konten' => function ($query) {
                $query->orderBy('urutan', 'asc')->limit(1);
            }])
            ->first();

        $kontenPPDB = $ppdbContent && $ppdbContent->konten->isNotEmpty()
                                ? $ppdbContent->konten->first()
                                : null;

        // 4. Ambil konten untuk bagian Tenaga Pengajar (Daftar Guru).
        // Kita asumsikan Tenaga Pengajar adalah koleksi item.
        $tenagaPengajarContent = KategoriKonten::where('nama', 'Tenaga Pengajar')
            ->with(['konten' => function ($query) {
                $query->orderBy('urutan', 'asc');
            }, 'konten.media' => function ($query) {
                // Preload media utama untuk foto guru
                $query->where('urutan', 0)->limit(1);
            }])
            ->first();

        $kontenTenagaPengajar = $tenagaPengajarContent
                                ? $tenagaPengajarContent->konten 
                                : collect();

        // 5. Ambil konten untuk bagian Ekstrakurikuler.
        // Kita asumsikan Ekstrakurikuler adalah koleksi item.
        $ekstrakurikulerContent = KategoriKonten::where('nama', 'Ekstrakurikuler')
            ->with(['konten' => function ($query) {
                $query->orderBy('urutan', 'asc');
            }, 'konten.media' => function ($query) {
                // Preload media utama untuk gambar ekstrakurikuler
                $query->where('urutan', 0)->limit(1);
            }])
            ->first();

        $kontenEkstrakurikuler = $ekstrakurikulerContent
                                ? $ekstrakurikulerContent->konten 
                                : collect();

        // 6. Kirim semua variabel ke View.
        return view('welcome', compact(
            'kontenBeranda', 
            'kontenTentangSekolah', 
            'kontenPPDB', 
            'kontenTenagaPengajar', 
            'kontenEkstrakurikuler' // <-- Tambahan variabel baru
        ));
    }
}