<?php

namespace App\Http\Controllers\MappeController;

use App\Http\Controllers\Controller;
use App\Models\LaboratorioModel\Laboratorio;
use App\Models\PrenotazioniModel\Prenotazione;
use App\Models\LaboratorioModel\TamponiProposti;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
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
        $tamponi_proposti_db = null;
        $laboratori = null;
        try {
            $laboratori = Laboratorio::getLaboratoriAttivi();
            $tamponi_proposti_db = TamponiProposti::getTamponiPropostiLabAttivi();
        } catch (QueryException $ex) {
            abort(500, 'Il database non risponde.');
        }
        $tamponi_proposti = array();

        foreach ($tamponi_proposti_db as $tupla) {
            $tamponi_proposti[$tupla->id_laboratorio][] = array('id_tampone' => $tupla->id_tampone, 'costo' => $tupla->costo);
        }

        $tipoPrenotazione = $request->input('tipoPrenotazione');
        return view('laboratoriVicini', compact('laboratori', 'tamponi_proposti', 'tipoPrenotazione'));
    }


    /**
     * Trova il primo giorno disponibile per prenotare un laboratorio. Il giorno corrente
     * è prenotabile se e solo se l'orario corrente è minore dell'orario di chiusura
     * meno 3 ore, quindi è possibile prenotare un tampone per il giorno stesso entro
     * massimo 3 ore dalla chiusura del laboratorio. Inoltre vengono esclusi i giorni
     * che hanno la capienza massima di prenotazioni già raggiunta.
     * @param Request $request
     * @return false|mixed|string
     */
    public function primoGiornoDisponibile(Request $request)
    {
        $id_lab = $request->input('idLab');
        $r = PrenotazioniController::preparaCalendario($id_lab);
        $boolean_calendario = $r['boolean_calendario'];
        $giorno = $r['giorno'];
        $orari = $r['orari'];
        $giorno_datetime = $r['giorno_datetime'];
        $capienza_lab = $r['capienza_lab'];

        /* Il primo controllo è fuori dal ciclo perché ha un controllo in più. Ovvero
         * se è possibile prenotare per il giorno corrente, bisogna confrontare l'ora
         * di chiusura con l'ora attuale. */
        try {
            if ($boolean_calendario[$giorno]) {
                $ora = intval(Carbon::now()->format('H'));
                if ($ora < ($orari[$giorno] - 3)) {
                    if (Prenotazione::getPrenotazioniByIdEData($id_lab, $giorno_datetime) < $capienza_lab) {
                        return $giorno_datetime;
                    }
                }
            }
            $giorno = ($giorno + 1) % 7;
            $giorno_datetime = date('Y-m-d', strtotime($giorno_datetime . ' +1 day'));

            // ciclo infinito
            for ($i = 0; $i > -1; $i++) {
                if ($boolean_calendario[$giorno]) {
                    if (Prenotazione::getPrenotazioniByIdEData($id_lab, $giorno_datetime) < $capienza_lab) {
                        return $giorno_datetime;
                    }
                }
                $giorno = ($giorno + 1) % 7;
                $giorno_datetime = date('Y-m-d', strtotime($giorno_datetime . ' +1 day'));
            }
        } catch (QueryException $ex) {
            abort(500, 'Il database non risponde.');
        }
    }
}
