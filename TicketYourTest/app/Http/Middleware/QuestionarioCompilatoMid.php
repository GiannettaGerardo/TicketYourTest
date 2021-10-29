<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class QuestionarioCompilatoMid
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
        /*
         * Se non esiste nella sessione l'id della prenotazione, il questionario non viene visualizzato
         */
        if(!$request->input('id_prenotazione')) {
            return redirect('/');
        }

        return $next($request);
    }
}
