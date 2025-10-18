<?php

namespace Database\Seeders;

use App\Models\Admin; // Pastikan Model Admin di-import
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run()
    {
        Admin::create([
            'nama' => 'Super Admin',
            'username' => 'superadmin',
            'email' => 'super@admin.com',
            'password' => Hash::make('password'), // Laravel akan meng-hash ini
        ]);
        // Anda juga bisa perbarui akun yang sudah ada
        // Admin::where('id_admin', 1)->update(['password' => Hash::make('123456')]);
    }
}