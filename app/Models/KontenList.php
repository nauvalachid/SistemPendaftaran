<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KontenList extends Model
{
    use HasFactory;

    protected $table = 'konten_list';

    protected $fillable = [
        'konten_id',
        'meta_key',
        'meta_value',
    ];

    public function konten()
    {
        return $this->belongsTo(Konten::class, 'konten_id');
    }
}
