<?php

namespace App\View\Components\registroLabPagamenti;

use Illuminate\View\Component;

class containerListaPagamentiLab extends Component
{
    public $registroPagamenti;
    public $flagBottone;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct( $registroPagamenti, $flagBottone )
    {
        $this->registroPagamenti = $registroPagamenti;
        //Variabile utilizzata per effettuare un controllo sulle componenti da far visualizzare
        $this->flagBottone = $flagBottone; 
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.registro-lab-pagamenti.container-lista-pagamenti-lab');
    }
}
