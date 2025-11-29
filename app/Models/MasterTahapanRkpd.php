<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterTahapanRkpd extends Model
{
    protected $guarded = ['id'];

    public function renjas()
    {
        return $this->hasMany(RenjaDocument::class, 'tahapan_id');
    }
}