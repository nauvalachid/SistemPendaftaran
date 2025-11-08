<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pendaftaran extends Model
{
    use HasFactory;

    // Nama tabel sesuai dengan migrasi
    protected $table = 'pendaftaran'; 
    
    // Primary key sesuai dengan migrasi
    protected $primaryKey = 'id_pendaftaran';

    /**
     * The attributes that are mass assignable.
     * Mengizinkan pengisian massal untuk semua kolom non-foreign key dan non-dokumen yang akan diisi
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id_user',
        'nama_siswa',
        'tempat_tgl_lahir',
        'jenis_kelamin',
        'agama',
        'asal_sekolah',
        'alamat',
        'status',
        
        // Atribut Orang Tua
        'nama_ayah',
        'nama_ibu',
        'pendidikan_terakhir_ayah',
        'pendidikan_terakhir_ibu',
        'pekerjaan_ayah',
        'pekerjaan_ibu',

        // Atribut Dokumen (hanya nama file/path)
        'kk',
        'akte',
        'foto',
        'ijazah_sk',
        'bukti_bayar',
    ];

    /**
     * Relasi many-to-one ke User (siswa yang mendaftar).
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }
}
