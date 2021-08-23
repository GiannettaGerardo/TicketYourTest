<?php

namespace App\Http\Controllers;

use App\Models\Laboratorio;
use Illuminate\Http\Request;

/**
 * Class LoginController
 * Classe per gestire tutta la logica legata all'admin
 */
class AdminController extends Controller
{
    /**
     * Restituisce la vista per visualizzare i laboratori non convenzionati.
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function visualizzaLaboratoriNonConvenzionati(Request $request) {
        $laboratori = Laboratorio::getLaboratoriNonConvenzionati();
        return view('richiestaLab', compact('laboratori'));
    }

    /**
     * Permette di convenzionare un laboratorio, inserendo le coordinate.
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function convenzionaLaboratorio(Request $request) {
        // Informazioni del laboratorio
        $laboratorio = $request->all();

        // Controllo sull'inserimento delle coordinate
        if(!isset($laboratorio['coordinata_x']) and !isset($laboratorio['coordinata_y'])) {
            return back()->with('coordinate-non-inserite', 'Non sono state inserite le coordinate per ' . $laboratorio['nome'] . '!');
        }

        // Inserimento delle coordinate
        Laboratorio::setCoordinateById($laboratorio['id'], $laboratorio['coordinata_x'], $laboratorio['coordinata_y']);
        // Convenzionamento
        Laboratorio::convenzionaById($laboratorio['id']);

        return back()->with('convenzionamento-avvenuto', 'Il laboratorio ' . $laboratorio['nome'] . ' e\' stato correttamente convenzionato!');
    }
}
