<?php

namespace App\Http\Controllers;

use App\Models\CittadinoPrivato;
use App\Models\DatoreLavoro;
use App\Models\Laboratorio;
use App\Models\ListaDipendenti;
use Illuminate\Http\Request;

/**
 * Class ListaDipendentiController
 * Classe che incapsula la logica per il comportamento della lista dei dipendenti.
 */
class ListaDipendentiController extends Controller
{
    /**
     * Richiede l'inserimento nella lista dipendenti di un cittadino privato.
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|void
     */
    public function richiediInserimento(Request $request) {
        // Controllo inserimento nome azienda
        $request->validate(['nomeAzienda' => 'required|max:40']);

        // Controllo esistenza dell'azienda
        $nome_azienda = $request->input('nomeAzienda');
        $azienda = Laboratorio::getAziendaByNome($nome_azienda);
        if(!$azienda) {
            return back()->with('nome-azienda-errato', 'Il nome dell\'azienda risulta errato o inesistente!');
        }

        // Inserimento nel database dei dati
        $cittadino_privato = CittadinoPrivato::getById($request->get('LoggedUser'));
        ListaDipendenti::insertNewCittadino($azienda->partita_iva, $cittadino_privato->codice_fiscale, 0);

        return back()->with('richiesta-avvenuta', 'La richiesta e\' avvenuta con successo!');
    }
}
