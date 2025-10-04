<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoginLog extends Model
{
    protected $fillable = [
        'id_user',
        'ip_address',
        'user_agent',
        'logged_in_at',
        'logged_out_at'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}