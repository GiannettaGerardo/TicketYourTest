<?php

namespace App\Http\Controllers;

use App\Models\Paziente;
use App\Models\Referto;
use App\Notifications\NotificaRefertoTampone;
use App\Utility\DataMapComunicaRisultatoTamponeAdASL;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
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

        try {
            // Inserimento nel DB
            Referto::updateRefertoByIdPrenotazioneCfPaziente($id_prenotazione, $cf_paziente, $esito_tampone, Carbon::now()->format('Y-m-d'), $quantita);
            $this->inviaPositivoAdASL($esito_tampone, $id_prenotazione);
        }
        catch(QueryException $ex) {
            abort(500, 'Il database non risponde.');
        }

        return back()->with('referto-success', 'Il referto e\' stato creato con successo!');
    }


    /**
     * Utilizza l'API fornita dall'ASL per inviargli i dati dei pazienti risultati:
     * - positivi
     * - indeterminati
     *
     * @param $esito_tampone // stringa che può essere una tra ('positivo', 'negativo', 'indeterminato')
     * @param $id_prenotazione // id della prenotazione del paziente
     * @throws QueryException
     */
    private function inviaPositivoAdASL($esito_tampone, $id_prenotazione)
    {
        if ($esito_tampone !== 'negativo') {
            $dati_per_API = Paziente::getPrenotazioneEPazienteById($id_prenotazione);
            // ToDo testare effettuando una prenotazione in giornata
            ASLapi::comunicaRisultatoTamponeAdASL(self::mapData($dati_per_API));
        }
    }


    /**
     * Mappa i dati ritornati dal metodo model Paziente::getPrenotazioneEPazienteById in un oggetto
     * fatto appositamente per contenere questi dati
     * @param $dati_per_API // dati ritornati dal metodo model Paziente::getPrenotazioneEPazienteById
     * @return DataMapComunicaRisultatoTamponeAdASL
     */
    private static function mapData($dati_per_API): DataMapComunicaRisultatoTamponeAdASL
    {
        $data = new DataMapComunicaRisultatoTamponeAdASL();
        $data->setCfPaziente($dati_per_API->cf_paziente);
        $data->setNome($dati_per_API->nome_paziente);
        $data->setCognome($dati_per_API->cognome_paziente);
        $data->setCittaResidenza($dati_per_API->citta_residenza_paziente);
        $data->setProvinciaResidenza($dati_per_API->provincia_residenza_paziente);
        $data->setNomeLaboratorio($dati_per_API->nome_laboratorio);
        $data->setProvinciaLaboratorio($dati_per_API->provincia_laboratorio);
        return $data;
    }


    /**
     * Carica il pdf del referto e lo restituisce insieme al suo nome
     *
     * @param $id_referto // id del referto da ottenere
     * @return array indici:
     * 'pdf' => contiene il pdf del referto caricato;
     * 'dati_collection_referto' => oggetto Collection contenente i dati del referto
     */
    private function getReferto($id_referto): array
    {
        $referto = null;
        try {
            $referto = Referto::getRefertoById($id_referto);
        }
        catch(QueryException $ex) {
            abort(500, 'Il database non risponde.');
        }
        $pdf = PDF::loadView('referto', compact('referto'));

        return [
            'pdf' => $pdf,
            'dati_collection_referto' => $referto
        ];
    }


    /**
     * Ottiene il referto dall'id, lo scarica e lo salva nella cartella
     * public/files/ . Successivamente invia l'email al paziente contenente
     * in allegato il pdf del referto salvato e una volta fatto, elimina
     * il pdf salvato dalla cartella public/files/
     *
     * @param $id_referto // id del referto da ottenere
     */
    public function inviaNotificaRefertoPaziente($id_referto)
    {
        $pdf = $this->getReferto($id_referto);
        $referto = $pdf['dati_collection_referto'];
        $email_paziente = $this->getPazienteAndHisEmail($referto);

        $content = $pdf['pdf']->download()->getOriginalContent();
        $nome = 'referto'.$referto->cf_paziente.'.pdf';
        $path = 'public/files/' . $nome;
        Storage::put($path, $content) ;

        $this->invioMailPaziente(
            [
                'file_path' => $path,
                'file_name' => $nome,
                'email_paziente' => $email_paziente
            ]
        );
        Storage::delete($path);
    }


    /**
     * Ottiene tutti i pazienti e su di essi cerca e ritorna l'email associata al
     * referto passato in input
     * 
     * @param $referto // oggetto Collection contenente i dati di un referto
     * @return null|string
     */
    private function getPazienteAndHisEmail($referto)
    {
        $email_paziente = null;
        try {
            $pazienti = Paziente::getQueryForAllPazienti()->get();
            foreach ($pazienti as $paziente) {
                if ($paziente->cf_paziente === $referto->cf_paziente) {
                    $email_paziente = $paziente->email_paziente;
                    break;
                }
            }
            if ($email_paziente === null) {
                abort(500, 'Il paziente non ha una email');
            }
        }
        catch(QueryException $ex) {
            abort(500, 'Il database non risponde.');
        }
        return $email_paziente;
    }


    /**
     * Invia una email al paziente contenente il suo referto in allegato
     * @param mixed $data indici:
     * 'file_path' => il percorso del file pdf da mandare;
     * 'file_name' => il nome del file pdf da mandare;
     * 'email_paziente' => l'email del paziente a cui mandare il file
     */
    private function invioMailPaziente($data)
    {
        $details = [
            'actiontext' => '',
            'actionurl' => '',
            'file_referto_path' => $data['file_path'],
            'file_referto_nome' => $data['file_name']
        ];

        Notification::route('mail', $data['email_paziente'])->notify(new NotificaRefertoTampone($details));
    }


    /**
     * Restituisce una vista sotto forma di pdf per visualizzare un referto
     * @param $id // l'id del referto da visualizzare
     * @return mixed
     */
    public function visualizzaReferto($id) {
        $pdf = $this->getReferto($id);
        return $pdf['pdf']->stream('referto'.$pdf['dati_collection_referto']->cf_paziente.'.pdf');
    }
}
