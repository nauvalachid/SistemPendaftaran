<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    // Pastikan nama tabel benar
    protected $table = 'users';

    // Primary Key sesuai screenshot Anda
    protected $primaryKey = 'id_user';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'username',      // KITA PAKAI INI (Hapus 'email' dari sini)
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            // 'email_verified_at' => 'datetime', // HAPUS BARIS INI (Karena kolom email tidak ada)
            'password' => 'hashed',
        ];
    }

    public function pendaftaran()
    {
        // Pastikan foreign key didefinisikan jika namanya bukan 'id' standard
        return $this->hasOne(Pendaftaran::class, 'id_user', 'id_user');
    }
}