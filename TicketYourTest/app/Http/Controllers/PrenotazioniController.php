<?php

namespace App\Http\Controllers;

use App\Models\CalendarioDisponibilita;
use App\Models\Laboratorio;
use App\Models\Prenotazione;
use App\Models\Tampone;
use App\Models\TamponiProposti;
use App\Models\Paziente;
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
        $giorni_prenotabili = $this->generaCalendarioLaboratorio($request, $id_lab);
        $tamponi_prenotabili = null;
        $laboratorio_scelto = null;

        try {
            $utente_prenotante = User::getById($request->session()->get('LoggedUser'));
            $tamponi_prenotabili = TamponiProposti::getTamponiPropostiByLaboratorio($id_lab);
            $laboratorio_scelto = Laboratorio::getById($id_lab);
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
     * Effettua la prenotazione singola di un tampone in un dato laboratorio.
     * @param Request $request
     */
    public function prenota(Request $request) {
        // Validazione dell'input
        $request->validate([
            'email_prenotante' => 'required|email',
            'numero_cellulare' => 'required|min:10|max:10',
            'data_tampone' => 'required',
            'tampone' => 'required'
        ]);

        // Ottenimento delle informazioni dal form
        $id_lab = $request->input('id_lab');
        $cod_fiscale_prenotante = $request->input('cod_fiscale');
        $email = $request->input('email_prenotante');
        $numero_cellulare = $request->input('numero_cellulare');
        $data_tampone = $request->input('data_tampone');

        try {
            $tampone_scelto = Tampone::getTamponeByNome($request->input('tampone'));
        }
        catch(QueryException $ex) {
            abort(500, 'Il database non risponde');
        }


        // Inserimento delle informazioni nel database
        try{
            $this->createPrenotazioneIfNotExsists(
                $cod_fiscale_prenotante,
                null,
                null,
                $cod_fiscale_prenotante,
                $email,
                $numero_cellulare,
                null,
                null,
                $tampone_scelto,
                Carbon::now()->format('Y-m-d'),
                $data_tampone,
                $id_lab
            );
        }
        catch(QueryException $ex) {
            abort(500, 'Il database non risponde');
        }

        return back()->with('prenotazione-success', 'La prenotazione del tampone e\' stata effettuata con successo!');
    }


    /**
     * Effettua la prenotazione di un tampone per terzi
     * @param Request $request
     */
    public function prenotaPerTerzi(Request $request) {
        // Validazione dell'input
        $request->validate([
            'nome' => 'required|max:30',
            'cognome' => 'required|max:30',
            'cod_fiscale' => 'required|min:16|max:16',
            'email' => 'required|email',
            'numero_cellulare' => 'required|min:10|max:10',
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

        try {
            $tampone_scelto = Tampone::getTamponeByNome($request->input('tampone'));
            $utente = User::getById($request->session()->get('LoggedUser'));
        }
        catch(QueryException $ex) {
            abort(500, 'Il database non risponde');
        }

        // Inserimento delle informazioni nel database
        try{
            $this->createPrenotazioneIfNotExsists(
                $utente->cod_fiscale,
                $nome_paziente,
                $cognome_paziente,
                $cod_fiscale_paziente,
                $email,
                $numero_cellulare,
                $citta_residenza_paziente,
                $provincia_residenza_paziente,
                $tampone_scelto,
                Carbon::now()->format('Y-m-d'),
                $data_tampone,
                $id_lab
            );
        }
        catch(QueryException $ex) {
            abort(500, 'Il database non risponde');
        }

    }


    /**
     * Inserisce la prenotazione nella tabella 'prenotazioni' e il paziente nella tabella 'pazienti',
     * preoccupandosi di controllare che questa prenotazione non esista gia'.
     * @param $cod_fiscale_prenotante Il codice fiscale di chi effettua la prenotazione
     * @param null $nome_paziente Il nome del paziente
     * @param null $cognome_paziente Il cognome del paziente
     * @param $cod_fiscale_paziente Il codice fiscale del paziente
     * @param $email L'email dove ricevere l'avviso
     * @param $numero_cellulare Il numero di cellulare
     * @param null $citta_residenza La citta' di residenza del paziente
     * @param null $provincia_residenza La provincia di residenza del paziente
     * @param $tampone_scelto Il tampone scelto
     * @param $data_prenotazione La data in cui deve essere registrata la prenotazione
     * @param $data_tampone La data in cui deve essere effettuato il tampone
     * @param $id_lab L'id del laboratorio presso cui effettuare il tampone
     * @return \Illuminate\Http\RedirectResponse|void
     */
    private function createPrenotazioneIfNotExsists($cod_fiscale_prenotante, $nome_paziente = null, $cognome_paziente = null, $cod_fiscale_paziente, $email, $numero_cellulare, $citta_residenza = null, $provincia_residenza = null, $tampone_scelto, $data_prenotazione, $data_tampone, $id_lab) {
        // Controllo sull'esistenza di una prenotazione uguale
        if(Prenotazione::existsPrenotazione($cod_fiscale_prenotante, $cod_fiscale_paziente, $tampone_scelto->id, $data_tampone, $id_lab)) {    // Se esiste una prenotazione con quei dati...
            return back()->with('prenotazione-esistente', 'E\' stata gia\' effettuata una prenotazione con questi dati!');
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
            $prenotazioni_da_terzi = Prenotazione::getPrenotazioniDaTerzi($utente->codice_fiscale);
        }
        catch(QueryException $ex) {
            abort(500, 'Il database non risponde.');
        }
//dd($prenotazioni_mie);
        return view('calendarioPrenotazioni', compact('prenotazioni_mie', 'prenotazioni_per_terzi', 'prenotazioni_da_terzi'));
    }


    /**
     * Invia una email di notifica a chi ha ricevuto una prenotazione tampone da un'altra persona
     * @param $email // email del destinatario della notifica
     * @param $nome_prenotante // nome di chi ha effettuato la prenotazione
     * @param $nome_laboratorio // nome del laboratorio in cui è stata effettuata la prenotazione
     * @param $citta_lab // città in cui si trova il laboratorio
     * @param $data_tampone // data prenotata in cui effettuare il tampone
     */
    static function inviaNotificaPrenotazioneDaTerzi($email, $nome_prenotante, $nome_laboratorio, $citta_lab, $data_tampone)
    {
        $details = [
            'greeting' => 'Ciao. Hai ricevuto una nuova prenotazione tampone su TicketYourTest!',
            'body_1' => 'Prenotazione effettuata da ' . $nome_prenotante,
            'body_2' => 'Prevista per la data ' . $data_tampone,
            'body_3' => 'Presso il laboratorio ' . $nome_laboratorio . ', '.$citta_lab,
            'actiontext' => 'Guarda le tue prenotazioni',
            'actionurl' => url('/calendarioPrenotazioni'),
            'lastline' => 'Grazie per aver scelto TicketYourTest'
        ];

        Notification::route('mail', $email)->notify(new NotificaEmail($details));
    }
}
