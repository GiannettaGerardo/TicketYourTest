<?php

namespace App\View\Components\dashboardProfilo;

use Illuminate\View\Component;

class dashboardDatore extends Component
{

    public $datoreLavoro;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($datoreLavoro)
    {
        $this->datoreLavoro = $datoreLavoro;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.dashboard-profilo.dashboard-datore');
    }
}
