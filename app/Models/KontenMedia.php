<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KontenMedia extends Model
{
    use HasFactory;

    protected $table = 'konten_media';

    protected $fillable = [
        'konten_id',
        'file_path',
        'file_type',
        'urutan',
    ];

    public function konten()
    {
        return $this->belongsTo(Konten::class, 'konten_id');
    }
}
