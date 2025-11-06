<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Textarea extends Component
{
    public $label;
    public $id;
    public $name;
    public $rows;
    public $textarea;

    public function __construct($label = '', $id = '', $name = '', $rows = 3, $textarea = '')
    {
        $this->label = $label;
        $this->id = $id ?: $name;
        $this->name = $name;
        $this->rows = $rows;
        $this->rows = $textarea;
    }

    public function render()
    {
        return view('components.textarea');
    }
}