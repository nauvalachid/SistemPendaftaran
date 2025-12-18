<?php

// database/migrations/YYYY_MM_DD_HHMMSS_create_tagihan_table.php

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
        Schema::create('tagihan', function (Blueprint $table) {
            $table->id();
            // id_pendaftaran sebagai Foreign Key ke tabel pendaftaran/users
            // Ganti 'users' menjadi nama tabel pendaftaran/siswa yang sebenarnya jika berbeda
            $table->foreignId('id_pendaftaran')->constrained('pendaftaran', 'id_pendaftaran')->onDelete('cascade');
            
            $table->decimal('total_tagihan', 10, 2);
            $table->decimal('sisa_tagihan', 10, 2);
            // Status: Lunas, Belum Lunas, Dicicil
            $table->enum('status_pembayaran', ['Lunas', 'Belum Lunas', 'Dicicil'])->default('Belum Lunas');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tagihan');
    }
};