<?php

namespace App\Http\Controllers;

use App\Models\Laboratorio;
use App\Models\Paziente;
use App\Models\QuestionarioAnamnesi;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use PDF;

/**
 * Class QuestionarioAnamnesiController
 */
class QuestionarioAnamnesiController extends Controller
{

    /**
     * Restituisce la vista per visualizzare gli errori relativi alla visualizzazione del questionario anamnesi.
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function visualizzaErroreQuestionarioAnamnesi() {
        return view('questionarioAnamnesiError');
    }


    /**
     * Restituisce la vista per compilare il questionario anamnesi
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function visualizzaFormQuestionarioAnamnesi($token) {
        $questionario = null;
        $prenotazione_e_paziente = null;
        $laboratorio = null;

        try {
            $questionario = QuestionarioAnamnesi::getQuestionarioAnamnesiByToken($token);
            $prenotazione_e_paziente = Paziente::getPrenotazioneEPazienteById($questionario->id_prenotazione);
            $laboratorio = Laboratorio::getById($prenotazione_e_paziente->id_laboratorio);
        }
        catch(QueryException $ex) {
            abort(500, 'Il database non risponde.');
        }

        return view('formTestAnamnesi', compact('laboratorio', 'prenotazione_e_paziente', 'token'));
    }


    /**
     * @param Request $request
     * @param $token
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|void
     */
    public function compilaQuestionario(Request $request, $token) {
        // Check compilazione dati
        $request->validate(
            [
                'motivazione' => 'required',
                'lavoro' => 'required',
                'contatto' => 'required',
                'quindici-giorni-dopo-contatto' => 'required',
                'tampone-fatto' => 'required',
                'isolamento' => 'required',
                'info-contagio-covid' => 'required',
                'sintomi' => 'required'
            ]
        );

        // Ottenimento dell'input
        $motivazione = $request->input('motivazione');
        $lavoro = $request->input('lavoro');
        $contatto = $request->input('contatto');
        $quindici_giorni_dopo_contatto = $request->input('quindici-giorni-dopo-contatto');
        $tampone_fatto = $request->input('tampone-fatto');
        $isolamento = $request->input('isolamento');
        $info_contagio_covid = $request->input('info-contagio-covid');
        $sintomi = $request->input('sintomi');
        $cf_paziente = $request->input('codice_fiscale');
        $id_prenotazione = $request->input('id_prenotazione');

        // Valutazione dei sintomi inseriti dall'utente
        /*
         * Viene creato un array associativo con tutti i sintomi possibili come chiave e un valore che indica se
         * e' stato scelto dall'utente o meno.
         * A quel punto si assegna 1 a quel valore la cui chiave e' presente nell'array "sintomi" (che contiene come valori
         * solo le scelte dell'utente).
         */
        $sintomi_inseriti = [
            'si-febbre' => 0,
            'si-tosse' => 0,
            'si-difficolta-respiratorie' => 0,
            'si-raffreddore' => 0,
            'si-malDiGola' => 0,
            'si-alterazione-gusto' => 0,
            'si-dolori-muscolari' => 0,
            'si-cefalea' => 0
        ];

        foreach($sintomi as $sintomo) {
            $sintomi_inseriti[$sintomo] = 1;
        }

        // Inserimento nel database
        try {
            QuestionarioAnamnesi::upsertNewQuestionarioAnamnesi(
                $id_prenotazione,
                $cf_paziente,
                $token,
                1,
                $motivazione,
                $lavoro==='si' ? 1 : 0,
                $contatto==='si' ? 1 : 0,
                $quindici_giorni_dopo_contatto==='si' ? 1 : 0,
                $tampone_fatto==='si' ? 1 : 0,
                $isolamento==='si' ? 1 : 0,
                $info_contagio_covid==='si' ? 1 : 0,
                $sintomi_inseriti['si-febbre'],
                $sintomi_inseriti['si-tosse'],
                $sintomi_inseriti['si-difficolta-respiratorie'],
                $sintomi_inseriti['si-raffreddore'],
                $sintomi_inseriti['si-malDiGola'],
                $sintomi_inseriti['si-alterazione-gusto'],
                $sintomi_inseriti['si-dolori-muscolari'],
                $sintomi_inseriti['si-cefalea']
            );

            return redirect('/calendarioPrenotazioni')->with('questionario-anamnesi-success', 'Il questionario anamnesi e\' stato compilato correttamente!');
        }
        catch(QueryException $ex) {
            abort(500, 'Il database non risponde.');
        }
    }


