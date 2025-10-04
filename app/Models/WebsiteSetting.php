<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class WebsiteSetting extends Model
{
    use HasFactory, LogsActivity;

    protected $table = 'website_settings';
    protected $guarded = ['id']; // typo fixed

    protected static $logName = 'Website Setting';

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName(self::$logName)
            ->logOnly(['title', 'content'])
            ->setDescriptionForEvent(function (string $eventName) {
                $user = Auth::check() ? Auth::user()->name : 'system';
                return "Website Setting {$eventName} by {$user}";
            });
    }
}