<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class WidgetCard extends Component
{
    public $mdClass;
    public $customClass;
    public $id;
    public $title;
    public $value;
    public $icon;
    /**
     * Create a new component instance.
     */
    public function __construct($mdClass = '', $customClass = '', $id = '', $title = '', $value = '', $icon = '')
    {
        $this->mdClass = $mdClass;
        $this->customClass = $customClass;
        $this->id = $id;
        $this->title = $title;
        $this->value = $value;
        $this->icon = $icon;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.widget-card');
    }
}
