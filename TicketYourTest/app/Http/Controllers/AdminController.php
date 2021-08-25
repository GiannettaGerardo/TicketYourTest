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
        $labs = Laboratorio::getLaboratoriNonConvenzionati();
        $laboratori = [];
        $i=0;

        // Trasformazione del risultato della query in un array contenente le informazioni sui laboratori sotto forma di array
        foreach($labs as $lab) {
            $laboratori[$i++] = [
                'id' => $lab->id,
                'partita_iva' => $lab->partita_iva,
                'nome' => $lab->nome,
                'provincia' => $lab->provincia,
                'citta' => $lab->citta,
                'indirizzo' => $lab->indirizzo,
                'email' => $lab->email
            ];
        }

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
            return back()->with('coordinate-non-inserite', 'Non sono state inserite le coordinate');
        }

        // Inserimento delle coordinate
        Laboratorio::setCoordinateById($laboratorio['id'], $laboratorio['coordinata_x'], $laboratorio['coordinata_y']);
        // Convenzionamento
        Laboratorio::convenzionaById($laboratorio['id']);

        return back()->with('convenzionamento-avvenuto', 'Il laboratorio e\' stato correttamente convenzionato!');
    }
}
