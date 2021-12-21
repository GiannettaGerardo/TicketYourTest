<?php

namespace App\Http\Controllers;

use App\Models\CartaCredito;
use App\Models\Paziente;
use App\Models\Transazioni;
use App\Notifications\NotificaRicevutaPagamento;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Notification;

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


    /**
     * Ritorna la lista contenente le persone che devono pagare in contanti
     * e che hanno già pagato in contanti
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function getListaUtenti(Request $request)
    {
        $listaUtentiPagamentoInContantiNonEffettuato = null;
        $listaUtentiPagamentoEffettuato = null;
        $idLaboratorio = $request->session()->get('LoggedUser');
        try {
            $listaUtentiPagamentoInContantiNonEffettuato =
                Transazioni::getUtentiConPagamentoContantiDaEffettuareByLab($idLaboratorio);

            $listaUtentiPagamentoEffettuato =
                Transazioni::getUtentiCheHannoPagatoByLab($idLaboratorio);
        }
        catch (QueryException $e) {
            abort(500, 'Il database non risponde');
        }
        return view('registroPagamentiLab', compact(
            'listaUtentiPagamentoInContantiNonEffettuato',
            'listaUtentiPagamentoEffettuato'));
    }


    /**
     * Salva il pagamento in contanti di una persona che ha fatto il tampone
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function salvaPagamento(Request $request)
    {
        $id_laboratorio = $request->session()->get('LoggedUser');
        $id_transazione = $request->input('id_transazione');
        $nome_laboratorio = $request->session()->get('Nome');
        try {
            Transazioni::setPagamentoEffettuato($id_transazione, true);
            $datiEmail = Transazioni::getPazienteByTransazionePerRicevutaPagamento($id_laboratorio, $id_transazione);
            $allPazienti = Paziente::getQueryForAllPazienti()->get();
            $this->mergePaziente($allPazienti, $datiEmail);
            $this->inviaRicevutaPagamento($datiEmail, $nome_laboratorio);
        }
        catch (QueryException $e) {
            abort(500, 'Il database non risponde');
        }
        return $this->getListaUtenti($request);
    }


    /**
     * Invia una notifica email contenente i dati della ricevuta di pagamento
     * di un tampone in laboratorio
     * @param Collection $datiEmail // contiene i dati che servono per creare una ricevuta
     * @param $nome_lab // nome del laboratorio in cui è avvenuto il pagamento
     */
    private function inviaRicevutaPagamento(Collection $datiEmail, $nome_lab)
    {
        $details = [
            'greeting' => 'Hai una nuova ricevuta di pagamento!',
            'nome_laboratorio' => 'Presso il laboratorio: ' . $nome_lab,
            'data_di_pagamento' => 'In data: ' . $datiEmail->data_tampone,
            'nome_tampone_effettuato' => 'Tampone effettuato: ' . $datiEmail->nome_tampone,
            'importo_pagato' => 'Importo pagato: ' . $datiEmail->costo_tampone,
            'id_transazione' => 'Codice univoco della ricevuta: ' . $datiEmail->id_transazione
        ];

        Notification::route('mail', $datiEmail->email_paziente)->notify(new NotificaRicevutaPagamento($details));
    }


    private function mergePaziente($pazienti, $datiEmail) {
        if ($datiEmail !== null) {
            foreach ($pazienti as $paziente) {
                if ($paziente->cf_paziente === $datiEmail->cf_paziente) {
                    $datiEmail->email_paziente = $paziente->email_paziente;
                    break;
                }
            }
        }
    }
}
