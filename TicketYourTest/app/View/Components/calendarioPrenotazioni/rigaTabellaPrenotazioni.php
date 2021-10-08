<?php

namespace App\View\Components\calendarioPrenotazioni;

use Illuminate\View\Component;

class rigaTabellaPrenotazioni extends Component
{
    public $prenotazione;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($prenotazione)
    {
        $this.$prenotazione = $prenotazione;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.calendario-prenotazioni.riga-tabella-prenotazioni');
    }
}
