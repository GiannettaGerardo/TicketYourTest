<?php

namespace App\Http\Controllers;

use App\Models\CittadinoPrivato;
use App\Models\DatoreLavoro;
use App\Models\Laboratorio;
use App\Models\ListaDipendenti;
use Illuminate\Http\Request;

/**
 * Class ListaDipendentiController
 * Classe che incapsula la logica per il comportamento della lista dei dipendenti.
 */
class ListaDipendentiController extends Controller
{
    /**
     * Richiede l'inserimento nella lista dipendenti di un cittadino privato.
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|void
     */
    public function richiediInserimento(Request $request) {
        // Controllo inserimento nome azienda
        $request->validate(['nomeAzienda' => 'required|max:40']);

        // Controllo esistenza dell'azienda
        $nome_azienda = $request->input('nomeAzienda');
        $azienda = Laboratorio::getAziendaByNome($nome_azienda);
        if(!$azienda) {
            return back()->with('nome-azienda-errato', 'Il nome dell\'azienda risulta errato o inesistente!');
        }

        // Inserimento nel database dei dati
        $cittadino_privato = CittadinoPrivato::getById($request->get('LoggedUser'));
        ListaDipendenti::insertNewCittadino($azienda->partita_iva, $cittadino_privato->codice_fiscale, 0);

        return back()->with('richiesta-avvenuta', 'La richiesta e\' avvenuta con successo!');
    }


    /**
     * Permette ad un cittadino di abbandonare la lista dipendenti.
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function abbandona(Request $request) {
        ListaDipendenti::deleteCittadino($request->input('iva'), $request->input('cf'));

        return back()->with('abbandono-success', 'Hai abbandonato la lista con successo!');
    }


    /**
     * Permette ad un datore di lavoro di inserire un dipendente, anche se non e' registrato, alla lista dei dipendenti.
     * Viene controllato, tramite l'indirizzo email, che non esista un cittadino privato gia' registrato.
     * Se esiste, nella lista vengono inseriti solo i dati necessari (codice fiscale del dipendente e partita iva del datore). Altrimenti, vengono inserite
     * tutte le informazioni necessarie per identificare un dipendente (come nome, cognome, citta' di residenza ecc...).
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function inserisciDipendente(Request $request) {
        // Ottenimento del datore di lavoro dell'azienda
        $id_datore = $request->session()->get('LoggedUser');
        $datore = DatoreLavoro::getById($id_datore);

        // Validazione del codice fiscale e dell'email
        $request->validate([
            'cf' => 'required|min:16|max:16',
            'email' => 'required|email'
        ]);

        // Controllo dell'esistenza di un cittadino privato con quella email
        // Se il cittadino esiste, viene aggiunto alla lista solo il codice fiscale, altrimenti, vengono aggiunte tutte le informazioni
        $cittadino = CittadinoPrivato::getByEmail($request->input('email'));
        if($cittadino) {
            ListaDipendenti::insertNewCittadino($datore->partita_iva, $cittadino->codice_fiscale, 1);   // inserimento del cittadino
        } else {
            // Validazione degli altri dati
            $request->validate([
                'nome' => 'required|max:30',
                'cognome' => 'required|max:30',
                'citta_residenza' => 'required|max:50',
                'provincia_residenza' => 'required|max:50'
            ]);

            // Ottenimento dei dati
            $input = $request->all();

            // Inserimento del dipendente alla lista
            ListaDipendenti::insertNewDipendente(
                $datore->partita_iva,
                $input['cf'],
                $input['nome'],
                $input['cognome'],
                $input['email'],
                $input['citta_residenza'],
                $input['provincia_residenza']
            );
        }

        return back()->with('inserimento-success', 'Il dipendente e\' stato inserito con successo!');
    }


    /**
     * Restituisce la vista relativa alla lista dei dipendenti.
     * Prende dal model ListaDipendenti i dati sulla lista dei dipendenti che si vuole visualizzare e restituisce la vista corrispondente.
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function visualizzaListaDipendenti(Request $request) {
        // Ottenimento della lista dei dipendenti
        $datore = DatoreLavoro::getById($request->session()->get('LoggedUser'));
        $result = ListaDipendenti::getAllByPartitaIva($datore->partita_iva);

        // Trasformazione in array
        $listaDipendenti = [];
        $i=0;
        foreach($result as $dipendente) {
            $listaDipendenti[$i++] = [
                'codice_fiscale' => $dipendente->codice_fiscale,
                'nome' => $dipendente->nome,
                'cognome' => $dipendente->cognome,
                'email' => $dipendente->email,
                'citta_residenza' => $dipendente->citta_residenza,
                'provincia_residenza' => $dipendente->provincia_residenza
            ];
        }

        return view('listadatore', compact('listaDipendenti'));
    }

    
    /**
     * Metodo che restituisce la vista per visualizzare le richieste di inserimento da parte dei dipendenti nella lista di un certo datore
     * Prende dal model ListaDipendenti i dati sulle richieste che si vogliono visualizzare e restituisce la vista corrispondente.
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function visualizzaRichieste(Request $request) {
        // Ottenimento delle richieste
        $datore = DatoreLavoro::getById($request->session()->get('LoggedUser'));
        $result = ListaDipendenti::getRichiesteInserimentoByPartitaIva($datore->partita_iva);

        // Trasformazione in array
        $richieste = [];
        $i=0;
        foreach($result as $dipendente) {
            $richieste[$i++] = [
                'codice_fiscale' => $dipendente->codice_fiscale,
                'nome' => $dipendente->nome,
                'cognome' => $dipendente->cognome,
                'email' => $dipendente->email,
                'citta_residenza' => $dipendente->citta_residenza,
                'provincia_residenza' => $dipendente->provincia_residenza
            ];
        }

        // Restituzione vista
        return view('richiestedatore', compact('richieste'));
    }
}
