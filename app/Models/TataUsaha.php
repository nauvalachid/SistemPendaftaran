<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable; // PENTING: Import Authenticatable
use Illuminate\Notifications\Notifiable;

class TataUsaha extends Authenticatable
{
    use HasFactory, Notifiable;

    // Definisikan nama tabel yang benar sesuai skema database Anda
    protected $table = 'tata_usaha';
    
    // Definisikan Primary Key jika namanya bukan 'id'
    protected $primaryKey = 'id_tu';
    
    // Definisikan field yang bisa diisi (Fillable)
    protected $fillable = [
        'nama',
        'username',
        'email',
        'password',
    ];

    // Field yang disembunyikan (Hidden) saat di-serialize
    protected $hidden = [
        'password',
        'remember_token',
    ];
}