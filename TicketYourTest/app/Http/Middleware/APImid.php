<?php

namespace App\Http\Middleware;

use App\Models\Token_api;
use Closure;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class APImid
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
        /**
         * Controlla che il token utilizzato nell'url sia autorizzato
         */
        $token = $request->route('token');
        try {
            $tokenDB = Token_api::tokenEsiste($token);
            if ($tokenDB === null) {
                abort(401, 'Utente non autorizzato');
            }
        }
        catch(QueryException $ex) {
            abort(500, 'Il database non risponde.');
        }

        return $next($request);
    }
}
