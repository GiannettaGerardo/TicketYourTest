<?php

namespace App\Http\Controllers\UserController;

use App\Http\Controllers\Controller;
use App\Models\UserModel\CittadinoPrivato;
use App\Models\UserModel\DatoreLavoro;
use App\Models\UserModel\MedicoMG;
use App\Models\UserModel\User;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Throwable;

/**
 * Class ProfiloUtente
 * Controller per gestire il profilo personale di un utente
 * @package App\Http\Controllers
 */
class ProfiloUtente extends Controller
{
    /**
     * Raggruppa le validazioni dei dati comuni
     * @param Request $request
     */
    private function validation(Request $request) {
        $validation = $request->validate([
            'cf' => 'required|min:16|max:16',
            'cf_attuale' => 'required|min:16|max:16',
            'nome' => 'required|max:30',
            'cognome' => 'required|max:30',
            'citta_residenza' => 'required|max:50',
            'provincia_residenza' => 'required|max:50',
            'email' => 'required|email'
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
        return $input;
    }


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

        if ($flag_attore === Attore::CITTADINO_PRIVATO) {
            $utente = CittadinoPrivato::getById($id_utente);
        }
        if ($flag_attore === Attore::DATORE_LAVORO) {
            $utente = DatoreLavoro::getById($id_utente);
        }
        if ($flag_attore === Attore::MEDICO_MEDICINA_GENERALE) {
            $utente = MedicoMG::getById($id_utente);
        }

        return view('ProfileView.profilo', compact('utente'));
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
        if ($flag_attore === Attore::DATORE_LAVORO) {
            $validation = $request->validate([
                'iva' => 'required|min:11|max:11',
                'nome_azienda' => 'required|max:50',
                'citta_sede_aziendale' => 'required|max:40',
                'provincia_sede_aziendale' => 'required|max:40'
            ]);
        }
        if ($flag_attore === Attore::MEDICO_MEDICINA_GENERALE) {
            $validation = $request->validate([
                'iva' => 'required|min:11|max:11'
            ]);
        }
        $input = $this->generalInput($request);

        DB::beginTransaction();
        try {
            // aggiorno i dati generali sulla tabella utente
            User::updateInfo($id_utente, $input['nuovo_codice_fiscale'], $input['nome'], $input['cognome'], $input['citta_residenza'],
                $input['provincia_residenza'], $input['email']);

            if ($flag_attore === Attore::CITTADINO_PRIVATO) {
                CittadinoPrivato::updateCittadino($input['codice_fiscale_attuale'], $input['nuovo_codice_fiscale']);
            }
            elseif ($flag_attore === Attore::DATORE_LAVORO) {
                $input['partita_iva'] = $request->input('iva');
                $input['nome_azienda'] = $request->input('nome_azienda');
                $input['citta_azienda'] = $request->input('citta_sede_aziendale');
                $input['provincia_azienda'] = $request->input('provincia_sede_aziendale');
                DatoreLavoro::updateDatore($input['codice_fiscale_attuale'], $input['nuovo_codice_fiscale'], $input['partita_iva'], $input['nome_azienda'], $input['citta_azienda'], $input['provincia_azienda']);
            }
            elseif ($flag_attore === Attore::MEDICO_MEDICINA_GENERALE) {
                $input['partita_iva'] = $request->input('iva');
                MedicoMG::updateMedico($input['codice_fiscale_attuale'], $input['nuovo_codice_fiscale'], $input['partita_iva']);
            }

            DB::commit();
        }
        catch (QueryException $e) {
            DB::rollBack();
            abort(500, 'Il database non risponde.');
        }
        catch (Throwable $e) {
            DB::rollBack();
            abort(500, 'Server error. Manca la connessione.');
        }

        return redirect('/profilo');
    }
}
