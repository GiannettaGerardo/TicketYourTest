<?php

namespace App\Http\Controllers;

use App\Models\CittadinoPrivato;
use App\Models\DatoreLavoro;
use App\Models\MedicoMG;
use Illuminate\Http\Request;

/**
 * Class ProfiloUtente
 * Controller per gestire il profilo personale di un utente
 * @package App\Http\Controllers
 */
class ProfiloUtente extends Controller
{
    const CITTADINO_PRIVATO = 1;        // costante per indicare il cittadino privato
    const DATORE_LAVORO = 2;            // costante per indicare il datore di lavoro
    const MEDICO_MEDICINA_GENERALE = 3; // costante per indicare il medico di medicina generale
    const LABORATORIO_ANALISI = 4;      // costante per indicare il laboratorio di analisi

    /**
     * Ritorna la vista del profilo personale dell'utente con le sue informazioni
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function visualizzaProfiloUtente(Request $request)
    {
        $id_utente = $request->session()->get('LoggedUser');
        $flag_attore = $request->session()->get('Attore');
        $utente = null;

        if ($flag_attore === self::CITTADINO_PRIVATO) {
            $utente = CittadinoPrivato::getById($id_utente);
        }
        if ($flag_attore === self::DATORE_LAVORO) {
            $utente = DatoreLavoro::getById($id_utente);
        }
        if ($flag_attore === self::MEDICO_MEDICINA_GENERALE) {
            $utente = MedicoMG::getById($id_utente);
        }
        // aggiungere il laboratorio

        //return view('vista', compact('utente'));
    }
}
