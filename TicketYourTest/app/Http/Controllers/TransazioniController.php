<?php

namespace App\Http\Controllers;

use App\Models\CartaCredito;
use App\Models\Transazioni;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

/**
 * Class TransazioniController
 * Controller per gestire le transazioni tra utenti e laboratori
 * @package App\Http\Controllers
 */
class TransazioniController extends Controller
{
    /**
     * Restituisce la vista per visualizzare il form di checkout.
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function visualizzaFormCheckout(Request $request) {
        $prenotazioni = $request->session()->get('prenotazioni');

        return view('formCheckout', compact('prenotazioni'));
    }


    /**
     * Effettua il pagamento di uno o piu' tamponi
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function checkout(Request $request) {
        // Controllo sull'inserimento
        $request->validate([
            'nome_indirizzo_fatt' => 'required',
            'cognome_indirizzo_fatt' => 'required',
            'indirizzo' => 'required',
            'paese' => 'required',
            'citta' => 'required',
            'cap' => 'required',
            'nome_proprietario' => 'required',
            'numero_carta' => 'required|digits:16',
            'exp_month' => 'required|digits:2',
            'exp_year' => 'required|digits:2',
            'cvv' => 'required|digits:3'
        ]);

        // Ottenimento delle informazioni
        $nome_proprietario = $request->input('nome_proprietario');
        $numero_carta = $request->input('numero_carta');
        $exp = $request->input('exp_month') . '/' . $request->input('exp_year');
        $cvv = $request->input('cvv');
        $id_prenotazioni = $request->input('id_prenotazioni');  // array

        try {
            // Controllo esistenza carta di credito
            if(!CartaCredito::existsCartaCredito($nome_proprietario, $numero_carta, $exp, $cvv)) {
                return back()->with('checkout-error', 'I dati inseriti non corrispondono a nessuna carta di credito!');
            }

            // Inserimento nel database
            for($i=0; $i<count($id_prenotazioni); $i++) {
                $transazione = Transazioni::getTransazioneByIdPrenotazione($id_prenotazioni[$i]);
                Transazioni::setPagamentoEffettuato($transazione->id, 1, 1);
            }
        }
        catch(QueryException $ex) {
            abort(500, 'Il database non risponde.');
        }

        return redirect('/calendarioPrenotazioni')->with('checkout-success', 'Il pagamento e\' andato a buon fine!');
    }
}
