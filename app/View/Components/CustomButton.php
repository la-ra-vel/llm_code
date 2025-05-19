<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class CustomButton extends Component
{
    public $name;
    public $type;
    public $class;
    public $icon;
    public $mdClass;
    public $btnColor;
    /**
     * Create a new component instance.
     */
    public function __construct($name = '', $type= '', $class = '', $icon = '', $mdClass = 'col-md-3', $btnColor='primary')
    {
        $this->name = $name;
        $this->type = $type;
        $this->class = $class;
        $this->icon = $icon;
        $this->mdClass = $mdClass;
        $this->btnColor = $btnColor;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.custom-button');
    }
}
