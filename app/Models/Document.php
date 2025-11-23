<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Document extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang terhubung dengan model.
     * @var string
     */
    protected $table = 'documents';

    /**
     * Atribut yang dapat diisi secara massal.
     * @var array<int, string>
     */
    protected $fillable = [
        'kategori_document_id',
        'judul',
        'cover',
        'file',
        'lainnya',
        'download'
    ];

    /**
     * PERBAIKAN: Atribut yang harus di-cast ke tipe data tertentu.
     * @var array<string, string>
     */
    protected $casts = [
        'lainnya' => 'array',
    ];

    /**
     * Mendefinisikan relasi ke model KategoriDocument.
     */
    public function kategori(): BelongsTo
    {
        return $this->belongsTo(KategoriDocument::class, 'kategori_document_id');
    }
}
