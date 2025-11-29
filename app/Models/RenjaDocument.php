<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RenjaDocument extends Model
{
    protected $guarded = ['id'];

    public function tahapan()
    {
        return $this->belongsTo(MasterTahapanRkpd::class, 'tahapan_id');
    }

    public function opd()
    {
        return $this->belongsTo(User::class, 'opd_id');
    }
    
    public function histories()
    {
        return $this->hasMany(RenjaHistory::class, 'renja_document_id')->latest();
    }
}