<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InformasiPembayaran extends Model
{
    use HasFactory;

    protected $table = 'informasi_pembayaran';
    
    // Kolom yang dapat diisi secara massal
    protected $fillable = [
        'uraian',
        'jenis_kelamin',
        'jumlah_biaya',
        'tahun_ajaran',
    ];
}