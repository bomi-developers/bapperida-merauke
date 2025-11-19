<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PageView extends Model
{
    public $timestamps = false;
    protected $fillable = ['url', 'ip_address', 'user_agent', 'user_id', 'viewed_at'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}