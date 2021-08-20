<?php

namespace App\View\Components\dashboardProfilo;

use Illuminate\View\Component;

class dashboardMedico extends Component
{
    public $medico;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($medico)
    {
        $this->usmedicoer = $medico;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.dashboard-profilo.dashboard-medico');
    }
}
