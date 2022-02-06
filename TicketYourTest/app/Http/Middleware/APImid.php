<?php

namespace App\Http\Middleware;

use App\Http\Controllers\Api\ASLapi;
use App\Models\APIModel\Token_api;
use Closure;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class APImid
{
    /**
     * Handle an incoming request.
     * Controlla che il token utilizzato nell'url sia autorizzato
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $token = $request->route('token');

        // controlla se la route contiene il token
        if ($token === null) {
            return (new ASLapi())->notFound($request);
        }
        // controlla se il token inserito nella route è autorizzato
        try {
            $tokenDB = Token_api::tokenEsiste($token);
            if ($tokenDB === null) { // il token non è autorizzato
                return response()->json([
                    'data' => 'Utente non autorizzato'
                ], 401);
            }
        }
        catch(QueryException $ex) {
            return response()->json([
                'data' => 'Il database non risponde'
            ], 500);
        }

        return $next($request);
    }
}
