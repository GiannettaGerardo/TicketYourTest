<?php

namespace App\View\Components\dashboardProfilo;

use App\Models\User;
use Illuminate\View\Component;

class dashboardCittadino extends Component
{
    public $cittadinoPrivato;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($cittadinoPrivato)
    {
        $this->cittadinoPrivato = $cittadinoPrivato;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.dashboard-profilo.dashboard-cittadino');
    }
}
