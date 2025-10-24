<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo; // Import BelongsTo

class GaleriItem extends Model
{
    use HasFactory;

    protected $table = 'galeri_items';

    protected $fillable = [
        'galeri_id',
        'file_path',
        'tipe_file',
        'caption',
    ];
    
    public function galeri(): BelongsTo
    {
        return $this->belongsTo(Galeri::class);
    }
}
