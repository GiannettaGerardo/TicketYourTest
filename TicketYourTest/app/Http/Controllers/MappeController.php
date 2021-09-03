<?php

namespace App\Http\Controllers;

use App\Models\Laboratorio;
use App\Models\TamponiProposti;
use Illuminate\Http\Request;

/**
 * Class MappeController
 * Controller per gestire le mappe e la geo-localizzazione
 * @package App\Http\Controllers
 */
class MappeController extends Controller
{
    /**
     * Ritorna la vista contenente una mappa con i laboratori e alcune info sui tamponi proposti
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function getViewMappa(Request $request)
    {
        $laboratori = Laboratorio::getLaboratoriAttivi();
        $tamponi_proposti_db = TamponiProposti::getTamponiPropostiLabAttivi();
        $tamponi_proposti = array();
        
        foreach ($tamponi_proposti_db as $tupla) {
            $tamponi_proposti[$tupla->id_laboratorio][] = array('id_tampone' => $tupla->id_tampone, 'costo' => $tupla->costo);
        }

        //dd($tamponi_proposti);
        return view('laboratoriVicini', compact('laboratori', 'tamponi_proposti'));
    }
}
