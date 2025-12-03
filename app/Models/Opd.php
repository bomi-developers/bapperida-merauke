<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Opd extends Model
{
    protected $table = 'opd';
    protected $guarded = [];

    public function user()
    {
        return $this->hasOne(User::class, 'id_opd');
    }
}
