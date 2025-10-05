<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pegawai extends Model
{
    protected $fillable = [
        'nama',
        'nip',
        'nik',
        'alamat',
        'id_jabatan',
        'id_golongan'
    ];

    public function jabatan()
    {
        return $this->belongsTo(Jabatan::class, 'id_jabatan');
    }

    public function golongan()
    {
        return $this->belongsTo(Golongan::class, 'id_golongan');
    }
}
