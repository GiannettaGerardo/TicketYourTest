<?php

namespace App\Http\Middleware;

use App\Models\QuestionarioAnamnesi;
use Closure;
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
        $token = $request->route('token');

        // Check sull'esistenza del token
        if(! QuestionarioAnamnesi::exsistsQuestionarioAnamnesiByToken($token)) {
            return redirect('questionario-anamnesi-error')->with('questionario-inesistente', 'Il questionario anamnesi richiesto non esiste!');
        }

        // TODO Errori da aggiungere: questionario non ancora compilabile e non piu' compilabile

        return $next($request);
    }
}
