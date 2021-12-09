<?php

namespace App\Http\Middleware;

use App\Http\Controllers\Attore;
use Closure;
use Illuminate\Http\Request;

class CittadinoDatoreMid
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
        if($request->session()->has('Attore')) {
            $attore = $request->get('Attore');

            if($attore===Attore::CITTADINO_PRIVATO || $attore===Attore::DATORE_LAVORO) {
                return $next($request);
            }
        }

        return redirect('login');

    }
}
