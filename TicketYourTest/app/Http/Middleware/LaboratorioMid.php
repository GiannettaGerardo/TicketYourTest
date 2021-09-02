<?php

namespace App\Http\Middleware;

use App\Http\Controllers\Attore;
use Closure;
use Illuminate\Http\Request;

class LaboratorioMid
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
            if ($request->session()->get('Attore') === Attore::LABORATORIO_ANALISI) {
                return $next($request);
            }
        }
        return redirect('login');
    }
}
