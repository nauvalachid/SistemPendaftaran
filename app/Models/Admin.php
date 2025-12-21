<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable; // PENTING: Import Authenticatable
use Illuminate\Notifications\Notifiable;

class Admin extends Authenticatable
{
    use HasFactory, Notifiable;

    // Definisikan nama tabel yang benar sesuai skema database Anda
    protected $table = 'admin';
    
    // Definisikan Primary Key jika namanya bukan 'id'
    protected $primaryKey = 'id_admin';

    protected $guard = 'admin';

    // Definisikan field yang bisa diisi (Fillable)
    protected $fillable = [
        'nama',
        'username',
        'password',
    ];

    // Field yang disembunyikan (Hidden) saat di-serialize
    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Satu Admin bisa me-review banyak pendaftaran
    public function pendaftaran()
    {
        return $this->hasMany(Pendaftaran::class, 'id_admin', 'id_admin');
    }
}