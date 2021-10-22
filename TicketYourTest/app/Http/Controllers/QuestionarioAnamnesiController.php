<?php

namespace App\Http\Controllers;

use App\Models\Laboratorio;
use App\Models\Paziente;
use App\Models\QuestionarioAnamnesi;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

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

        return view('formTestAnamnesi', compact('laboratorio', 'prenotazione_e_paziente'));
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
                $sintomi_inseriti['cefalea']
            );

            return redirect('/calendarioPrenotazioni')->with('questionario-anamnesi-success', 'Il questionario anamnesi e\' stato compilato correttamente!');
        }
        catch(QueryException $ex) {
            abort(500, 'Il database non risponde.');
        }
    }
}
