<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class CustomTextarea extends Component
{
    public $name;
    public $label;
    public $placeholder;
    public $value;
    public $id;
    public $rows;
    public $cols;
    public $required;
    public $readonly;
    public $class;
    public $mdClass;
    /**
     * Create a new component instance.
     */
    public function __construct($name, $label = null, $placeholder = null, $value = null, $id = null, $rows = 3, $cols = 50, $required = false, $readonly = false, $class = null, $mdClass = null)
    {
        $this->name = $name;
        $this->label = $label;
        $this->placeholder = $placeholder;
        $this->value = $value;
        $this->id = $id;
        $this->rows = $rows;
        $this->cols = $cols;
        $this->required = $required;
        $this->readonly = $readonly;
        $this->class = $class;
        $this->mdClass = $mdClass;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.custom-textarea');
    }
}
