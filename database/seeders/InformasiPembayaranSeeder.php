<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InformasiPembayaranSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tahun_ajaran = '2026/2027';

        // Data untuk SISWA PUTRI
        $data_putri = [
            ['uraian' => 'Biaya Seragam', 'jenis_kelamin' => 'Putri', 'jumlah_biaya' => 925000.00, 'tahun_ajaran' => $tahun_ajaran],
            ['uraian' => 'Biaya SPP per Semester', 'jenis_kelamin' => 'Putri', 'jumlah_biaya' => 450000.00, 'tahun_ajaran' => $tahun_ajaran],
            ['uraian' => 'Biaya Kegiatan per Semester', 'jenis_kelamin' => 'Putri', 'jumlah_biaya' => 525000.00, 'tahun_ajaran' => $tahun_ajaran],
            ['uraian' => 'Biaya Buku per Semester', 'jenis_kelamin' => 'Putri', 'jumlah_biaya' => 250000.00, 'tahun_ajaran' => $tahun_ajaran],
            ['uraian' => 'Biaya Dana Sehat Muhammadiyah per tahun', 'jenis_kelamin' => 'Putri', 'jumlah_biaya' => 65000.00, 'tahun_ajaran' => $tahun_ajaran],
            ['uraian' => 'Infaq Pengembangan Sekolah', 'jenis_kelamin' => 'Putri', 'jumlah_biaya' => 100000.00, 'tahun_ajaran' => $tahun_ajaran],
            ['uraian' => 'Infaq Pembangunan Masjid', 'jenis_kelamin' => 'Putri', 'jumlah_biaya' => 300000.00, 'tahun_ajaran' => $tahun_ajaran],
        ];

        // Data untuk SISWA PUTRA
        $data_putra = [
            ['uraian' => 'Biaya Seragam', 'jenis_kelamin' => 'Putra', 'jumlah_biaya' => 800000.00, 'tahun_ajaran' => $tahun_ajaran],
            ['uraian' => 'Biaya SPP per Semester', 'jenis_kelamin' => 'Putra', 'jumlah_biaya' => 450000.00, 'tahun_ajaran' => $tahun_ajaran],
            ['uraian' => 'Biaya Kegiatan per Semester', 'jenis_kelamin' => 'Putra', 'jumlah_biaya' => 525000.00, 'tahun_ajaran' => $tahun_ajaran],
            ['uraian' => 'Biaya Buku per Semester', 'jenis_kelamin' => 'Putra', 'jumlah_biaya' => 250000.00, 'tahun_ajaran' => $tahun_ajaran],
            ['uraian' => 'Biaya Dana Sehat Muhammadiyah per tahun', 'jenis_kelamin' => 'Putra', 'jumlah_biaya' => 65000.00, 'tahun_ajaran' => $tahun_ajaran],
            ['uraian' => 'Infaq Pengembangan Sekolah', 'jenis_kelamin' => 'Putra', 'jumlah_biaya' => 100000.00, 'tahun_ajaran' => $tahun_ajaran],
            ['uraian' => 'Infaq Pembangunan Masjid', 'jenis_kelamin' => 'Putra', 'jumlah_biaya' => 300000.00, 'tahun_ajaran' => $tahun_ajaran],
        ];
        
        // Catatan: Biaya Seragam Putri (925rb) vs Putra (800rb). Total Tagihan Putri: 2.615.000, Putra: 2.490.000
        
        DB::table('informasi_pembayaran')->insert(array_merge($data_putri, $data_putra));
    }
}