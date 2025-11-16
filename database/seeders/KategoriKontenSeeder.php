<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\KategoriKonten;

class KategoriKontenSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            [
                'nama' => 'Beranda',
                'deskripsi' => 'Konten utama pada halaman landing page',
                'urutan' => 1
            ],
            [
                'nama' => 'Tentang Sekolah',
                'deskripsi' => 'Informasi tentang profil sekolah',
                'urutan' => 2
            ],
            [
                'nama' => 'Ekstrakurikuler',
                'deskripsi' => 'Daftar kegiatan ekstrakurikuler sekolah',
                'urutan' => 3
            ],
            [
                'nama' => 'Tenaga Pengajar',
                'deskripsi' => 'Guru dan staf pengajar sekolah',
                'urutan' => 4
            ],
            [
                'nama' => 'Informasi PPDB',
                'deskripsi' => 'Informasi pendaftaran peserta didik baru',
                'urutan' => 5
            ],
        ];

        foreach ($data as $item) {
            KategoriKonten::create($item);
        }
    }
}
