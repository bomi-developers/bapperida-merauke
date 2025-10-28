<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ProposalInovasi extends Model
{
    use HasFactory;

    protected $table = 'proposal_inovasi';

    protected $fillable = [
        'uuid',
        'nama',
        'judul',
        'latar_belakang',
        'email',
        'no_hp',
        'link_video',
        'file',
        'email_verified',
        'status',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = (string) Str::uuid();
            }
        });
    }
}
