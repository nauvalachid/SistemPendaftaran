<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   public function up()
{
    Schema::table('konten', function (Blueprint $table) {
        // Menambahkan kolom sub_judul setelah kolom judul
        $table->string('sub_judul')->nullable()->after('judul'); 
    });
}

public function down()
{
    Schema::table('konten', function (Blueprint $table) {
        $table->dropColumn('sub_judul');
    });
}
};
