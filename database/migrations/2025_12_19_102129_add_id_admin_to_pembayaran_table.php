<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('pembayaran', function (Blueprint $table) {
            // Gunakan constrained('admins') karena tabel referensinya adalah 'admins'
            $table->foreignId('id_admin')
                ->nullable()
                ->after('status_konfirmasi')
                ->constrained('admin')
                ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('pembayaran', function (Blueprint $table) {
            $table->dropForeign(['id_admin']);
            $table->dropColumn('id_admin');
        });
    }
};