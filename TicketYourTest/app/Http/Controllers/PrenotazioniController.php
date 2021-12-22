<?php

namespace App\Http\Controllers;

use App\Models\CalendarioDisponibilita;
use App\Models\DatoreLavoro;
use App\Models\Laboratorio;
use App\Models\Prenotazione;
use App\Models\QuestionarioAnamnesi;
use App\Models\Referto;
use App\Models\Tampone;
use App\Models\TamponiProposti;
use App\Models\Paziente;
use App\Models\ListaDipendenti;
use App\Models\Transazioni;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use App\Models\User;
use App\Notifications\NotificaEmail;
use Illuminate\Support\Facades\Notification;

/**
 * Class PrenotazioniController
 * Classe che incapsula la logica per il comportamento delle prenotazioni dei tamponi.
 */
class PrenotazioniController extends Controller
{
    const INTERVALLO_TEMPORALE = 15; // intervallo temporale per generare un calendario di prenotazioni

    /**
     * Prepara le informazioni da mostrare nei form di prenotazione di un tampone.
     * Acquisisce dal database informazioni per il laboratorio scelto, i giorni prenotabili e per i tamponi scelti.
     * Acquisisce anche informazioni per l'utente prenotante.
     * @param Request $request
     * @return array
     */
    private function preparaInfoFormPrenotaione(Request $request) {
        $id_lab = $request->input('id_lab');
        $utente_prenotante = null;
        $calendario_laboratorio = $this->generaCalendarioLaboratorio($request, $id_lab);
        $tamponi_prenotabili = null;
        $laboratorio_scelto = null;
        $giorni_prenotabili = []; // Array contenente, per ogni giorno, anche il numero di posti dispoibili

        try {
            $utente_prenotante = User::getById($request->session()->get('LoggedUser'));
            $tamponi_prenotabili = TamponiProposti::getTamponiPropostiByLaboratorio($id_lab);
            $laboratorio_scelto = Laboratorio::getById($id_lab);

            // Ottenimento giorni disponibili e capienza per quel giorno
            foreach($calendario_laboratorio as $giorno) {
                $num_prenotazioni_effettuate = Prenotazione::getPrenotazioniByIdEData($id_lab, $giorno);
                array_push($giorni_prenotabili, [
                    'data' => $giorno,
                    'posti_disponibili' => $laboratorio_scelto->capienza_giornaliera - $num_prenotazioni_effettuate
                ]);
            }
        }
        catch(QueryException $ex) {
            abort(500, 'Il database non risponde.');
        }

        return [
            'utente_prenotante' => $utente_prenotante,
            'giorni_prenotabili' => $giorni_prenotabili,
            'tamponi_prenotabili' => $tamponi_prenotabili,
            'laboratorio_scelto' => $laboratorio_scelto
        ];
    }


