<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\CittadinoPrivato;
use App\Models\DatoreLavoro;
use App\Models\Laboratorio;
use App\Models\MedicoMG;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

/**
 * Class LoginController
 * Controller per gestire il login di diverse tipologie di utenti
 * @package App\Http\Controllers
 */
class LoginController extends Controller
{
    /**
     * Ritorna la vista di login
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function getLoginView(Request $request) {
        if($request->session()->has('redirectTo')) {
            $request->session()->forget('redirectTo');
        }
        $request->session()->put('redirectTo', url()->previous());
        return view('login');
    }


    /**
     * Raggruppa le validazioni dei dati comuni
     * @param Request $request
     */
    private function validation(Request $request) {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'min:8', 'max:40'],
        ]);
    }


    /**
     * Raggruppa diversi dati presi in input
     * @param Request $request
     * @param $email            // & -> passaggio per riferimento
     * @param $password_hash    // & -> passaggio per riferimento
     */
    private function generalInput(Request $request, &$email, &$password_hash) {
        $email = $request->input('email');
        $password_hash = $request->input('password');
    }


    /**
     * Effettua il login decidendo che tipo di utente sta effettuando l'accesso
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function login(Request $request)
    {
        // valido le credenziali ottenute in input dall'utente
        $this->validation($request);
        // memorizzo le credenziali dopo la validazione
        $this->generalInput($request, $email, $password_hash);

        $utente = User::getByEmail($email);
        if (!$utente) {
            $laboratorio_esiste = Laboratorio::getByEmail($email);
            if ($laboratorio_esiste) {
                return $this->authenticateLaboratorio($request, $password_hash, $laboratorio_esiste);
            }
            else {
                $amministratore_esiste = Admin::getByEmail($email);
                if ($amministratore_esiste) {
                    return $this->authenticateAdmin($request, $password_hash, $amministratore_esiste);
                }
                else {
                    return back()->with('email', 'email o password errati');
                }
            }
        }
        else {
            return $this->authenticate($request, $password_hash, $utente);
        }
    }


    /**
     * Stabilisce la tipologia dell'utente che effettua l'accesso e ne crea la sessione
     * @param Request $request
     * @param $password_hash
     * @param $utente
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    private function authenticate(Request $request, $password_hash, $utente)
    {
        if ($utente->attore === Attore::CITTADINO_PRIVATO) {
            return $this->createSession($request, $password_hash, $utente, Attore::CITTADINO_PRIVATO);
        }
        if ($utente->attore === Attore::DATORE_LAVORO) {
            return $this->createSession($request, $password_hash, $utente, Attore::DATORE_LAVORO);
        }
        if ($utente->attore === Attore::MEDICO_MEDICINA_GENERALE) {
            return $this->createSession($request, $password_hash, $utente, Attore::MEDICO_MEDICINA_GENERALE);
        }
        return back()->with('email', 'email o password errati');
    }


    /**
     * Crea la sessione, generalizzando diverse funzionalità per gli utenti
     * @param Request $request
     * @param $password_hash
     * @param $utente
     * @param $attore
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    private function createSession(Request $request, $password_hash, $utente, $attore)
    {
        // controllo se la password è giusta
        if (Hash::check($password_hash, $utente->password)) {
            if ($request->session()->has('LoggedUser')) {
                session()->pull('LoggedUser');
                session()->pull('Attore');
            }
            $request->session()->put('LoggedUser', $utente->id);
            $request->session()->put('Attore', $attore);


            // Inserimento del nome dell'attore che ha fatto il login nella sessione
            if($attore === Attore::AMMINISTRATORE) {
                $request->session()->put('Nome', $utente->nome);
                // L'amministratore effettua il redirect alla pagina principale
                return redirect('/'); // home page
            }
            $request->session()->put('Nome', $utente->nome.' '.$utente->cognome);

            // Gli altri attori, dopo il login, vengono riportati alla pagina precedentemente richiesta
            $redirectTo = '/profilo';
            if($request->session()->has('redirectTo')) {
                $redirectTo = $request->session()->get('redirectTo');
                $request->session()->pull('redirectTo');
            }
            return redirect($redirectTo);
        }
        // ritorno indietro alla pagina di login avvisando che la password inserita è errata
        return back()->with('password', 'password errata');
    }


    /**
     * Permette di effettuare l'accesso ad un utente admin e ne crea la sessione
     * @param Request $request
     * @param $password_hash
     * @param $utente
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    private function authenticateAdmin(Request $request, $password_hash, $utente)
    {
        return $this->createSession($request, $password_hash, $utente, Attore::AMMINISTRATORE);
    }


    /**
     * Permette di effettuare l'accesso ad un utente laboratorio e ne crea la sessione
     * @param Request $request
     * @param $password_hash
     * @param $laboratorio_esiste
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    private function authenticateLaboratorio(Request $request, $password_hash, $laboratorio_esiste)
    {
        // Controllo sulla password
        if(Hash::check($password_hash, $laboratorio_esiste->password)) {
            // Controllo sul convenzionamento del laboratorio
            if($laboratorio_esiste->convenzionato==0) {
                return back()->with('convenzionamento', 'Laboratorio non ancora convenzionato');
            }
            // Elimino l'eventuale sessione presente
            if($request->session()->has('LoggedUser')) {
                session()->pull('LoggedUser');
                session()->pull('Attore');
            }
            /* controllo se il calendario disponibilità è già stato compilato,
             * se non è stato compilato, obbligo l'utente a compilarlo prima di effettuare l'accesso */
            if ($laboratorio_esiste->calendario_compilato === 0) {
                $fornisci_calendario = true; // flag per attivare solo il form del calendario disponibilità nella vista
                return view('profiloLab', compact('laboratorio_esiste', 'fornisci_calendario'));
            }
            // Inserimento nella sessione dei nuovi dati del login
            $request->session()->put('LoggedUser', $laboratorio_esiste->id);
            $request->session()->put('Attore', Attore::LABORATORIO_ANALISI);
            $request->session()->put('Nome', $laboratorio_esiste->nome);
            // Redirect alla dashboard generale
            return redirect('/'); // homepage
        }
        return back()->with('password', 'password errata');
    }


    /**
     * Effettua il logout dell'utente eliminando i dati salvati nella sessione
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function logout(Request $request)
    {
        if ($request->session()->has('LoggedUser')) {
            $request->session()->pull('LoggedUser');
            $request->session()->pull('Attore');
            $request->session()->pull('Nome');
            return redirect('/');
        }
    }
}
