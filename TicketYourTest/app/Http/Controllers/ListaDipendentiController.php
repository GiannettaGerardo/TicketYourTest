<?php

namespace App\Http\Controllers;

use App\Models\CittadinoPrivato;
use App\Models\DatoreLavoro;
use App\Models\Laboratorio;
use App\Models\ListaDipendenti;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

/**
 * Class ListaDipendentiController
 * Classe che incapsula la logica per il comportamento della lista dei dipendenti.
 */
class ListaDipendentiController extends Controller
{
    /**
     * Restituisce la vista per permettere ad un datore di lavoro di inserire un dipendente.
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function visualizzaInserisciDipendente() {
        return view('AggiungiDipendente');
    }


    /**
     * Restituisce la vista per visualizzare la pagina di richiesta di inserimento in una lista
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function visualizzaInserimentoInLista() {
        return view('richiestaAzienda');
    }


    /**
     * Richiede l'inserimento nella lista dipendenti di un cittadino privato.
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|void
     */
    public function richiediInserimento(Request $request) {
        // Controllo inserimento nome azienda
        $request->validate(['nomeAzienda' => 'required|max:40']);

        try {
            // Controllo esistenza dell'azienda
            $nome_azienda = $request->input('nomeAzienda');
            $azienda = DatoreLavoro::getAziendaByNome($nome_azienda);
            if(!$azienda) {
                return back()->with('nome-azienda-errato', 'Il nome dell\'azienda risulta errato o inesistente!');
            }

            // Controllo esistenza di un'iscrizione gia' presente nell'azienda cercata
            $cittadino = CittadinoPrivato::getById($request->session()->get('LoggedUser'));
            $liste = ListaDipendenti::getAllListeByCodiceFiscale($cittadino->codice_fiscale);
            $found = false;
            $i=0;
            while(!$found and $i<count($liste)) {
                $lista = $liste[$i++];
                if($lista->nome_azienda === $nome_azienda) {
                    $found = true;
                }
            }

            if($found) {
                return back()->with('inserimento-gia-effettuato', 'Sei gia\' iscritto o hai gia\' fatto una richiesta di inserimento per la lista di questa azienda!');
            }

            // Inserimento nel database dei dati
            $cittadino_privato = CittadinoPrivato::getById($request->session()->get('LoggedUser'));
            ListaDipendenti::insertNewCittadino($azienda->partita_iva, $cittadino_privato->codice_fiscale, 0);
        }
        catch (QueryException $e) {
            abort(500, 'Il database non risponde.');
        }
        catch (Throwable $e) {
            abort(500, 'Server error. Manca la connessione.');
        }

        return back()->with('richiesta-avvenuta', 'La richiesta e\' avvenuta con successo!');
    }


