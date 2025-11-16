<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('konten_list', function (Blueprint $table) {
            $table->id();

            $table->foreignId('konten_id')
                  ->constrained('konten')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');

            $table->string('meta_key', 100);
            $table->text('meta_value')->nullable();

            $table->unique(['konten_id', 'meta_key'], 'unique_konten_list');
            $table->index('konten_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('konten_list');
    }
};