    /**
     * Crea il PDF del questionario anamnesi compilato per un paziente di una prenotazione
     * @param Request $request
     * @return mixed // pdf
     */
    public function questionarioCompilato(Request $request)
    {
        $id_prenotazione = $request->input('id_prenotazione');
        $cf_paziente = $request->input('cf_paziente');
        $paziente = null;
        $questionario_compilato = null;

        try {
            // in questo caso l'id della prenotazione fa già riferimento ad un solo paziente specifico
            $paziente = Paziente::getPrenotazioneEPazienteById($id_prenotazione);
            $questionario_compilato = QuestionarioAnamnesi::getQuestionarioByIdCf($id_prenotazione, $cf_paziente);
        }
        catch(QueryException $ex) {
            abort(500, 'Il database non risponde.');
        }

        $questionario = self::preparaQuestionarioPerPDF($paziente, $questionario_compilato);

        $pdf = PDF::loadView('testAnamnesiCompilato', compact('questionario'));
        return $pdf->stream('questionario_anamnesi_'.$questionario['codice_fiscale'].'.pdf');
    }


    /**
     * Formatta come necessario i dati da restituire al PDF del questionario anamnesi compilato
     * @param $paziente // paziente prelevato dal database
     * @param $questionario_compilato  // questionario compilato prelevato dal database
     * @return array
     */
    private static function preparaQuestionarioPerPDF($paziente, $questionario_compilato)
    {
        $questionario = [];
        $formattaSiNo = ['No', 'Si'];   // Array contenente le risposte formattate (0=No, 1=Si)
        $formattaMotivazioni = [
            'sintomi' => 'Presenza di sintomi',
            'contatto' => 'Contatto con positivi',
            'controllo' => 'Controllo',
            'accesso-struttura-sanitaria' => 'Accesso struttra sanitaria',
            'viaggi-trasferta' => 'Viaggi e trasferte',
            'lavoro' => 'Attivita\' lavorativa',
            'sport' => 'Attivita\' sportiva',
            'scuola' => 'Attivita\' scolastica'
        ];

        // Dati paziente
        $questionario['nome_paziente'] = $paziente->nome_paziente;
        $questionario['cognome'] = $paziente->cognome_paziente;
        $questionario['codice_fiscale'] = $paziente->cf_paziente;
        $questionario['citta_residenza'] = $paziente->citta_residenza_paziente;
        $questionario['provincia_residenza'] = $paziente->provincia_residenza_paziente;
        // Motivazione
        $questionario['motivazione'] = $formattaMotivazioni[$questionario_compilato->motivazione];
        // Risposte alle domande, sia risposte impostate a 1 che a 0
        $questionario['lavoro'] = $formattaSiNo[$questionario_compilato->lavoro];
        $questionario['contatto'] = $formattaSiNo[$questionario_compilato->contatto];
        $questionario['quindici_giorni_dopo_contatto'] = $formattaSiNo[$questionario_compilato->{'quindici-giorni-dopo-contatto'}];
        $questionario['tampone_fatto'] = $formattaSiNo[$questionario_compilato->{'tampone-fatto'}];
        $questionario['isolamento'] = $formattaSiNo[$questionario_compilato->isolamento];
        $questionario['contagiato'] = $formattaSiNo[$questionario_compilato->contagiato];
        // Sintomi
        $questionario['sintomi'] = [];
        if ($questionario_compilato->febbre === 1) {
            $questionario['sintomi']['febbre'] = 'Febbre';
        }
        if ($questionario_compilato->tosse === 1) {
            $questionario['sintomi']['tosse'] = 'Tosse';
        }
        if ($questionario_compilato->{'difficolta-respiratorie'} === 1) {
            $questionario['sintomi']['difficolta_respiratorie'] = 'Difficolta\' respiratorie';
        }
        if ($questionario_compilato->raffreddore === 1) {
            $questionario['sintomi']['raffreddore'] = 'Raffreddore';
        }
        if ($questionario_compilato->{'mal-di-gola'} === 1) {
            $questionario['sintomi']['mal_di_gola'] = 'Mal di gola';
        }
        if ($questionario_compilato->{'mancanza-gusto'} === 1) {
            $questionario['sintomi']['mancanza_gusto'] = 'Mancanza di gusto';
        }
        if ($questionario_compilato->{'dolori-muscolari'} === 1) {
            $questionario['sintomi']['dolori_muscolari'] = 'Dolori muscolari';
        }
        if ($questionario_compilato->cefalea === 1) {
            $questionario['sintomi']['cefalea'] = 'Cefalea';
        }
        return $questionario;
    }
}
