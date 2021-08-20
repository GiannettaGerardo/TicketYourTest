<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Http\Controllers\Attore;

class CittadinoMid
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
            if($request->session()->get('Attore')===Attore::CITTADINO_PRIVATO) {
                return $next($request);
            }
        }

        return redirect('login');
    }
}
