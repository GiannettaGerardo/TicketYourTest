<?php

namespace App\View\Components\richiestaLab;

use Illuminate\View\Component;

class containeirPrenotazioniLab extends Component
{

    public $prenotazione;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct( $prenotazione )
    {
        $this->prenotazione = $prenotazione;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.richiesta-lab.containeir-prenotazioni-lab');
    }
}
