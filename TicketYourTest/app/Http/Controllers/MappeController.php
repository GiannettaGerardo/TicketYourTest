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
     * Ritorna la vista contenente una mappa con i laboratori
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function getViewMappa(Request $request)
    {
        $laboratori = Laboratorio::getLaboratoriAttivi();
        return view('laboratoriVicini', compact('laboratori'));
    }
}
