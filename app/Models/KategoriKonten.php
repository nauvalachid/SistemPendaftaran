<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KategoriKonten extends Model
{
    use HasFactory;

    protected $table = 'kategori_konten';
     protected $primaryKey = 'id';

    protected $fillable = [
        'nama',
        'deskripsi',
        'urutan',
    ];

    public function konten()
    {
        return $this->hasMany(Konten::class, 'kategori_konten_id');
    }
}