    /**
     * Restituisce la vista per visualizzare il form di prenotazione
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function visualizzaFormPrenotazione(Request $request) {
        // Ottenimento delle informazioni da inviare alla vista
        $infoFormPrenotazione = self::preparaInfoFormPrenotaione($request);

        $utente = $infoFormPrenotazione['utente_prenotante'];
        $giorni_prenotabili = $infoFormPrenotazione['giorni_prenotabili'];
        $tamponi_prenotabili = $infoFormPrenotazione['tamponi_prenotabili'];
        $laboratorio_scelto = $infoFormPrenotazione['laboratorio_scelto'];

        return view('formPrenotazioneTampone', compact('utente', 'laboratorio_scelto', 'tamponi_prenotabili', 'giorni_prenotabili'));
    }


    /**
     * Restituisce la vista per visualizzare il form di prenotazione per terzi
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function visualizzaFormPrenotazionePerTerzi(Request $request) {
        // Ottenimento delle informazioni da inviare alla vista
        $infoFormPrenotazione = self::preparaInfoFormPrenotaione($request);

        $giorni_prenotabili = $infoFormPrenotazione['giorni_prenotabili'];
        $tamponi_prenotabili = $infoFormPrenotazione['tamponi_prenotabili'];
        $laboratorio_scelto = $infoFormPrenotazione['laboratorio_scelto'];

        return view('formPrenotazioneTamponePerTerzi', compact('laboratorio_scelto', 'tamponi_prenotabili', 'giorni_prenotabili'));
    }


    /**
     * Restituisce la vista per visualizzare il form di prenotazione per i dipendenti
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function visualizzaFormPrenotazioneDipendenti(Request $request) {
        // Ottengo le informazioni da inviare alla vista
        $infoFormPrenotazione = $this->preparaInfoFormPrenotaione($request);

        $giorni_prenotabili = $infoFormPrenotazione['giorni_prenotabili'];
        $tamponi_prenotabili = $infoFormPrenotazione['tamponi_prenotabili'];
        $laboratorio_scelto = $infoFormPrenotazione['laboratorio_scelto'];
        $dipendenti = null;

        // Ottenimento della lista dei dipendenti a cui va prenotato il tampone
        try {
            $datore = DatoreLavoro::getById($request->session()->get('LoggedUser'));
            $dipendenti = ListaDipendenti::getAllByPartitaIva($datore->partita_iva);
        }
        catch(QueryException $ex) {
            abort(500, 'Il database non risponde.');
        }

        return view('formPrenotazioneTamponiPerDipendenti', compact('laboratorio_scelto', 'tamponi_prenotabili', 'giorni_prenotabili', 'dipendenti'));
    }


    /**
     * Restituisce le prenotazioni
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function visualizzaElencoPrenotazioni(Request $request) {
        $id_lab = $request->session()->get('LoggedUser');
        $prenotazioni = null;

        try {
            $prenotazioni = Prenotazione::getInfoPrenotazioniFutureByIdLab($id_lab);
        }
        catch(QueryException $ex) {
            abort(500, 'Il database non risponde.');
        }

        return view('elencoPrenotazioniLab', compact('prenotazioni'));
    }


    /**
     * Effettua la prenotazione singola di un tampone in un dato laboratorio.
     * @param Request $request
     */
    public function prenota(Request $request) {
        // Validazione dell'input
        $request->validate([
            'email_prenotante' => 'required|email',
            'numero_cellulare' => 'digits:10',
            'data_tampone' => 'required',
            'tampone' => 'required'
        ]);

        // Ottenimento delle informazioni dal form
        $id_lab = $request->input('id_lab');
        $cod_fiscale_prenotante = $request->input('cod_fiscale');
        $email = $request->input('email_prenotante');
        $numero_cellulare = $request->input('numero_cellulare');
        $data_tampone = $request->input('data_tampone');
        $tampone_scelto = null;
        $tampone_proposto = null;
        $prenotazione_effettuata = [];   // contiene le informazioni per effettuare il checkout
        $prenotazione_esistente = false;

        try {
            $tampone_scelto = Tampone::getTamponeByNome($request->input('tampone'));

            // Inserimento delle informazioni nel database
            $prenotazione_esistente = $this->createPrenotazioneIfNotExsists(
                $cod_fiscale_prenotante,
                $cod_fiscale_prenotante,
                $email,
                $tampone_scelto,
                Carbon::now()->format('Y-m-d'),
                $data_tampone,
                $id_lab,
                null,
                null,
                $numero_cellulare
            );

            if(!$prenotazione_esistente) {
                return back()->with('prenotazione-esistente', 'E\' stata gia\' effettuata una prenotazione per ' . $cod_fiscale_prenotante . ' per il giorno ' . Carbon::parse($data_tampone)->format('d/m/Y') . '!');
            }

            // Ottenimento informazioni sulla prenotazione appena effettuata
            $id_prenotazione = Prenotazione::getLastPrenotazione()->id;
            $tampone_proposto = TamponiProposti::getTamponePropostoLabAttivoById($id_lab, $tampone_scelto->nome);

            // Creazione del questionario anamnesi
            $this->createQuestionarioAnamnesi($cod_fiscale_prenotante);

            // Creazione della transazione
            Transazioni::insertNewTransazione($id_prenotazione, $id_lab, $tampone_proposto->costo);

            // Creazione informazioni per il checkout
            $prenotazione_effettuata = $this->preparaInfoCheckout($id_prenotazione, $id_lab, $tampone_scelto->nome);

        }
        catch(QueryException $ex) {
            abort(500, 'Il database non risponde');
        }

        // Checkout
        $request->session()->flash('prenotazioni', [$prenotazione_effettuata]);
        return redirect('/checkout');
    }


