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
        Schema::create('pendaftaran', function (Blueprint $table) {
            $table->id('id_pendaftaran');
            
            // Foreign Keys
            $table->foreignId('id_user')->nullable()->constrained('users', 'id_user')->onDelete('set null'); // M:1 dengan User
            $table->foreignId('id_admin')->nullable()->constrained('admin', 'id_admin')->onDelete('set null'); // M:1 dengan Admin
            
            // Atribut Siswa
            $table->string('nama_siswa');
            $table->string('tempat_tgl_lahir');
            $table->enum('jenis_kelamin', ['Laki-laki', 'Perempuan']);
            $table->string('agama');
            $table->string('asal_sekolah');
            $table->text('alamat');

            // Atribut Orang Tua
            $table->string('nama_ayah')->nullable();
            $table->string('nama_ibu')->nullable();
            $table->string('pendidikan_terakhir_ayah')->nullable();
            $table->string('pendidikan_terakhir_ibu')->nullable();
            $table->string('pekerjaan_ayah')->nullable();
            $table->string('pekerjaan_ibu')->nullable();

            // Atribut Dokumen
            $table->string('kk')->nullable();
            $table->string('akte')->nullable();
            $table->string('foto')->nullable();
            $table->string('ijazah_sk')->nullable();

            // Atribut Transaksi
            $table->string('bukti_bayar')->nullable(); // Atribut yang menghubungkan ke User melalui relasi "melakukan"
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pendaftaran');
    }
};