<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tagihan extends Model
{
    use HasFactory;

    protected $table = 'tagihan';
    
    protected $fillable = [
        'id_pendaftaran',
        'total_tagihan',
        'sisa_tagihan',
        'status_pembayaran',
    ];

    // Relasi: Satu Tagihan dimiliki oleh satu Pendaftaran/Siswa
    // ASUMSI: Model Pendaftaran Anda bernama 'Pendaftaran'
    public function pendaftaran()
    {
        return $this->belongsTo(Pendaftaran::class, 'id_pendaftaran', 'id_pendaftaran');
    }

    // Relasi: Satu Tagihan memiliki banyak Pembayaran (Cicilan)
    public function pembayaran()
    {
        return $this->hasMany(Pembayaran::class, 'tagihan_id', 'id');
    }
}