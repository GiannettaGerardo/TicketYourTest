<?php

namespace App\View\Components\storicoPrenotazioni;

use Illuminate\View\Component;

class tabellaStoricoPerTerzi extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.storico-prenotazioni.tabella-storico-per-terzi');
    }
}
