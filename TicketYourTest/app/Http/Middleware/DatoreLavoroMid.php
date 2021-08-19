<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class DatoreLavoroMid
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
        if ($request->session()->has('LoggedUser')) {
            if ($request->session()->get('Attore') === 2) {
                return $next($request);
            }
        }
        return redirect('login');
    }
}
