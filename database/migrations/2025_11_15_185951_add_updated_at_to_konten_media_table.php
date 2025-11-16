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
        Schema::table('konten_media', function (Blueprint $table) {
            // Menambahkan kolom updated_at sebagai timestamp, yang dapat bernilai kosong (nullable)
            $table->timestamp('updated_at')->nullable()->after('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('konten_media', function (Blueprint $table) {
            $table->dropColumn('updated_at');
        });
    }
};