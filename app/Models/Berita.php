<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Berita extends Model
{
    protected $table  = 'berita';
    protected $fillable = ['title', 'slug'];

    public function items()
    {
        return $this->hasMany(BeritaItem::class)->orderBy('position');
    }
}
