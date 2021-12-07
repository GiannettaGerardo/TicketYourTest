<?php

namespace App\Http\Controllers;

use App\Models\Transazioni;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class PagamentiContanti extends Controller
{
    /**
     * Ritorna la lista contenente le persone che devono pagare in contanti
     * e che hanno già pagato in contanti
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
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


    /**
     * Salva il pagamento in contanti di una persona che ha fatto il tampone
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function salvaPagamento(Request $request)
    {
        $id_transazione = $request->input('id_transazione');
        try {
            Transazioni::setPagamentoEffettuato($id_transazione, true);
        }
        catch (QueryException $e) {
            abort(500, 'Il database non risponde');
        }
        return $this->getListaUtenti($request);
    }
}
