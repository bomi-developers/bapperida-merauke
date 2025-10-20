<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class KategoriDocument extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang terhubung dengan model.
     * @var string
     */
    protected $table = 'kategori_documents';

    /**
     * Atribut yang dapat diisi secara massal.
     * @var array<int, string>
     */
    protected $fillable = [
        'nama_kategori',
    ];

    public function documents(): HasMany
    {
        return $this->hasMany(Document::class);
    }
}
