<?php

namespace App\Http\Middleware;

use App\Http\Controllers\UserController\Attore;
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
            if ($request->session()->get('Attore') === Attore::DATORE_LAVORO) {
                return $next($request);
            }
        }
        return redirect('login');
    }
}
