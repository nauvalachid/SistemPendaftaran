<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('konten', function (Blueprint $table) {
            $table->id();

            $table->foreignId('kategori_konten_id')
                  ->constrained('kategori_konten')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');

            $table->string('judul', 255);
            $table->longText('isi')->nullable();
            $table->integer('urutan')->default(0);
            $table->timestamps();

            $table->index('kategori_konten_id');
            $table->index('urutan');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('konten');
    }
};
