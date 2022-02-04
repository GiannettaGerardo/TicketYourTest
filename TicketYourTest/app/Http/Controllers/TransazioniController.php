<?php

namespace App\Http\Controllers;

use App\Models\CartaCredito;
use App\Models\Laboratorio;
use App\Models\Paziente;
use App\Models\Prenotazione;
use App\Models\Tampone;
use App\Models\TamponiProposti;
use App\Models\Transazioni;
use App\Models\User;
use App\Notifications\NotificaRicevutaPagamento;
use App\Notifications\NotificaRicevutaPagamentoPerTerzi;
use Carbon\Carbon;
use Exception;
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
        $size_prenotazioni = count($id_prenotazioni);
        $id_transazioni_terzi = [];

        try {
            // Controllo esistenza carta di credito
            if(!CartaCredito::existsCartaCredito($nome_proprietario, $numero_carta, $exp, $cvv)) {
                return back()->with('checkout-error', 'I dati inseriti non corrispondono a nessuna carta di credito!');
            }

            // Inserimento nel database
            for($i=0; $i < $size_prenotazioni; $i++) {
                $transazione = Transazioni::getTransazioneByIdPrenotazione($id_prenotazioni[$i]);
                Transazioni::setPagamentoEffettuato($transazione->id, 1, 1);
                //array_push($id_transazioni_terzi, $transazione->id);
                $id_transazioni_terzi[$id_prenotazioni[$i]] = $transazione->id; // mappo {id_prenotazione => id_transazione}
            }

            $this->invioRicevutaPagamentoOnlineAlPagante($request, $id_prenotazioni[0], $id_transazioni_terzi, $size_prenotazioni);

            // IF-AT.03
            $this->invioRicevutePagamentoOnlineAgliAssistitiTerzi($request, $id_prenotazioni, $id_transazioni_terzi, $size_prenotazioni);
        }
        catch(QueryException $ex) {
            abort(500, 'Il database non risponde.');
        }
        catch(Exception $ex) {
            abort(500, 'Invio ricevute pagamento impossibile.');
        }

        // Elimina i dati dalla sessione
        $request->session()->forget('prenotazioni');

        return redirect('/calendarioPrenotazioni')->with('checkout-success', 'Il pagamento e\' andato a buon fine!');
    }


    /**
     * Raccoglie i dati utili a creare una ricevuta di pagamento da
     * inviare a colui che paga online i tamponi per terze persone
     * @param Request $request
     * @param $id_prenotazione // un id di prenotazione di uno delle terze persone, non importa quale.
     *                         // Serve solo per ottenere l'id del laboratorio in cui si stanno prenotando i tamponi.
     * @param $id_transazioni_terzi // array contenente gli id delle transazioni create per le terze persone
     *                              // nella forma {id_prenotazione => id_transazione}.
     * @param $size // dimensione dell'array $id_transazioni_terzi e quindi anche il numero dei terzi alla quale
     *              // è stato prenotato un tampone.
     */
    private function invioRicevutaPagamentoOnlineAlPagante(Request $request, $id_prenotazione, $id_transazioni_terzi, $size)
    {
        $id_pagante = $request->session()->get('LoggedUser');
        $pagante = User::getById($id_pagante);
        $una_delle_prenotazioni = Prenotazione::getPrenotazioneById($id_prenotazione);
        $tampone = Tampone::getTamponeById($una_delle_prenotazioni->id_tampone);
        $tampone_proposto = TamponiProposti::getTamponePropostoLabAttivoById($una_delle_prenotazioni->id_laboratorio, $tampone->nome);
        $laboratorio = Laboratorio::getById($una_delle_prenotazioni->id_laboratorio);

        $datiEmail = [
            'data_tampone' => Carbon::now()->toDateTimeString(),
            'nome_tampone' => $tampone->nome,
            'costo_tampone' => $size * $tampone_proposto->costo,
            'email_paziente' => $pagante->email,
            'id_transazione' => ''
        ];

        $i = 0;
        foreach ($id_transazioni_terzi as $key => $id_tr) {
            if ($i >= $size-1) {
                $datiEmail['id_transazione'] .= $id_tr;
            }
            else {
                $datiEmail['id_transazione'] .= $id_tr . '-';
            }
            $i++;
        }

        $this->inviaRicevutaPagamento((object)$datiEmail, $laboratorio->nome);
    }


    /**
     * Raccoglie i dati utili a creare le ricevute di pagamento da inviare
     * alle agli assistiti terzi per cui è stato pagato il tampone.
     * @param Request $request
     * @param $id_prenotazioni // array contenete gli id di tutte le prenotazioni.
     * @param $id_transazioni_terzi // array contenente gli id delle transazioni create per le terze persone
     *                              // nella forma {id_prenotazione => id_transazione}.
     * @param $size // dimensione degli array id_prenotazioni e id_transazioni_terzi.
     */
    private function invioRicevutePagamentoOnlineAgliAssistitiTerzi(Request $request, $id_prenotazioni, $id_transazioni_terzi, $size)
    {
        $id_pagante = $request->session()->get('LoggedUser');
        $pagante = User::getById($id_pagante);
        $una_delle_prenotazioni = Prenotazione::getPrenotazioneById($id_prenotazioni[0]);
        $tampone = Tampone::getTamponeById($una_delle_prenotazioni->id_tampone);
        $tampone_proposto = TamponiProposti::getTamponePropostoLabAttivoById($una_delle_prenotazioni->id_laboratorio, $tampone->nome);
        $today = Carbon::now()->toDateTimeString();

        for ($p = 0; $p < $size; $p++) {
            $paziente = Paziente::getPrenotazioneEPazienteById($id_prenotazioni[$p]);

            $details = [
                'greeting' => 'Hai una nuova ricevuta di pagamento!',
                'nome_pagante' => 'Il pagamento è stato effettuato da: ' . $pagante->nome . ' ' . $pagante->cognome,
                'email_pagante' => 'L\'email del pagante è: ' . $pagante->email,
                'nome_laboratorio' => 'Presso il laboratorio: ' . $paziente->nome_laboratorio,
                'data_di_pagamento' => 'In data: ' . $today,
                'nome_tampone_effettuato' => 'Tampone effettuato: ' . $tampone->nome,
                'importo_pagato' => 'Importo pagato: € ' . $tampone_proposto->costo,
                'id_transazione' => 'Codice univoco della ricevuta: ' . $id_transazioni_terzi[$id_prenotazioni[$p]]
            ];

            Notification::route('mail', $paziente->email_paziente)
                ->notify(new NotificaRicevutaPagamentoPerTerzi($details));
        }
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
        $id_transazione = $request->input('id_transazione');
        try {
            Transazioni::setPagamentoEffettuato($id_transazione, true);
            $this->inviaRicevutaPagamentoEOttieniDati($request);
        }
        catch (QueryException $e) {
            abort(500, 'Il database non risponde');
        }
        return $this->getListaUtenti($request);
    }


    /**
     * Ottieni i dati della ricevuta di pagamento che servono ad
     * inviare una notifica email e successivamente la invia
     * @param Request $request
     * @param mixed $idTransazione
     */
    private function inviaRicevutaPagamentoEOttieniDati(Request $request, $idTransazione=null)
    {
        if ($idTransazione === null)
            $id_transazione = $request->input('id_transazione');
        else
            $id_transazione = $idTransazione;

        $id_laboratorio = $request->session()->get('LoggedUser');
        $nome_laboratorio = $request->session()->get('Nome');
        $datiEmail = Transazioni::getPazienteByTransazionePerRicevutaPagamento($id_laboratorio, $id_transazione);
        $allPazienti = Paziente::getQueryForAllPazienti()->get();
        $this->mergePaziente($allPazienti, $datiEmail);
        $this->inviaRicevutaPagamento($datiEmail, $nome_laboratorio);
    }


    /**
     * Invia una notifica email contenente i dati della ricevuta di pagamento
     * di un tampone in laboratorio
     * @param $datiEmail // contiene i dati che servono per creare una ricevuta
     * @param $nome_lab // nome del laboratorio in cui è avvenuto il pagamento
     */
    private function inviaRicevutaPagamento($datiEmail, $nome_lab)
    {
        $details = [
            'greeting' => 'Hai una nuova ricevuta di pagamento!',
            'nome_laboratorio' => 'Presso il laboratorio: ' . $nome_lab,
            'data_di_pagamento' => 'In data: ' . $datiEmail->data_tampone,
            'nome_tampone_effettuato' => 'Tampone effettuato: ' . $datiEmail->nome_tampone,
            'importo_pagato' => 'Importo pagato: € ' . $datiEmail->costo_tampone,
            'id_transazione' => 'Codice/i univoco/ci della ricevuta: ' . $datiEmail->id_transazione
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
