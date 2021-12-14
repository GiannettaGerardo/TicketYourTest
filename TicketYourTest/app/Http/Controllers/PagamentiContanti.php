<?php

namespace App\Http\Controllers;

use App\Models\Paziente;
use App\Models\Transazioni;
use App\Notifications\NotificaEmail;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use \Illuminate\Support\Collection;

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
        $id_laboratorio = $request->session()->get('LoggedUser');
        $id_transazione = $request->input('id_transazione');
        $nome_laboratorio = $request->session()->get('Nome');
        try {
            Transazioni::setPagamentoEffettuato($id_transazione, true);
            $datiEmail = Transazioni::getPazienteByTransazionePerRicevutaPagamento($id_laboratorio, $id_transazione);
            $allPazienti = Paziente::getQueryForAllPazienti()->get();
            $this->mergePaziente($allPazienti, $datiEmail);
            $this->inviaRicevutaPagamento($datiEmail, $nome_laboratorio);
        }
        catch (QueryException $e) {
            abort(500, 'Il database non risponde');
        }
        return $this->getListaUtenti($request);
    }


    /**
     * Invia una notifica email contenente i dati della ricevuta di pagamento
     * di un tampone in laboratorio
     * @param Collection $datiEmail // contiene i dati che servono per creare una ricevuta
     * @param $nome_lab // nome del laboratorio in cui è avvenuto il pagamento
     */
    private function inviaRicevutaPagamento(Collection $datiEmail, $nome_lab)
    {

        $details = [
            'greeting' => 'Hai una nuova ricevuta di pagamento!',
            'nome_laboratorio' => 'Presso il laboratorio: ' . $nome_lab,
            'data_di_pagamento' => 'In data: ' . $datiEmail->data_tampone,
            'nome_tampone_effettuato' => 'Tampone effettuato: ' . $datiEmail->nome_tampone,
            'importo_pagato' => 'Importo pagato: ' . $datiEmail->costo_tampone,
            'id_transazione' => 'Codice univoco della ricevuta: ' . $datiEmail->id_transazione
        ];

        Notification::route('mail', $datiEmail->email_paziente)->notify(new NotificaEmail($details));
    }


    private function mergePaziente($pazienti, $datiEmail) {
        foreach ($pazienti as $paziente) {
            if ($paziente->cf_paziente === $datiEmail->cf_paziente) {
                $datiEmail->email_paziente = $paziente->email_paziente;
                break;
            }
        }
    }
}
