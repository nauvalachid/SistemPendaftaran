<?php

// database/migrations/YYYY_MM_DD_HHMMSS_create_informasi_pembayaran_table.php

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
        Schema::create('informasi_pembayaran', function (Blueprint $table) {
            $table->id();
            $table->string('uraian', 100);
            // ENUM untuk membedakan biaya berdasarkan jenis kelamin
            $table->enum('jenis_kelamin', ['Putra', 'Putri', 'Umum'])->default('Umum'); 
            $table->decimal('jumlah_biaya', 10, 2); // Contoh: 925000.00
            $table->string('tahun_ajaran', 10); // Contoh: 2026/2027
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('informasi_pembayaran');
    }
};