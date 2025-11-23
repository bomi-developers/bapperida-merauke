<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TriwulanPeriod extends Model
{
    protected $guarded = ['id'];

    public function laporans()
    {
        return $this->hasMany(LaporanTriwulan::class, 'period_id');
    }
}