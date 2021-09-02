<?php

namespace App\Http\Controllers;

use App\Models\Laboratorio;
use Illuminate\Http\Request;

/**
 * Class MappeController
 * Controller per gestire le mappe e la geo-localizzazione
 * @package App\Http\Controllers
 */
class MappeController extends Controller
{
    public function getViewMappa(Request $request)
    {
        $laboratori = Laboratorio::getLaboratoriAttivi();
        return view('laboratoriVicini', compact('laboratori'));
    }
}
