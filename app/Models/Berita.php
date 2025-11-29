<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Berita extends Model
{
    protected $table  = 'berita';
    protected $fillable = ['title', 'slug', 'cover_image', 'excerpt', 'status', 'user_id', 'page'];

    public function items()
    {
        return $this->hasMany(BeritaItem::class)->orderBy('position');
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
