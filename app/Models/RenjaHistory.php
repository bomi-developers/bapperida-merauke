<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RenjaHistory extends Model
{
    protected $guarded = ['id'];

    public function renja()
    {
        return $this->belongsTo(RenjaDocument::class, 'renja_document_id');
    }
}
