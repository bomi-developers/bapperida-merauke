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
    public $value;

    public function __construct($label = '', $id = '', $name = '', $type = 'text', $value = '')
    {
        $this->label = $label;
        $this->id = $id ?: $name;
        $this->name = $name;
        $this->type = $type;
        $this->value = $value;
    }

    public function render()
    {
        return view('components.input');
    }
}