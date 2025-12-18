<?php

// database/migrations/YYYY_MM_DD_HHMMSS_create_pembayaran_table.php

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
        Schema::create('pembayaran', function (Blueprint $table) {
            $table->id();
            // Foreign Key ke tabel 'tagihan'
            $table->foreignId('tagihan_id')->constrained('tagihan')->onDelete('cascade');
            
            $table->decimal('nominal_bayar', 10, 2);
            $table->dateTime('tanggal_bayar');
            $table->string('keterangan_cicilan', 100); // e.g., 'Cicilan ke-1', 'Pelunasan'
            $table->string('bukti_transfer')->nullable(); // Path/URL file bukti
            // Status konfirmasi oleh admin
            $table->enum('status_konfirmasi', ['Menunggu Verifikasi', 'Dikonfirmasi', 'Ditolak'])->default('Menunggu Verifikasi'); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembayaran');
    }
};