    /**
     * Restituisce la vista per visualizzare le liste dei dipendenti di un cittadino.
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function visualizzaListeDipendentiCittadino(Request $request) {
        // Trasformazione in array
        $listeCittadino = [];
        try {
            // Ottenimento liste
            $cittadino = CittadinoPrivato::getById($request->session()->get('LoggedUser'));
            $liste = ListaDipendenti::getListeByCodiceFiscale($cittadino->codice_fiscale);

            $i=0;
            foreach($liste as $lista) {
                $listeCittadino[$i++] = [
                    'nome_azienda' => $lista->nome_azienda,
                    'partita_iva' => $lista->partita_iva
                ];
            }
        }
        catch (QueryException $e) {
            abort(500, 'Il database non risponde.');
        }
        catch (Throwable $e) {
            abort(500, 'Server error. Manca la connessione.');
        }

        return view('listeDipendenti', compact('listeCittadino'));
    }


    /**
     * Permette ad un cittadino di abbandonare la lista dipendenti.
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function abbandona(Request $request) {
        try {
            $cittadino = CittadinoPrivato::getById($request->session()->get('LoggedUser'));
            ListaDipendenti::deleteDipendente($request->input('iva'), $cittadino->codice_fiscale);
        }
        catch (QueryException $e) {
            abort(500, 'Il database non risponde.');
        }
        catch (Throwable $e) {
            abort(500, 'Server error. Manca la connessione.');
        }

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
        DB::beginTransaction();
        try {
            // Ottenimento del datore di lavoro dell'azienda
            $id_datore = $request->session()->get('LoggedUser');
            $datore = DatoreLavoro::getById($id_datore);

            // Validazione del codice fiscale e dell'email
            $request->validate([
                'codfiscale' => 'required|min:16|max:16'
            ]);

            // Controllo sull'esistenza di un cittadino nella lista
            $lista_dipendenti = ListaDipendenti::getAllByPartitaIva($datore->partita_iva);
            $lista_richieste = ListaDipendenti::getRichiesteInserimentoByPartitaIva($datore->partita_iva);
            $found = false;
            // Ricerca nella lista dipendenti
            foreach($lista_dipendenti as $dipendente) {
                if($dipendente->codice_fiscale === $request->input('codfiscale')) {
                    $found = true;
                }
            }
            // Ricerca nelle richieste
            foreach($lista_richieste as $dipendente) {
                if($dipendente->codice_fiscale === $request->input('codfiscale')) {
                    $found = true;
                }
            }

            if($found) {
                return back()->with('cittadino-esistente', 'Esiste gia\' un cittadino con queste credenziali che ha effettuato una richiesta di inserimento o e\' inserito nella lista!');
            }

            // Controllo dell'esistenza di un cittadino privato con quel codice fiscale
            // Se il cittadino esiste, viene aggiunto alla lista solo il codice fiscale, altrimenti, vengono aggiunte tutte le informazioni
            $cittadino_esistente = CittadinoPrivato::existsByCodiceFiscale($request->input('codfiscale'));
            if($cittadino_esistente) {
                ListaDipendenti::insertNewCittadino($datore->partita_iva, $request->input('codfiscale'), 1);   // inserimento del cittadino
            } else {
                // Validazione degli altri dati
                $request->validate([
                    'email' => 'required|email',
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
                    $input['codfiscale'],
                    $input['nome'],
                    $input['cognome'],
                    $input['email'],
                    $input['citta_residenza'],
                    $input['provincia_residenza']
                );
            }

            DB::commit();
        }
        catch (QueryException $e) {
            DB::rollBack();
            abort(500, 'Il database non risponde.');
        }
        catch (Throwable $e) {
            DB::rollBack();
            abort(500, 'Server error. Manca la connessione.');
        }

        return back()->with('inserimento-success', 'Il dipendente e\' stato inserito con successo!');
    }


    /**
     * Metodo che permette ad un datore di lavoro di eliminare un dipendente dalla lista.
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteDipendente(Request $request) {
        try {
            $datore = DatoreLavoro::getById($request->session()->get('LoggedUser'));
            ListaDipendenti::deleteDipendente($datore->partita_iva, $request->input('codice_fiscale'));
        }
        catch (QueryException $e) {
            abort(500, 'Il database non risponde.');
        }
        catch (Throwable $e) {
            abort(500, 'Server error. Manca la connessione.');
        }

        return back()->with('dipendente-eliminato', 'Il dipendente e\' stato eliminato con successo!');
    }


    /**
     * Restituisce la vista relativa alla lista dei dipendenti.
     * Prende dal model ListaDipendenti i dati sulla lista dei dipendenti che si vuole visualizzare e restituisce la vista corrispondente.
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function visualizzaListaDipendenti(Request $request) {
        $result = null;
        try {
            // Ottenimento della lista dei dipendenti
            $datore = DatoreLavoro::getById($request->session()->get('LoggedUser'));
            $result = ListaDipendenti::getAllByPartitaIva($datore->partita_iva);
        }
        catch (QueryException $e) {
            abort(500, 'Il database non risponde.');
        }
        catch (Throwable $e) {
            abort(500, 'Server error. Manca la connessione.');
        }

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
        $result = null;
        try {
            // Ottenimento delle richieste
            $datore = DatoreLavoro::getById($request->session()->get('LoggedUser'));
            $result = ListaDipendenti::getRichiesteInserimentoByPartitaIva($datore->partita_iva);
        }
        catch (QueryException $e) {
            abort(500, 'Il database non risponde.');
        }
        catch (Throwable $e) {
            abort(500, 'Server error. Manca la connessione.');
        }

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


    /**
     * Metodo per accettare la richiesta di un cittadino privato di entrare nella lista dipendenti.
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function accettaRichiestaDipendente(Request $request) {
        try {
            // Accettazione della richiesta
            $datore = DatoreLavoro::getById($request->session()->get('LoggedUser'));
            ListaDipendenti::accettaDipendenteByCodiceFiscale($datore->partita_iva, $request->input('codice_fiscale'));
        }
        catch (QueryException $e) {
            abort(500, 'Il database non risponde.');
        }
        catch (Throwable $e) {
            abort(500, 'Server error. Manca la connessione.');
        }

        return back()->with('richiesta-accettata', 'Il dipendente e\' stato inserito nella lista!');
    }

    /**
     * Metodo per rifiutare la richiesta di un cittadino privato di entrare nella lista dipendenti.
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function rifiutaRichiestaDipendente(Request $request) {
        try {
            // Rifiuto della richesta
            $datore = DatoreLavoro::getById($request->session()->get('LoggedUser'));
            ListaDipendenti::rifiutaDipendenteByCodiceFiscale($datore->partita_iva, $request->input('codice_fiscale'));
        }
        catch (QueryException $e) {
            abort(500, 'Il database non risponde.');
        }
        catch (Throwable $e) {
            abort(500, 'Server error. Manca la connessione.');
        }

        return back()->with('richiesta-rifiutata', 'La richiesta e\' stata rifiutata!');
    }
}
