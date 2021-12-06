<?php

namespace App\Http\Controllers;

use App\Models\Transazioni;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class PagamentiContanti extends Controller
{
    public function getListaUtenti(Request $request)
    {
        $listaUtentiPagamentoInContantiNonEffettuato = null;
        $listaUtentiPagamentoInContantiEffettuato = null;
        $idLaboratorio = $request->session()->get('LoggedUser');
        try {
            $listaUtentiPagamentoInContantiNonEffettuato =
                Transazioni::getUtentiConPagamentoContantiByLab($idLaboratorio, 0);

            $listaUtentiPagamentoInContantiEffettuato =
                Transazioni::getUtentiConPagamentoContantiByLab($idLaboratorio, 1);
        }
        catch (QueryException $e) {
            abort(500, 'Il database non risponde');
        }
        return view('registroPagamentiLab', compact(
            'listaUtentiPagamentoInContantiNonEffettuato',
            'listaUtentiPagamentoInContantiEffettuato'));
    }
}
