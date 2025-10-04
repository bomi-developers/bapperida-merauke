<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProfileDinas extends Model
{
    protected $table = 'profile_dinas';
    protected $fillable = [
        'visi',
        'misi',
        'sejarah',
        'tugas_fungsi',
        'struktur_organisasi'
    ];
}