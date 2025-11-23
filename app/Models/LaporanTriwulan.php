<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LaporanTriwulan extends Model
{
    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function period()
    {
        return $this->belongsTo(TriwulanPeriod::class, 'period_id');
    }

    public function histories()
    {
        return $this->hasMany(LaporanHistory::class, 'laporan_id')->latest();
    }
}
