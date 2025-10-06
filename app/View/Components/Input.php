<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Input extends Component
{
    public $label;
    public $id;
    public $name;
    public $type;

    public function __construct($label = '', $id = '', $name = '', $type = 'text')
    {
        $this->label = $label;
        $this->id = $id ?: $name;
        $this->name = $name;
        $this->type = $type;
    }

    public function render()
    {
        return view('components.input');
    }
}
