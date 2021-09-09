<?php

namespace App\View\Components\dipendenti;

use Illuminate\View\Component;

class listeAziende extends Component
{
    public $azienda;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($azienda)
    {
        $this->azienda = $azienda;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.dipendenti.liste-aziende');
    }
}
