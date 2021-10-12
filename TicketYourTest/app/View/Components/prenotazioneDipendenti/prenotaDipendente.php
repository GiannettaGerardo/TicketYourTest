<?php

namespace App\View\Components\prenotazioneDipendenti;

use Illuminate\View\Component;

class prenotaDipendente extends Component
{
    public $dipendenti;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($dipendenti)
    {
        $this->dipendenti = $dipendenti;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.prenotazione-dipendenti.prenota-dipendente');
    }
}
