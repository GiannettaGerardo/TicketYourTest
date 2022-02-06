<?php

namespace App\Http\Middleware;

use App\Http\Controllers\UserController\Attore;
use Closure;
use Illuminate\Http\Request;

class CittadinoDatoreMedicoMid
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
        if($request->session()->has('LoggedUser')) {
            $attore = $request->session()->get('Attore');
            if (($attore===Attore::CITTADINO_PRIVATO) or
                ($attore===Attore::DATORE_LAVORO) or
                ($attore===Attore::MEDICO_MEDICINA_GENERALE))
            {
                return $next($request);
            }
        }

        return redirect('login');
    }
}
