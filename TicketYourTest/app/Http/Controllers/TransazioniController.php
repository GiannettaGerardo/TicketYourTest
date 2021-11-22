<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

/**
 * Class TransazioniController
 * Controller per gestire le transazioni tra utenti e laboratori
 * @package App\Http\Controllers
 */
class TransazioniController extends Controller
{
    /**
     * Restituisce la vista per visualizzare il form di checkout.
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function visualizzaFormCheckout(Request $request) {
        $prenotazioni = $request->session()->get('prenotazioni');

        return view('formCheckout', compact('prenotazioni'));
    }
}
