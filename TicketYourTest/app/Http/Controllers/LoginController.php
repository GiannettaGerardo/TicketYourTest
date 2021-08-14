<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\CittadinoPrivato;
use App\Models\DatoreLavoro;
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
    const AMMINISTRATORE = 0;           // costante per indicare l'amministratore
    const CITTADINO_PRIVATO = 1;        // costante per indicare il cittadino privato
    const DATORE_LAVORO = 2;            // costante per indicare il datore di lavoro
    const MEDICO_MEDICINA_GENERALE = 3; // costante per indicare il medico di medicina generale
    const LABORATORIO_ANALISI = 4;      // costante per indicare il laboratorio di analisi

    /**
     * Stabilisce la tipologia dell'utente che effettua l'accesso e ne crea la sessione
     * flag Attore in session:
     * 0 -> Amministratore
     * 1 -> Cittadino privato
     * 2 -> Datore lavoro
     * 3 -> Medico medicina generale
     * 4 -> Laboratorio di analisi
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
                $request->session()->put('Attore', self::AMMINISTRATORE);
                return redirect('dashboard'); // dashboard generale
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
                $request->session()->put('Attore', self::CITTADINO_PRIVATO);
                return redirect('dashboard'); // dashboard generale
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
                $request->session()->put('Attore', self::DATORE_LAVORO);
                return redirect('dashboard'); // dashboard generale
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
                $request->session()->put('Attore', self::MEDICO_MEDICINA_GENERALE);
                return redirect('dashboard'); // dashboard generale
            }
            else {
                return back()->with('password', 'password errata');
            }
        }

        /*
         * $laboratorio_analisi_esiste = Laboratorio::getByEmail($email);
        if ($laboratorio_analisi_esiste) {
        }
         */

        return back()->with('email', 'utente non esistente');
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