<?php

namespace App\Http\Controllers;

use App\Models\Paziente;
use App\Models\Referto;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

/**
 * Class RisultatiTamponiController
 * Classe che si occupa di gestire i risultati dei tamponi
 */
class RisultatiTamponiController extends Controller
{
    /**
     * Restituisce la vista per visualizzare tutti i pazienti che devono effettuare il tampone in data odierna.
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function visualizzaElencoPazientiOdierni(Request $request) {
        $id_lab = $request->session()->get('LoggedUser');
        $pazienti_odierni = null;

        try {
            $pazienti_odierni = Paziente::getPazientiOdierniByIdLaboratorio($id_lab);
        }
        catch(QueryException $ex) {
            abort(500, 'Il database non risponde.');
        }

        return view('elencoPazientiOdierni', compact('pazienti_odierni'));
    }


    /**
     * Registra il referto associato al tampone.
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function confermaEsitoTampone(Request $request) {
        $request->validate([
            'esito_tampone' => 'required',
        ]);

        $id_prenotazione = $request->input('id_prenotazione');
        $cf_paziente = $request->input('cf_paziente');
        $esito_tampone = $request->input('esito_tampone');
        $quantita = $request->input('quantita');

        // Check sulla quantita'
        if($esito_tampone === 'positivo' && !isset($quantita)) {
            return back()->with('referto-error', 'Se l\'esito e\' positivo bisogna inserire anche la quantita\' di materiale genetico.');
        }

        // Inserimento nel DB
        try {
            Referto::upsertReferto($id_prenotazione, $cf_paziente, $esito_tampone, $quantita, Carbon::now()->format('Y-m-d'));
        }
        catch(QueryException $ex) {
            abort(500, 'Il database non risponde.');
        }

        return back()->with('referto-success', 'Il referto e\' stato creato con successo!');
    }
}
