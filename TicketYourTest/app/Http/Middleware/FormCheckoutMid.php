<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class FormCheckoutMid
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
        if(!$request->session()->has('prenotazioni')) {
            return redirect('/');
        }
        return $next($request);
    }
}
