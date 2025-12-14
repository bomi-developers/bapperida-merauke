<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LendingPage extends Model
{
    protected $table = 'lending_page';
    protected $guarded = [];

    public function template()
    {
        return $this->belongsTo(Template::class, 'id_template');
    }
}
