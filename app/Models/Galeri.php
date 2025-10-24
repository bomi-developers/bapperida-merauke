<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany; // Import HasMany

class Galeri extends Model
{
    use HasFactory;

    protected $table = 'galeris';

    /**
     * Atribut yang dapat diisi secara massal.
     * Kolom file_path dan tipe_file dihapus dari sini.
     */
    protected $fillable = [
        'judul',
        'keterangan',
    ];

    /**
     * Relasi one-to-many ke GaleriItem.
     */
    public function items(): HasMany
    {
        // Urutkan berdasarkan ID (asumsi ID = urutan penambahan)
        return $this->hasMany(GaleriItem::class)->orderBy('id');
    }

    /**
     * 2. Relasi one-to-one ke item pertama (berdasarkan ID terendah).
     */
    public function firstItem(): HasOne
    {
        // Mengambil item dengan ID terendah yang terkait dengan galeri ini
        return $this->hasOne(GaleriItem::class)->oldestOfMany();
    }
}
