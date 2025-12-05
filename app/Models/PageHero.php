<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PageHero extends Model
{
    use HasFactory;

    protected $table = 'page_heroes';

    // Tambahkan ini agar bisa di-input sekaligus
    protected $fillable = [
        'route_name',
        'hero_bg'
    ];
}
