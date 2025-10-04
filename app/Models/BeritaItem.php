<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BeritaItem extends Model
{
    protected $table  = 'berita_items';
    protected $fillable = ['berita_id', 'type', 'content', 'position'];

    public function berita()
    {
        return $this->belongsTo(Berita::class);
    }
}
