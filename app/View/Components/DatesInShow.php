<?php

namespace App\View\Components;

use Illuminate\View\Component;

class DatesInShow extends Component
{
    public $model;

    /**
     * Create a new component instance.
     *
     * @param object $model The model instance
     */
    public function __construct($model)
    {
        $this->model = $model;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.dates-in-show');
    }
}