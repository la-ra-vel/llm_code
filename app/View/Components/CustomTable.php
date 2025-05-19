<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class CustomTable extends Component
{
    public $tableID;
    public $headers;
    public $ajaxUrl;
    public $columns;
    /**
     * Create a new component instance.
     */
    public function __construct($tableID = '',array $headers, string $ajaxUrl, array $columns = [])
    {
        $this->tableID = $tableID;
        $this->headers = $headers;
        $this->ajaxUrl = $ajaxUrl;
        $this->columns = $columns;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.custom-table');
    }
}
