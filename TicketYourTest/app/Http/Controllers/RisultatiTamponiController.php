<?php

namespace App\Http\Controllers;

use App\Models\Laboratorio;
use App\Models\MedicoMG;
use App\Models\Paziente;
use App\Models\Prenotazione;
use App\Models\Referto;
use App\Models\Tampone;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use PDF;
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
            Referto::updateRefertoByIdPrenotazioneCfPaziente($id_prenotazione, $cf_paziente, $esito_tampone, Carbon::now()->format('Y-m-d'), $quantita);
        }
        catch(QueryException $ex) {
            abort(500, 'Il database non risponde.');
        }

        return back()->with('referto-success', 'Il referto e\' stato creato con successo!');
    }


    /**
     * Restituisce una vista sotto forma di pdf per visualizzare un referto
     * @param $id l'id del referto da visualizzare
     * @return mixed
     */
    public function visualizzaReferto($id) {
        $id_referto = $id;
        $referto = null;

        try {
            $referto = Referto::getRefertoById($id_referto);
        }
        catch(QueryException $ex) {
            abort(500, 'Il database non risponde.');
        }

        $pdf = PDF::loadView('referto', compact('referto'));
        return $pdf->stream('referto'. $referto->cf_paziente .'.pdf');
    }
}
