<?php

namespace App\Http\Middleware;

use App\Models\PrenotazioniModel\Prenotazione;
use App\Models\PrenotazioniModel\QuestionarioAnamnesi;
use Carbon\Carbon;
use Closure;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class FormQuestionarioAnamnesiMid
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $questionario = null;
        $prenotazione = null;
        $token = $request->route('token');

        try {
            // Check sull'esistenza del token
            $questionario = QuestionarioAnamnesi::getQuestionarioAnamnesiByToken($token);
            if($token===null or $questionario===null) {
                return redirect('questionario-anamnesi-error')->with('questionario-inesistente', 'Il questionario anamnesi richiesto non esiste!');
            }

            // Check sul questionario non ancora compilabile
            $prenotazione = Prenotazione::getPrenotazioneById($questionario->id_prenotazione);
            if( strtotime($prenotazione->data_tampone . '- 3 days') > strtotime(now()->toString()) ) {
                return redirect('questionario-anamnesi-error')->with('questionario-non-compilabile', 'Il questionario anamnesi non e\' ancora compilabile! Potrai compilarlo dai 3 giorni precedenti alla data in cui dovrai effettuare il tampone.');
            }

            // Check sul questionario non piu' compilabile
            /*
             * Viene controllato il campo relativo al token. Se e' scaduto, allora il questionario non e' piu'
             * compilabile.
             */
            if($questionario->token_scaduto != 0) {
                return redirect('questionario-anamnesi-error')->with('questionario-gia-compilato', 'Il questionario anamnesi richiesto e\' stato gia\' compilato oppure non e\' piu\' possibile compilarlo!');
            }
        }
        catch(QueryException $ex) {
            abort(500, 'Il database non risponde.');
        }

        // Questionario anamnesi
        return $next($request);
    }
}
