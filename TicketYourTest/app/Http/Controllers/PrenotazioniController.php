<?php

namespace App\Http\Controllers;

use App\Models\CalendarioDisponibilita;
use App\Models\Laboratorio;
use App\Models\Prenotazione;
use Carbon\Carbon;
use DateInterval;
use DateTime;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use App\Models\User;

/**
 * Class PrenotazioniController
 * Classe che incapsula la logica per il comportamento delle prenotazioni dei tamponi.
 */
class PrenotazioniController extends Controller
{
    const INTERVALLO_TEMPORALE = 14; // intervallo temporale per generare un calendario di prenotazioni


    /**
     * Restituisce la vista per visualizzare il form di prenotazione
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function visualizzaFormPrenotazione(Request $request) {
        // Ottenimento delle informazioni da inviare alla vista
        $utente = null;
        try {
            $utente = User::getById($request->session()->get('LoggedUser'));
        }
        catch(QueryException $ex) {
            abort(500, 'Il database non risponde.');
        }

        return view('form-prenotazione', compact('utente'));
    }


    /**
     * Prepara le date e le variabili per la generazione di un calendario prenotazione
     * @param $id_lab
     * @return array
     */
    public static function preparaCalendario($id_lab)
    {
        $calendario = null;
        $capienza_lab = 0;
        try {
            $calendario = CalendarioDisponibilita::getCalendarioDisponibilitaByIdLaboratorio($id_lab);
            $capienza_lab = Laboratorio::getCapienzaById($id_lab);
        }
        catch(QueryException $ex) {
            abort(500, 'Il database non risponde.');
        }

        $da_giorni_a_numeri = [
            'lunedi' => 0,
            'martedi' => 1,
            'mercoledi' => 2,
            'giovedi' => 3,
            'venerdi' => 4,
            'sabato' => 5,
            'domenica' => 6
        ];

        $orari = [];
        $boolean_calendario = [false, false, false, false, false, false, false];
        foreach ($calendario as $c) {
            $boolean_calendario[$da_giorni_a_numeri[$c->giorno_settimana]] = true;
            $orari[$da_giorni_a_numeri[$c->giorno_settimana]] = intval($c->oraChiusura);
        }

        $giorno = date('N') - 1; // ottengo il giorno della settimana odierno sotto forma di numero
        $giorno_datetime = date('Y-m-d'); // ottengo la data odierna

        return [
            'capienza_lab' => $capienza_lab,
            'orari' => $orari,
            'boolean_calendario' => $boolean_calendario,
            'giorno' => $giorno,
            'giorno_datetime' => $giorno_datetime
        ];
    }


    /**
     * Genera un calendario per le prenotazioni di un laboratorio. Il giorno corrente
     * è prenotabile se e solo se l'orario corrente è minore dell'orario di chiusura
     * meno 3 ore, quindi è possibile prenotare un tampone per il giorno stesso entro
     * massimo 3 ore dalla chiusura del laboratorio. Verranno mostrate le date solo
     * di giorni con posti di prenotazione ancora disponibili.
     * @param Request $request
     * @param $id_lab
     * @return array
     */
    public function generaCalendarioLaboratorio(Request $request, $id_lab)
    {
        $r = self::preparaCalendario($id_lab);
        $boolean_calendario = $r['boolean_calendario'];
        $giorno = $r['giorno'];
        $orari = $r['orari'];
        $giorno_datetime = $r['giorno_datetime'];
        $capienza_lab = $r['capienza_lab'];

        // conterrà il calendario generato
        $nuovo_calendario = [];

        /* Il primo controllo è fuori dal ciclo perché ha un controllo in più. Ovvero
         * se è possibile prenotare per il giorno corrente, bisogna confrontare l'ora
         * di chiusura con l'ora attuale. */
        try {
            if ($boolean_calendario[$giorno]) {
                $ora = intval(Carbon::now()->format('H'));
                if ($ora < ($orari[$giorno] - 3)) {
                    if (Prenotazione::getPrenotazioniByIdEData($id_lab, $giorno_datetime) < $capienza_lab) {
                        array_push($nuovo_calendario, $giorno_datetime);
                    }
                }
            }
            $giorno = ($giorno + 1) % 7;
            $giorno_datetime = date('Y-m-d', strtotime($giorno_datetime .' +1 day'));

            // intervallo di 2 settimane, 14 giorni
            for ($i = 0; $i < self::INTERVALLO_TEMPORALE; $i++) {
                if ($boolean_calendario[$giorno]) {
                    if (Prenotazione::getPrenotazioniByIdEData($id_lab, $giorno_datetime) < $capienza_lab) {
                        array_push($nuovo_calendario, $giorno_datetime);
                    }
                }
                $giorno = ($giorno + 1) % 7;
                $giorno_datetime = date('Y-m-d', strtotime($giorno_datetime .' +1 day'));
            }
        }
        catch(QueryException $ex) {
            abort(500, 'Il database non risponde.');
        }

        return $nuovo_calendario;
    }
}
