<?php

namespace App\Http\Controllers;

use App\Models\Prenotazione;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class StoricoTamponiController extends Controller
{
    /**
     * Visualizza lo storico dei tamponi di un utente. Solo tamponi di cui è disponibile il risultato.
     * Restituisce anche il referto del tampone da poter consultare o scaricare
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function getStoricoTamponiPersonali(Request $request)
    {
        $prenotazioni_mie = null;
        try {
            $codice_fiscale_utente = (User::getById($request->session()->get('LoggedUser')))->codice_fiscale;
            $prenotazioni_mie = Prenotazione::getStoricoPersonale($codice_fiscale_utente);

        }
        catch (QueryException $e) {
            abort(500, 'Il database non risponde.');
        }

        return view('storicoTamponi', compact('prenotazioni_mie'));
    }
}
