<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TataUsaha;
use Illuminate\Support\Facades\Hash;

class TataUsahaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Menghapus data lama agar tidak duplikat saat dijalankan ulang
        // TataUsaha::truncate(); 

        TataUsaha::create([
            'nama'      => 'Staff TU',
            'username'  => 'tu_sekolah',
            'password'  => Hash::make('password'), // Ganti dengan password yang diinginkan
        ]);

        $this->command->info('Akun Tata Usaha berhasil ditambahkan!');
    }
}