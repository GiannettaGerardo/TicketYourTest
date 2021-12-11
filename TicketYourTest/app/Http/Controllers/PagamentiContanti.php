<?php

namespace App\Http\Controllers;

use App\Models\Transazioni;
use App\Notifications\NotificaEmail;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

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
            $this->inviaRicevutaPagamento();
        }
        catch (QueryException $e) {
            abort(500, 'Il database non risponde');
        }

        return $this->getListaUtenti($request);
    }


    /**
     * @throws QueryException
     */
    private function inviaRicevutaPagamento()
    {

        $details = [
            'greeting' => 'Hai una nuova ricevuta di pagamento',
            'nome_laboratorio' => '',
            'data_di_pagamento' => '',
            'nome_tampone_effettuato' => '',
            'importo_pagato' => ''
        ];

        //Notification::route('mail', $email)->notify(new NotificaEmail($details));
    }
}
