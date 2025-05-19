<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class CustomDropdown extends Component
{
    public $name;
    public $options;
    public $label;
    public $selected;
    public $required;
    public $multiple;
    public $mdClass;
    public $id;
    public $customDropdown;
    public $keys;
    /**
     * Create a new component instance.
     */
    public function __construct($name, $options = [], $label = null, $selected = null, $required = false, $multiple = false, $mdClass = '', $id = '', $customDropdown = false, $keys = false)
    {
        $this->name = $name;
        $this->options = $options;
        $this->label = $label;
        $this->selected = $selected;
        $this->required = $required;
        $this->multiple = $multiple;
        $this->mdClass = $mdClass;
        $this->id = $id;
        $this->customDropdown = $customDropdown;
        $this->keys = $keys;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.custom-dropdown');
    }
}
