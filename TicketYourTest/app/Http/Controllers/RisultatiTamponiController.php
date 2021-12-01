<?php

namespace App\Http\Controllers;

use App\Models\MedicoMG;
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


    /**
     * Restituisce la vista per visualizzare l'elenco dei referti dei tamponi effettuato dai pazienti del medico che ha effettuato il login.
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function visualizzaElencoReferti(Request $request) {
        $medico = MedicoMG::getById($request->session()->get('LoggedUser'));
        $lista_pazienti = null;
        $elenco_referti = [];

        try {
            $lista_pazienti = Paziente::getPazientiByEmailMedico($medico->email);

            /*
             * Si prende l'ultimo referto per ciascun paziente e si aggiunge il risultato ottenuto in un array che
             * verra' passato in input alla vista.
             */
            foreach($lista_pazienti as $paziente) {
                $referto = Referto::getUltimoRefertoPazienteByCodiceFiscale($paziente->cf_paziente);

                if(isset($referto)) {
                    array_push($elenco_referti, [
                        'cf_paziente' => $paziente->cf_paziente,
                        'nome_paziente' => $paziente->nome_paziente,
                        'cognome_paziente' => $paziente->cognome_paziente,
                        'data_referto' => $referto->data_referto,
                        'id_referto' => $referto->id
                    ]);
                }
            }
        }
        catch(QueryException $ex) {
            abort(500, 'Il database non risponde.');
        }

        return view('elencoReferti', $elenco_referti);
    }


    public function visualizzaReferto(Request $request) {
        // Prendere il referto con le informazioni sul paziente

        // Restituire il pdf
    }
}
