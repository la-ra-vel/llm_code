<?php

namespace App\View\Components;

use Auth;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ProfilePicture extends Component
{

    public $mdClass;
    public $name;
    public $ajax;
    public $user;

    /**
     * Create a new component instance.
     */
    public function __construct($mdClass = '', $name, $ajax= false, $user = [])
    {
        $this->user = Auth::guard('web')->user();
        $this->mdClass = $mdClass;
        $this->name = $name;
        $this->ajax = $ajax;
        $this->user = $user;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.profile-picture');
    }
}
