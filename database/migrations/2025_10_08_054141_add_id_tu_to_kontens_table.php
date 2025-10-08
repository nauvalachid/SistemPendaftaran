<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('konten', function (Blueprint $table) {
            // 1. Tambahkan kolom id_tu
            // Kolom ini dibuat nullable karena Admin (id_admin) juga bisa mengelola Konten.
            $table->foreignId('id_tu')
                  ->nullable()
                  ->after('id_admin') // Letakkan setelah id_admin untuk kerapian
                  ->constrained('tata_usaha', 'id_tu')
                  ->onDelete('set null'); // Jika Tata Usaha dihapus, kontennya tidak ikut terhapus, hanya nilai di sini yang diset ke NULL.
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('konten', function (Blueprint $table) {
            // Hapus foreign key terlebih dahulu
            $table->dropConstrainedForeignId('id_tu');
            
            // Hapus kolom
            $table->dropColumn('id_tu');
        });
    }
};