<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Testing\Fluent\Concerns\Has;

class WebsiteSetting extends Model
{
    use HasFactory;
    protected $table = 'website_settings';
    protected $guaded = ['id'];
}