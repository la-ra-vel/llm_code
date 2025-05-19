<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class CustomInput extends Component
{
    public $name;
    public $type;
    public $value;
    public $label;
    public $placeholder;
    public $class;
    public $required;
    public $id;
    public $mdClass;
    public $readonly;
    /**
     * Create a new component instance.
     */
    public function __construct($name, $type = 'text', $value = '', $label = null, $placeholder = '', $class = '', $required = false, $id = '',$mdClass = '', $readonly = false)
    {
        $this->name = $name;
        $this->type = $type;
        $this->value = $value;
        $this->label = $label;
        $this->placeholder = $placeholder;
        $this->class = $class;
        $this->required = $required;
        $this->id = $id;
        $this->mdClass = $mdClass;
        $this->readonly = $readonly;


    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.custom-input');
    }
}
