<?php

namespace App\View\Components\dipendenti;

use Illuminate\View\Component;

class containerDip extends Component
{
    public $dipendente;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($dipendente)
    {
        $this->dipendente = $dipendente;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.dipendenti.container-dip');
    }
}
