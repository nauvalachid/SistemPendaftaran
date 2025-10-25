<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration untuk menambahkan kolom NISN, status, dan no_telp ke tabel 'pendaftaran'.
 * Tanggal dan waktu pada nama file perlu disesuaikan dengan waktu pembuatan Anda.
 */
return new class extends Migration
{
    /**
     * Jalankan migrasi (menambahkan kolom).
     */
    public function up(): void
    {
        // Pastikan tabel 'pendaftaran' ada sebelum menambahkan kolom
        if (Schema::hasTable('pendaftaran')) {
            Schema::table('pendaftaran', function (Blueprint $table) {
                // Kolom nisn (Nomor Induk Siswa Nasional)
                // Diletakkan setelah kolom 'nama'
                $table->string('nisn', 10)->nullable()->after('nama_siswa')->comment('Nomor Induk Siswa Nasional');

                // Kolom no_telp (Nomor telepon/HP)
                // Diletakkan setelah kolom 'nisn' agar berdekatan dengan 'nama'
                $table->string('no_telp', 15)->nullable()->after('nisn')->comment('Nomor telepon/HP pelamar');

                // Kolom status (Status pendaftaran)
                // Diletakkan di akhir tabel (tanpa after() atau akan diletakkan setelah kolom terakhir yang ada)
                $table->enum('status', ['pending', 'diterima', 'ditolak'])->default('pending')->comment('Status pendaftaran');
            });
        }
    }

    /**
     * Batalkan migrasi (menghapus kolom).
     */
    public function down(): void
    {
        // Pastikan tabel 'pendaftaran' ada sebelum menghapus kolom
        if (Schema::hasTable('pendaftaran')) {
            Schema::table('pendaftaran', function (Blueprint $table) {
                // Menghapus ketiga kolom jika migrasi di-rollback
                $table->dropColumn(['nisn', 'status', 'no_telp']);
            });
        }
    }
};
