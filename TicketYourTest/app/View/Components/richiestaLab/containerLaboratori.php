<?php

namespace App\View\Components\richiestaLab;

use App\Models\Laboratorio;
use Illuminate\View\Component;

class containerLaboratori extends Component
{
    public $laboratorio;
    public $id;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($laboratorio)
    {
        $this->laboratorio = $laboratorio;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.richiesta-lab.container-laboratori');
    }
}
