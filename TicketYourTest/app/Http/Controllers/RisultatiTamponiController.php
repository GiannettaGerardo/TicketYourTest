<?php

namespace App\Http\Controllers;

use App\Models\Paziente;
use App\Models\Referto;
use App\Notifications\NotificaRefertoTampone;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use PDF;
use Illuminate\Http\Request;
use Throwable;

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
        $id_tampone = $request->input('id_tampone');

        // Check sulla quantita'
        if($esito_tampone==='positivo' && $id_tampone===2 && !isset($quantita)) {
            return back()->with('referto-error', 'Se l\'esito e\' positivo bisogna inserire anche la quantita\' di materiale genetico.');
        }

        DB::beginTransaction();
        try {
            // Inserimento nel DB
            Referto::updateRefertoByIdPrenotazioneCfPaziente($id_prenotazione, $cf_paziente, $esito_tampone, Carbon::now()->format('Y-m-d'), $quantita);

            // Invio dei dati del positivo all'ASL tramite apposita API
            $this->inviaPositivoAdASL($esito_tampone, $id_prenotazione);

            // Invio del referto del tampone al paziente
            $referto = Referto::getIdRefertoByIdPrenotazione($id_prenotazione);
            if ($referto === null) {
                abort(501, 'Nessun referto associato alla prenotazione');
            }
            $this->inviaNotificaRefertoPaziente($referto->id);

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
            ASLapi::comunicaRisultatoTamponeAdASL($dati_per_API);
        }
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
        $email_paziente = $referto->email_paziente;

        $content = $pdf['pdf']->download()->getOriginalContent();
        $nome = 'referto'.$referto->cf_paziente.'.pdf';
        $path = 'public/files/' . $nome;
        Storage::put($path, $content);

        $this->invioMailPaziente(
            [
                'file_name' => $nome,
                'email_paziente' => $email_paziente
            ]
        );
        Storage::delete($path);
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
            'actiontext' => 'Storico personale',
            'actionurl' => url('/storicoPrenotazioni'),
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
