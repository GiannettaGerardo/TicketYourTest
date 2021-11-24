<?php

namespace App\Http\Controllers;

use App\Models\Laboratorio;
use App\Models\Paziente;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class RisultatiTamponiController extends Controller
{




    public function registraRisultato(Request $request)
    {
        // prendo l'input dal form di registrazione risultato
        $id_prenotazione = $request->input('id_prenotazione');
        $codice_fiscale = $request->input('codice_fiscale');
        $nome = $request->input('nome');
        $cognome = $request->input('cognome');
        $esito = $request->input('esito');
        //$carica_virale = $request->input('carica_virale');

        try {
            $laboratorio = Laboratorio::getById($request->session()->get('LoggedUser'));
            Paziente::updateEsitoTampone($id_prenotazione, $codice_fiscale, $esito);
        }
        catch (QueryException $e) {
            abort(500, 'Il database non risponde.');
        }

        // memorizza informazioni del referto nel database
    }
}
