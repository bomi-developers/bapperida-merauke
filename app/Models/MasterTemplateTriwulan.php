<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterTemplateTriwulan extends Model
{
    protected $table = 'master_templates_triwulans';
    protected $guarded = ['id'];

    protected $casts = [
        'is_active' => 'boolean',
        'slot' => 'integer',
    ];

    /**
     * Scope: ambil template aktif berdasarkan slot
     */
    public function scopeActiveBySlot($query, $slot)
    {
        return $query->where('is_active', true)->where('slot', $slot);
    }

    /**
     * Ambil semua template aktif (1 per slot, max 3)
     */
    public static function getAllActive()
    {
        return static::where('is_active', true)
            ->whereIn('slot', [1, 2, 3])
            ->orderBy('slot')
            ->get()
            ->keyBy('slot');
    }
}
