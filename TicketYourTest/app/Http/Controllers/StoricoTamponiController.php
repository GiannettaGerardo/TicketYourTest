<?php

namespace App\Http\Controllers;

use App\Models\Prenotazione;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class StoricoTamponiController extends Controller
{
    /**
     * Visualizza lo storico dei tamponi di un utente e dei dipendenti di un datore di lavoro.
     * Solo tamponi di cui è disponibile il risultato.
     * Restituisce anche il referto del tampone da poter consultare o scaricare
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function getStoricoTamponi(Request $request)
    {
        $prenotazioni_mie = null;
        $prenotazioni_dipendenti = null;
        try {
            $codice_fiscale_utente = (User::getById($request->session()->get('LoggedUser')))->codice_fiscale;
            $prenotazioni_mie = $this->getStoricoTamponiPersonali($request, $codice_fiscale_utente);
            if ($request->session()->get('Attore') === Attore::DATORE_LAVORO) {
                $prenotazioni_dipendenti = $this->getStoricoTamponiDipendenti($request, $codice_fiscale_utente);
            }
        }
        catch (QueryException $e) {
            abort(500, 'Il database non risponde.');
        }

        return view('storicoTamponi', compact('prenotazioni_mie', 'prenotazioni_dipendenti'));
    }


    /**
     * Ritorna lo storico dei tamponi effettuati di un utente.
     * Solo tamponi di cui è disponibile il risultato.
     * Restituisce anche il referto del tampone da poter consultare o scaricare
     * @param Request $request
     * @param $codice_fiscale_utente
     * @return \Illuminate\Support\Collection
     */
    private function getStoricoTamponiPersonali(Request $request, $codice_fiscale_utente)
    {
        $prenotazioni_mie = null;
        try {
            $prenotazioni_mie = Prenotazione::getStoricoPersonale($codice_fiscale_utente);
        }
        catch (QueryException $e) {
            throw $e;
        }
        return $prenotazioni_mie;
    }


    /**
     * Ritorna lo storico dei tamponi dei dipendenti di un datore di lavoro.
     * Solo tamponi di cui è disponibile il risultato.
     * Restituisce anche il referto dei tamponi da poter consultare o scaricare
     * @param Request $request
     * @param $codice_fiscale_datore
     * @return null
     */
    private function getStoricoTamponiDipendenti(Request $request, $codice_fiscale_datore)
    {
        $prenotazioni_dipendenti = null;
        try {
            $prenotazioni_dipendenti = Prenotazione::getStoricoDipendenti($codice_fiscale_datore);
        }
        catch (QueryException $e) {
            throw $e;
        }
        return $prenotazioni_dipendenti;
    }
}
