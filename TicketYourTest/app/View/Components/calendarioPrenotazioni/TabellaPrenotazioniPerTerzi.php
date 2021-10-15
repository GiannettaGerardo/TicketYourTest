<?php

namespace App\View\Components\calendarioPrenotazioni;

use Illuminate\View\Component;

class TabellaPrenotazioniPerTerzi extends Component
{

    public $prenotazioni;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($prenotazioni)
    {
        $this->prenotazioni = $prenotazioni;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components..calendario-prenotazioni.tabella-prenotazioni-per-terzi');
    }
}
