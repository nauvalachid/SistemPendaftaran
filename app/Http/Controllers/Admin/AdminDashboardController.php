<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// Ganti dengan nama model Pendaftaran Anda yang sebenarnya jika berbeda
use App\Models\Pendaftaran; 

class AdminDashboardController extends Controller
{
    /**
     * Menghitung total Pendaftaran berdasarkan status dan menampilkan dashboard.
     */
    public function index()
    {
        // 1. Hitung total SEMUA Pendaftaran (Total)
        $totalPendaftaran = Pendaftaran::count();

        // 2. Hitung Pendaftaran dengan status 'diterima'
        $diterimaCount = Pendaftaran::where('status', 'diterima')->count();

        // 3. Hitung Pendaftaran dengan status 'ditolak'
        $ditolakCount = Pendaftaran::where('status', 'ditolak')->count();

        // 4. Hitung Pendaftaran dengan status 'pending'
        $pendingCount = Pendaftaran::where('status', 'pending')->count();
        
        // Catatan: Jika Anda menggunakan status lain (misalnya 'terbayar', 'proses'), 
        // Anda bisa menambahkannya di sini.

        // Mengirimkan semua data perhitungan ke view dashboard admin
        return view('admin.dashboard', [
            'totalPendaftaran' => $totalPendaftaran,
            'diterimaCount' => $diterimaCount,
            'ditolakCount' => $ditolakCount,
            'pendingCount' => $pendingCount,
        ]);
    }
}