    /**
     * Effettua la prenotazione di un tampone per terzi
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function prenotaPerTerzi(Request $request) {
        // Validazione dell'input
        $request->validate([
            'nome' => 'required|max:30',
            'cognome' => 'required|max:30',
            'cod_fiscale' => 'required|min:16|max:16',
            'email' => 'required|email',
            'numero_cellulare' => 'digits:10',
            'citta_residenza' => 'required|min:2|max:30',
            'provincia_residenza' => 'required|min:2|max:30',
            'tampone' => 'required',
            'data_tampone' => 'required'
        ]);

        // Ottenimento delle informazioni dal form
        $nome_paziente = $request->input('nome');
        $cognome_paziente = $request->input('cognome');
        $cod_fiscale_paziente = $request->input('cod_fiscale');
        $email = $request->input('email');
        $numero_cellulare = $request->input('numero_cellulare');
        $citta_residenza_paziente = $request->input('citta_residenza');
        $provincia_residenza_paziente = $request->input('provincia_residenza');
        $data_tampone = $request->input('data_tampone');
        $id_lab = $request->input('id_lab');
        $tampone_scelto = null;
        $utente = null;
        $laboratorio = null;
        $prenotazione_effettuata = [];  // contiene le informazioni per il checkout

        try {
            $utente = User::getById($request->session()->get('LoggedUser'));
            $laboratorio = Laboratorio::getById($id_lab);
            $tampone_scelto = TamponiProposti::getTamponePropostoLabAttivoById($id_lab, $request->input('tampone'));

            // Inserimento delle informazioni nel database
            $this->createPrenotazioneIfNotExsists(
                $utente->codice_fiscale,
                $cod_fiscale_paziente,
                $email,
                $tampone_scelto,
                Carbon::now()->format('Y-m-d'),
                $data_tampone,
                $id_lab,
                $nome_paziente,
                $cognome_paziente,
                $numero_cellulare,
                $citta_residenza_paziente,
                $provincia_residenza_paziente
            );

            // Ottenimento informazioni sulla prenotazione appena effettuata
            $id_prenotazione = Prenotazione::getLastPrenotazione()->id;

            // Creazione del questionario anamnesi
            $this->createQuestionarioAnamnesi($cod_fiscale_paziente);

            // Creazione della transazione
            Transazioni::insertNewTransazione($id_prenotazione, $id_lab, $tampone_scelto->costo);

            // Creazione informazioni per il checkout
            $prenotazione_effettuata = $this->preparaInfoCheckout(Prenotazione::getLastPrenotazione()->id, $id_lab, $request->input('tampone'));
        }
        catch(QueryException $ex) {
            abort(500, 'Il database non risponde');
        }

        // INVIO NOTIFICA EMAIL
        self::inviaNotificaPrenotazioneDaTerzi(
            $nome_paziente.' '.$cognome_paziente,
            $email,
            $utente->nome,
            $laboratorio->nome,
            $laboratorio->citta,
            $data_tampone,
            $tampone_scelto->costo
        );


        if($request->session()->get('Attore')===Attore::MEDICO_MEDICINA_GENERALE) {
            return redirect('/calendarioPrenotazioni')->with('prenotazione-success', 'La prenotazione e\' stata effettuata con successo! A breve il paziente ricevera\' un\'email di conferma.');
        }

        // Checkout
        $request->session()->flash('prenotazioni', [$prenotazione_effettuata]);
        return redirect('/checkout')->with('prenotazione-success', 'La prenotazione e\' stata effettuata con successo! A breve il paziente ricevera\' un\'email di conferma.');
    }


    /**
     * Qui viene effettuata e gestita la prenotazione per i dipendenti.
     * Viene controllato il numero di dipendenti a cui fare il tampone e, se questo supera il numero di posti disponibili per un dato giorno,
     * la prenotazione per i dipendenti in eccesso viene spostata al primo giorno disponibile dopo a quello scelto.
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function prenotaPerDipendenti(Request $request) {
        // Controllo degli input
        $request->validate([
            'tampone' => 'required',
            'data_tampone' => 'required',
            'dipendenti' => 'required'
        ]);

        // Ottenimento degli input
        $id_lab = $request->input('id_lab');
        $tampone_scelto = null;
        $data_tampone_prevista = $request->input('data_tampone');
        $cod_fiscali_dipendenti = $request->input('dipendenti');
        $num_posti_disponibili = $request->input('posti_disponibili');
        $dipendenti = []; // Conterra' i dipendenti presi dalla lista
        $datore = null;
        $laboratorio_scelto = null;
        $calendario_prenotazioni = self::generaCalendarioLaboratorio($request, $id_lab);
        $prenotazioni_effettuate = [];  // contiene le informazioni per il checkout
        $success_message = null;
        $error_message = null;

        try {
            $tampone_scelto = Tampone::getTamponeByNome($request->input('tampone'));
            $datore = DatoreLavoro::getById($request->session()->get('LoggedUser'));
            $laboratorio_scelto = Laboratorio::getById($id_lab);
            $tampone_proposto = TamponiProposti::getTamponePropostoLabAttivoById($id_lab, $tampone_scelto->nome);

            // Ottenimento delle informazioni dei dipendenti
            $i=0;
            foreach($cod_fiscali_dipendenti as $cf) {
                $dipendenti[$i++] = ListaDipendenti::getDipendenteByPartitaIvaECodiceFiscale($datore->partita_iva, $cf);
            }

            /*
             * Si effettua la prenotazione del tampone per tutti i dipendenti dividendoli giorno per giorno.
             * Vengono effettuate le prenotazioni per i dipendenti controllando, ad ogni iterata, che il numero di posti disponibili
             * non sia 0. Nel caso, si aggiorna la data per effettuare il tampone e si aggiorna, quindi il numero di posti disponibili per quel giorno.
             */
            $data_tampone_effettiva = $data_tampone_prevista;   // inizializzo la data effettiva con la data prevista per il tampone
            $num_posti_disponibili = $laboratorio_scelto->capienza_giornaliera - Prenotazione::getPrenotazioniByIdEData($id_lab, $data_tampone_effettiva);  // TODO Da eliminare (bisogna prenderlo in input)
            for($i=0; $i<count($dipendenti); $i++) {
                // Prenotazione tampone
                $this->createPrenotazioneIfNotExsists(
                    $datore->codice_fiscale,
                    $dipendenti[$i]->codice_fiscale,
                    $dipendenti[$i]->email,
                    $tampone_scelto,
                    Carbon::now()->format('Y-m-d'),
                    $data_tampone_effettiva,
                    $id_lab
                );

                // Aggiornamento dei posti disponibili ed eventualmente anche del giorno
                $num_posti_disponibili--;
                if($num_posti_disponibili === 0) {
                    $indice_data_successiva = array_search($data_tampone_effettiva, $calendario_prenotazioni)+1;

                    // Se l'indice dell'array e' l'ultimo, viene restituito un errore
                    if($indice_data_successiva === array_key_last($calendario_prenotazioni)) {
                        $error_message = 'Sono esauriti i posti per i primi ' . self::INTERVALLO_TEMPORALE . ' giorni. Riprovare successivamente!';
                        $success_message = 'Le prenotazioni dei tamponi per i primi dipendenti sono state effettuate con successo! Verra\' inviata un\'email ai dipendenti con le indicazioni sulla prenotazione.';
                    }

                    $data_tampone_effettiva = $calendario_prenotazioni[$indice_data_successiva];
                    $num_posti_disponibili = $laboratorio_scelto->capienza_giornaliera - Prenotazione::getPrenotazioniByIdEData($id_lab, $data_tampone_effettiva);
                }

                // Ottenimento informazioni sulla prenotazione appena effettuata
                $id_prenotazione = Prenotazione::getLastPrenotazione()->id;

                // Creazione della transazione
                Transazioni::insertNewTransazione($id_prenotazione, $id_lab, $tampone_proposto->costo);

                // Preparazione delle info per il checkout
                array_push($prenotazioni_effettuate, $this->preparaInfoCheckout(Prenotazione::getLastPrenotazione()->id, $id_lab, $tampone_scelto->nome));
            } // end for
        }
        catch(QueryException $ex) {
            abort(500, 'Il database non risponde');
        }

        // Checkout
        $request->session()->flash('prenotazioni', $prenotazioni_effettuate);
        return redirect('/checkout')
            ->with('giorni-prenotazioni-superati', $error_message)
            ->with('prenotazione-success', $success_message);
    }


    /**
     * Inserisce la prenotazione nella tabella 'prenotazioni', il paziente nella tabella 'pazienti' e il referto nella tabella 'referti',
     * preoccupandosi di controllare che questa prenotazione non esista gia'.
     * @param $cod_fiscale_prenotante // Il codice fiscale di chi effettua la prenotazione
     * @param $cod_fiscale_paziente // Il codice fiscale del paziente
     * @param $email // L'email dove ricevere l'avviso
     * @param $tampone_scelto // Il tampone scelto
     * @param $data_prenotazione // La data in cui deve essere registrata la prenotazione
     * @param $data_tampone // La data in cui deve essere effettuato il tampone
     * @param $id_lab // L'id del laboratorio presso cui effettuare il tampone
     * @param null $nome_paziente // Il nome del paziente
     * @param null $cognome_paziente // Il cognome del paziente
     * @param null $numero_cellulare // Il numero di cellulare
     * @param null $citta_residenza // La citta' di residenza del paziente
     * @param null $provincia_residenza // La provincia di residenza del paziente
     * @return \Illuminate\Http\RedirectResponse
     */
    private function createPrenotazioneIfNotExsists($cod_fiscale_prenotante, $cod_fiscale_paziente, $email, $tampone_scelto, $data_prenotazione, $data_tampone, $id_lab, $nome_paziente = null, $cognome_paziente = null, $numero_cellulare = null, $citta_residenza = null, $provincia_residenza = null) {
        // Controllo sull'esistenza di una prenotazione uguale
        if(Prenotazione::existsPrenotazione($cod_fiscale_prenotante, $cod_fiscale_paziente, $tampone_scelto->id, $data_tampone, $id_lab)) {    // Se esiste una prenotazione...
            return false;
        }


        // A questo punto, se non esiste gia' la stessa prenotazione, viene effettuato l'inserimento nel database
        Prenotazione::insertNewPrenotazione(
            $data_prenotazione,
            $data_tampone,
            $tampone_scelto->id,
            $cod_fiscale_prenotante,
            $email,
            $numero_cellulare,
            $id_lab
        );

        $prenotazione_effettuata = Prenotazione::getPrenotazioneById(DB::getPdo()->lastInsertId()); // ottiene l'ultima prenotazione effettuata
        Paziente::insertNewPaziente(
            $prenotazione_effettuata->id,
            $cod_fiscale_paziente,
            $nome_paziente,
            $cognome_paziente,
            $nome_paziente===null? null : $email,   // Se è presente il nome del paziente, allora questo non è registrato e quindi viene inserita l'email
            $citta_residenza,
            $provincia_residenza
        );

        Referto::insertNewReferto($prenotazione_effettuata->id, $cod_fiscale_paziente);
        return true;
    }


    /**
     * Inserisce il questionario anamnesi nel database.
     */
    private function createQuestionarioAnamnesi($cod_fiscale_paziente) {
        // Generazione del token
        $token = null;
        do {
            $token = Str::uuid()->toString();
        } while(QuestionarioAnamnesi::exsistsQuestionarioAnamnesiByToken($token));

        // Inserimento questionario nel database
        $prenotazione_effettuata = Prenotazione::getLastPrenotazione();

        QuestionarioAnamnesi::upsertNewQuestionarioAnamnesi(
            $prenotazione_effettuata->id,
            $cod_fiscale_paziente,
            $token, 0,
            // Info del form
            null, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0,
            null
        );
    }



    /**
     * Prepara le date e le variabili per la generazione di un calendario prenotazione
     * @param $id_lab
     * @return array
     */
    public static function preparaCalendario($id_lab)
    {
        $calendario = null;
        $capienza_lab = 0;
        try {
            $calendario = CalendarioDisponibilita::getCalendarioDisponibilitaByIdLaboratorio($id_lab);
            $capienza_lab = Laboratorio::getCapienzaById($id_lab);
        }
        catch(QueryException $ex) {
            abort(500, 'Il database non risponde.');
        }

        $da_giorni_a_numeri = [
            'lunedi' => 0,
            'martedi' => 1,
            'mercoledi' => 2,
            'giovedi' => 3,
            'venerdi' => 4,
            'sabato' => 5,
            'domenica' => 6
        ];

        $orari = [];
        $boolean_calendario = [false, false, false, false, false, false, false];
        foreach ($calendario as $c) {
            $boolean_calendario[$da_giorni_a_numeri[$c->giorno_settimana]] = true;
            $orari[$da_giorni_a_numeri[$c->giorno_settimana]] = intval($c->oraChiusura);
        }

        $giorno = date('N') - 1; // ottengo il giorno della settimana odierno sotto forma di numero
        $giorno_datetime = date('Y-m-d'); // ottengo la data odierna

        return [
            'capienza_lab' => $capienza_lab,
            'orari' => $orari,
            'boolean_calendario' => $boolean_calendario,
            'giorno' => $giorno,
            'giorno_datetime' => $giorno_datetime
        ];
    }


    /**
     * Genera un calendario per le prenotazioni di un laboratorio. Il giorno corrente
     * è prenotabile se e solo se l'orario corrente è minore dell'orario di chiusura
     * meno 3 ore, quindi è possibile prenotare un tampone per il giorno stesso entro
     * massimo 3 ore dalla chiusura del laboratorio. Verranno mostrate le date solo
     * di giorni con posti di prenotazione ancora disponibili.
     * @param Request $request
     * @param $id_lab
     * @return array
     */
    private function generaCalendarioLaboratorio(Request $request, $id_lab)
    {
        $r = self::preparaCalendario($id_lab);
        $boolean_calendario = $r['boolean_calendario'];
        $giorno = $r['giorno'];
        $orari = $r['orari'];
        $giorno_datetime = $r['giorno_datetime'];
        $capienza_lab = $r['capienza_lab'];

        // conterrà il calendario generato
        $nuovo_calendario = [];

        /* Il primo controllo è fuori dal ciclo perché ha un controllo in più. Ovvero
         * se è possibile prenotare per il giorno corrente, bisogna confrontare l'ora
         * di chiusura con l'ora attuale. */
        try {
            if ($boolean_calendario[$giorno]) {
                $ora = intval(Carbon::now()->format('H'));
                if ($ora < ($orari[$giorno] - 3)) {
                    if (Prenotazione::getPrenotazioniByIdEData($id_lab, $giorno_datetime) < $capienza_lab) {
                        array_push($nuovo_calendario, $giorno_datetime);
                    }
                }
            }
            $giorno = ($giorno + 1) % 7;
            $giorno_datetime = date('Y-m-d', strtotime($giorno_datetime .' +1 day'));

            // intervallo di 2 settimane, 14 giorni
            $i=0;
            while ($i < self::INTERVALLO_TEMPORALE) {
                if ($boolean_calendario[$giorno]) {
                    if (Prenotazione::getPrenotazioniByIdEData($id_lab, $giorno_datetime) < $capienza_lab) {
                        array_push($nuovo_calendario, $giorno_datetime);
                        $i++;
                    }
                }
                $giorno = ($giorno + 1) % 7;
                $giorno_datetime = date('Y-m-d', strtotime($giorno_datetime .' +1 day'));
            }
        }
        catch(QueryException $ex) {
            abort(500, 'Il database non risponde.');
        }

        return $nuovo_calendario;
    }


    /**
     * Ottiene le prenotazioni di tamponi effettuate da me per me, per terzi e da terzi per me
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function visualizzaCalendariPrenotazione(Request $request)
    {
        $prenotazioni_mie = null;
        $prenotazioni_per_terzi = null;
        $prenotazioni_da_terzi = null;
        try {
            // ottengo il codice fiscale dell'utente
            $utente = User::getById($request->session()->get('LoggedUser'));
            // ottengo tutte le tipologie di prenotazioni che interessano l'utente
            $prenotazioni_mie = Prenotazione::getPrenotazioni($utente->codice_fiscale);
            $prenotazioni_per_terzi = Prenotazione::getPrenotazioniPerTerzi($utente->codice_fiscale);

            //start bug fix
            $pazienti = Paziente::getQueryForAllPazienti()->get();
            foreach ($pazienti as $paziente) {
                foreach ($prenotazioni_per_terzi as $prenotazione_per_terzo)
                if ($paziente->cf_paziente === $prenotazione_per_terzo->cf_paziente) {
                    $prenotazione_per_terzo->nome_paziente = $paziente->nome_paziente;
                    $prenotazione_per_terzo->cognome_paziente = $paziente->cognome_paziente;
                    break;
                }
            }
            //end bug fix

            $prenotazioni_da_terzi = Prenotazione::getPrenotazioniDaTerzi($utente->codice_fiscale);
        }
        catch(QueryException $ex) {
            abort(500, 'Il database non risponde.');
        }

        return view('calendarioPrenotazioni', compact('prenotazioni_mie', 'prenotazioni_per_terzi', 'prenotazioni_da_terzi'));
    }


    /**
     * Invia una email di notifica a chi ha ricevuto una prenotazione tampone da un'altra persona
     * @param $email // email del destinatario della notifica
     * @param $nome_prenotante // nome di chi ha effettuato la prenotazione
     * @param $nome_laboratorio // nome del laboratorio in cui è stata effettuata la prenotazione
     * @param $citta_lab // città in cui si trova il laboratorio
     * @param $data_tampone // data prenotata in cui effettuare il tampone
     * @param $costo // costo complessivo
     */
    static function inviaNotificaPrenotazioneDaTerzi($nome, $email, $nome_prenotante, $nome_laboratorio, $citta_lab, $data_tampone, $costo)
    {
        $details = [
            'greeting' => 'Nuova prenotazione tampone su TicketYourTest',
            'body_1' => 'Prenotazione effettuata per: ' . $nome,
            'body_2' => 'Da: ' . $nome_prenotante,
            'body_3' => 'Prevista in data: ' . $data_tampone,
            'body_4' => 'Presso il laboratorio: ' . $nome_laboratorio . ', '.$citta_lab,
            'body_5' => 'Costo complessivo: € ' . $costo,
            'actiontext' => 'Guarda le tue prenotazioni',
            'actionurl' => url('/calendarioPrenotazioni'),
            'lastline' => 'Grazie per aver scelto TicketYourTest'
        ];

        Notification::route('mail', $email)->notify(new NotificaEmail($details));
    }


    /**
     * Elimina tutte le prenotazione passate in input nella richiesta.
     * Schema array prenotazioni:
     * 0 => ['id_prenotazione' => 'numero', 'codice_fiscale' => 'codice'],
     * 1 => ['id_prenotazione' => 'numero', 'codice_fiscale' => 'codice'],
     * ...
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|void
     */
    public function annullaPrenotazioni(Request $request)
    {
        $prenotazioni = $request->input('prenotazioni');
        try {
            foreach ($prenotazioni as $prenotazione) {
                Paziente::deletePaziente($prenotazione['codice_fiscale'], $prenotazione['id_prenotazione']);
                Prenotazione::deletePrenotazione($prenotazione['id_prenotazione']);
            }
        }
        catch(QueryException $ex) {
            abort(500, 'Il database non risponde.');
        }
        return $this->visualizzaCalendariPrenotazione($request);
    }


    /**
     * Prepara le informazioni da passare alla vista per effettuare il checkout.
     * @param int $id_prenotazione L'id della prenotazione effettuata
     * @param int $id_laboratorio L'id del laboratorio presso cui verra' effettuato il tampone
     * @param string $nome_tampone Il tampone scelto
     * @return array Le informazioni per il checkout
     */
    private function preparaInfoCheckout($id_prenotazione, $id_laboratorio, $nome_tampone) {
        $prenotazione_e_paziente = Paziente::getPrenotazioneEPazienteById($id_prenotazione);
        $laboratorio = Laboratorio::getById($id_laboratorio);
        $tampone_proposto = TamponiProposti::getTamponePropostoLabAttivoById($id_laboratorio, $nome_tampone);

        return [
            'id_prenotazione' => $id_prenotazione,
            'nome_paziente' => $prenotazione_e_paziente->nome_paziente,
            'cognome_paziente' => $prenotazione_e_paziente->cognome_paziente,
            'id_laboratorio' => $id_laboratorio,
            'nome_laboratorio' => $laboratorio->nome,
            'nome_tampone' => $nome_tampone,
            'costo_tampone' => $tampone_proposto->costo
        ];
    }
}
