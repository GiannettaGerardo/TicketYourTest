<?php

namespace App\Http\Controllers;

use App\Models\CittadinoPrivato;
use App\Models\DatoreLavoro;
use App\Models\MedicoMG;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;

/**
 * Class ProfiloUtente
 * Controller per gestire il profilo personale di un utente
 * @package App\Http\Controllers
 */
class ProfiloUtente extends Controller
{
    private const CITTADINO_PRIVATO = 1;        // costante per indicare il cittadino privato
    private const DATORE_LAVORO = 2;            // costante per indicare il datore di lavoro
    private const MEDICO_MEDICINA_GENERALE = 3; // costante per indicare il medico di medicina generale
    private const LABORATORIO_ANALISI = 4;      // costante per indicare il laboratorio di analisi

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

    /**
     * Modifica le informazioni del profilo di un utente
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function modificaProfiloUtente(Request $request)
    {
        $id_utente = $request->session()->get('LoggedUser');
        $flag_attore = $request->session()->get('Attore');
        $this->validation($request);
        $input = $this->generalInput($request);

        try {
            User::updateInfo($id_utente, $input['nuovo_codice_fiscale'], $input['nome'], $input['cognome'], $input['citta_residenza'],
                $input['provincia_residenza'], $input['email'], $input['password']);

            if ($flag_attore === self::CITTADINO_PRIVATO) {
                CittadinoPrivato::updateCittadino($input['codice_fiscale_attuale'], $input['nuovo_codice_fiscale']);
            }
            elseif ($flag_attore === self::DATORE_LAVORO) {
                $input['partita_iva'] = $request->input('iva');
                $input['nome_azienda'] = $request->input('nome_azienda');
                $input['citta_azienda'] = $request->input('citta_sede_aziendale');
                $input['provincia_azienda'] = $request->input('provincia_sede_aziendale');
                DatoreLavoro::updateDatore($input['codice_fiscale_attuale'], $input['nuovo_codice_fiscale'], $input['partita_iva'], $input['nome_azienda'], $input['citta_azienda'], $input['provincia_azienda']);
            }
            elseif ($flag_attore === self::MEDICO_MEDICINA_GENERALE) {
                $input['partita_iva'] = $request->input('iva');
                MedicoMG::updateMedico($input['codice_fiscale_attuale'], $input['nuovo_codice_fiscale'], $input['partita_iva']);
            }
        }
        catch(QueryException $ex){
            return back()->with('update-error', 'Errore, modifica non avvenuta.');
        }

        return back()->with('update-success', 'Modifica avvenuta con successo.');
    }

    /**
     * Raggruppa le validazioni dei dati comuni
     * @param Request $request
     */
    private function validation(Request $request) {
        $validation = $request->validate([
            'cf' => 'required|min:16|max:16',
            'nome' => 'required|max:30',
            'cognome' => 'required|max:30',
            'citta_residenza' => 'required|max:50',
            'provincia_residenza' => 'required|max:50',
            'email' => 'required|email',
            'psw' => 'required|min:8|max:40'
        ]);
    }

    /**
     * Raggruppa diversi dati presi in input
     * @param Request $request
     * @return array // array di dati di input
     */
    private function generalInput(Request $request)
    {
        $input = [];
        $input['nuovo_codice_fiscale'] = $request->input('cf');
        $input['codice_fiscale_attuale'] = $request->input('cf_attuale');
        $input['nome'] = $request->input('nome');
        $input['cognome'] = $request->input('cognome');
        $input['citta_residenza'] = $request->input('citta_residenza');
        $input['provincia_residenza'] = $request->input('provincia_residenza');
        $input['email'] = $request->input('email');
        $input['password'] = $request->input('psw');
        return $input;
    }
}
