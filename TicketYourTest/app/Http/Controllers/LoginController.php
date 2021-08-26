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
    public function getLoginView() {
        return view('login');
    }

    /**
     * Stabilisce la tipologia dell'utente che effettua l'accesso e ne crea la sessione
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function authenticate(Request $request)
    {
        // valido le credenziali ottenute in input dall'utente
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'min:8', 'max:40'],
        ]);

        // memorizzo le credenziali dopo la validazione
        $email = $request->input('email');
        $password_hash = $request->input('password');

        // controllo se l'utente è un admin
        $amministratore_esiste = Admin::getByEmail($email);
        if ($amministratore_esiste) {
            // controllo se la password è giusta
            if (Hash::check($password_hash, $amministratore_esiste->password)) {
                if ($request->session()->has('LoggedUser')) {
                    session()->pull('LoggedUser');
                    session()->pull('Attore');
                }
                $request->session()->put('LoggedUser', $amministratore_esiste->id);
                $request->session()->put('Attore', Attore::AMMINISTRATORE);
                return redirect('/'); // homepage
            }
            else {
                return back()->with('password', 'password errata');
            }
        }

        // controllo se l'utente è un cittadino privato
        $cittadino_esiste = CittadinoPrivato::getByEmail($email);
        if ($cittadino_esiste) {
            // controllo se la password è giusta
            if (Hash::check($password_hash, $cittadino_esiste->password)) {
                if ($request->session()->has('LoggedUser')) {
                    session()->pull('LoggedUser');
                    session()->pull('Attore');
                }
                $request->session()->put('LoggedUser', $cittadino_esiste->id);
                $request->session()->put('Attore', Attore::CITTADINO_PRIVATO);
                return redirect('/profilo'); // profilo personale
            }
            else {
                return back()->with('password', 'password errata');
            }
        }

        // controllo se l'utente è un datore di lavoro
        $datore_lavoro_esiste = DatoreLavoro::getByEmail($email);
        if ($datore_lavoro_esiste) {
            // controllo se la password è giusta
            if (Hash::check($password_hash, $datore_lavoro_esiste->password)) {
                if ($request->session()->has('LoggedUser')) {
                    session()->pull('LoggedUser');
                    session()->pull('Attore');
                }
                $request->session()->put('LoggedUser', $datore_lavoro_esiste->id);
                $request->session()->put('Attore', Attore::DATORE_LAVORO);
                return redirect('/profilo'); // profilo personale
            }
            else {
                return back()->with('password', 'password errata');
            }
        }

        // controllo se l'utente è un medico di medicina generale
        $medico_medicina_generale_esiste = MedicoMG::getByEmail($email);
        if ($medico_medicina_generale_esiste) {
            // controllo se la password è giusta
            if (Hash::check($password_hash, $medico_medicina_generale_esiste->password)) {
                if ($request->session()->has('LoggedUser')) {
                    session()->pull('LoggedUser');
                    session()->pull('Attore');
                }
                $request->session()->put('LoggedUser', $medico_medicina_generale_esiste->id);
                $request->session()->put('Attore', Attore::MEDICO_MEDICINA_GENERALE);
                return redirect('/profilo'); // profilo personale
            }
            else {
                return back()->with('password', 'password errata');
            }
        }

        // Controllo che l'utente sia il laboratorio
        $laboratorio_esiste = Laboratorio::getByEmail($email);
        if ($laboratorio_esiste) {
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
                // Inserimento nella sessione dei nuovi dati del login
                $request->session()->put('LoggedUser', $laboratorio_esiste->id);
                $request->session()->put('Attore', Attore::LABORATORIO_ANALISI);
                // Redirect alla dashboard generale
                return redirect('/'); // homepage
            } else {
                return back()->with('password', 'password errata');
            }
        }

        return back()->with('email', 'email o password errati');
    }

    /**
     * Effettua il logout dell'utente eliminando i dati salvati nella sessione
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function logout(Request $request)
    {
        if (session()->has('LoggedUser')) {
            session()->pull('LoggedUser');
            session()->pull('Attore');
            return redirect('/');
        }
    }
}
