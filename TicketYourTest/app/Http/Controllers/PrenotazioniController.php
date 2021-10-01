<?php

namespace App\Http\Controllers;

use App\Models\CalendarioDisponibilita;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\User;

/**
 * Class PrenotazioniController
 * Classe che incapsula la logica per il comportamento delle prenotazioni dei tamponi.
 */
class PrenotazioniController extends Controller
{
    /**
     * Restituisce la vista per visualizzare il form di prenotazione
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function visualizzaFormPrenotazione(Request $request) {
        // Ottenimento delle informazioni da inviare alla vista
        $utente = User::getById($request->session()->get('LoggedUser'));

        return view('form-prenotazione', compact('utente'));
    }

    /**
     * Cerca la prima data disponibile per prenotare un tampone in un laboratorio.
     * Se la prima data disponibile corrisponde con quella corrente, il limite di tempo
     * per prenotare sarà di massimo 3 ore prima dell'orario di chiusura.
     * @param Request $request
     * @param $id_lab // id del laboratorio selezionato dall'utente
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function visualizzaPrimaDataDisponibile(Request $request, $id_lab)
    {
        $calendario = CalendarioDisponibilita::getCalendarioDisponibilitaByIdLaboratorio($id_lab);
        // creo un array di giorni con cui otterò il nome completo del giorno corrente
        $array_giorni = ['lunedi', 'martedi', 'mercoledi', 'giovedi', 'venerdi', 'sabato', 'domenica'];
        $array_giorni_numerici = [
            'lunedi' => 0,
            'martedi' => 1,
            'mercoledi' => 2,
            'giovedi' => 3,
            'venerdi' => 4,
            'sabato' => 5,
            'domenica' => 6
        ];
        // ottengo il giorno della settimana corrente
        $oggi = $array_giorni[Carbon::now()->dayOfWeek];
        // ottengo l'ora corrente
        $ora = intval(Carbon::now()->format('H'));

        $giorni_calendario = [];
        $orari = [];

        foreach ($calendario as $c) {
            $giorni_calendario[$c->giorno_settimana] = $array_giorni_numerici[$c->giorno_settimana];
            $orari[$c->giorno_settimana] = ['oraApertura' => $c->oraApertura, 'oraChiusura' => $c->oraChiusura];

            if ($c->giorno_settimana === $oggi) {
                $ora_chiusura = intval(date('H', strtotime($c->oraChiusura)));

                // sostituire true con un controllo sul numero di prenotazioni in quel giorno
                // è possibile effettuare una prenotazione entro massimo 3 ore prima dell'ora di chiusura
                if (($ora < ($ora_chiusura - 3)) and (true)) {
                    // preparo le variabili di ritorno
                    $giorno_return = $c->giorno_settimana;
                    $ore_return = $orari[$c->giorno_settimana];
                    return view('...', compact('giorno_return', 'ore_return'));
                }
            }
        }
        // cerco il primo giorno disponibile successivo al corrente
        $oggi_giorno_numerico = $array_giorni_numerici[$oggi];
        sort($giorni_calendario);

        foreach ($giorni_calendario as $giorno) {
            if ($giorno > $oggi_giorno_numerico) {
                // preparo le variabili di ritorno
                $giorno_return = $array_giorni[$giorno];
                $ore_return = $orari[$array_giorni[$giorno]];
                return view('...', compact('giorno_return', 'ore_return'));
            }
        }

    }
}
