<?php

namespace App\Http\Controllers;

use App\Models\CittadinoPrivato;
use App\Models\DatoreLavoro;
use App\Models\MedicoMG;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Class RegisterController
 * Controller per gestire le registrazioni dei diversi tipi di utenti
 * @package App\Http\Controllers
 */
class RegisterController extends Controller
{
    /**
     * Raggruppa le validazioni dei dati comuni a tutte le registrazioni
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
            'psw' => 'required|min:8|max:40',
            'psw-repeat' => 'required|min:8|max:40'
        ]);
    }

    /**
     * Raggruppa diversi dati presi in input dalle viste di registrazione
     * @param Request $request
     * @return array // array di dati di input
     */
    private function generalInput(Request $request) {
        $input = [];
        $input['codice_fiscale'] = $request->input('cf');
        $input['nome'] = $request->input('nome');
        $input['cognome'] = $request->input('cognome');
        $input['citta_residenza'] = $request->input('citta_residenza');
        $input['provincia_residenza'] = $request->input('provincia_residenza');
        $input['email'] = $request->input('email');
        $input['password'] = $request->input('psw');
        $input['password_repeat'] = $request->input('psw-repeat');
        return $input;
    }

    /**
     * Effettua la registrazione del cittadino privato
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function cittadinoPrivatoRegister(Request $request)
    {
        $this->validation($request);

        $input = $this->generalInput($request);

        // check password inserita male in input
        if ($input['password'] !== $input['password_repeat']) {
            return back()->with('psw-repeat-error', 'la password ripetuta non corrisponde a quella inserita');
        }

        // check di esistenza pregressa dell'utente nel database
        $cittadino_esistente = CittadinoPrivato::getByEmail($input['email']);
        if ($cittadino_esistente) {
            return back()->with('email-already-exists', 'l\'email esiste già');
        }

        // inserimento nel database dei dati
        User::insertNewUtenteRegistrato($input['codice_fiscale'], $input['nome'], $input['cognome'], $input['citta_residenza'], $input['provincia_residenza'], $input['email'], $input['password']);
        CittadinoPrivato::insertNewCittadino($input['codice_fiscale']);

        return back()->with('register-success', 'Registrazione avvenuta con successo');
    }

    /**
     * Effettua la registrazione del medico di medicina generale
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function medicoMedicinaGeneraleRegister(Request $request)
    {
        $this->validation($request);
        $validation = $request->validate([ 'iva' => 'required|min:11|max:11' ]);

        $input = $this->generalInput($request);
        $input['partita_iva'] = $request->input('iva');

        // check password inserita male in input
        if ($input['password'] !== $input['password_repeat']) {
            return back()->with('psw-repeat-error', 'la password ripetuta non corrisponde a quella inserita');
        }

        // check di esistenza pregressa dell'utente nel database
        $medico_esistente = MedicoMG::getByEmail($input['email']);
        if ($medico_esistente) {
            return back()->with('email-already-exists', 'l\'email esiste già');
        }

        // inserimento nel database dei dati
        User::insertNewUtenteRegistrato($input['codice_fiscale'], $input['nome'], $input['cognome'], $input['citta_residenza'], $input['provincia_residenza'], $input['email'], $input['password']);
        MedicoMG::insertNewMedico($input['codice_fiscale'], $input['partita_iva']);

        return back()->with('register-success', 'Registrazione avvenuta con successo');
    }

    /**
     * Effettua la registrazione del datore di lavoro
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function datoreLavoroRegister(Request $request)
    {
        $this->validation($request);
        $validation = $request->validate([
            'iva' => 'required|min:11|max:11',
            'nome_azienda' => 'required|max:50',
            'citta_sede_aziendale' => 'required|max:40',
            'provincia_sede_aziendale' => 'required|max:40'
        ]);

        $input = $this->generalInput($request);
        $input['partita_iva'] = $request->input('iva');
        $input['nome_azienda'] = $request->input('nome_azienda');
        $input['citta_azienda'] = $request->input('citta_sede_aziendale');
        $input['provincia_azienda'] = $request->input('provincia_sede_aziendale');

        // check password inserita male in input
        if ($input['password'] !== $input['password_repeat']) {
            return back()->with('psw-repeat-error', 'la password ripetuta non corrisponde a quella inserita');
        }

        // check di esistenza pregressa dell'utente nel database
        $datore_esistente = DatoreLavoro::getByEmail($input['email']);
        if ($datore_esistente) {
            return back()->with('email-already-exists', 'l\'email esiste già');
        }

        // inserimento nel database dei dati
        User::insertNewUtenteRegistrato($input['codice_fiscale'], $input['nome'], $input['cognome'], $input['citta_residenza'], $input['provincia_residenza'], $input['email'], $input['password']);
        DatoreLavoro::insertNewDatore($input['codice_fiscale'], $input['partita_iva'], $input['nome_azienda'], $input['citta_azienda'], $input['provincia_azienda']);

        return back()->with('register-success', 'Registrazione avvenuta con successo');
    }
}
