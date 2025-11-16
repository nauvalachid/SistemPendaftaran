<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('konten_media', function (Blueprint $table) {
            $table->id();

            $table->foreignId('konten_id')
                  ->constrained('konten')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');

            $table->string('file_path', 255);
            $table->string('file_type', 50)->default('image');
            $table->integer('urutan')->default(0);
            $table->timestamp('created_at')->useCurrent();

            $table->index('konten_id');
            $table->index('urutan');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('konten_media');
    }
};
