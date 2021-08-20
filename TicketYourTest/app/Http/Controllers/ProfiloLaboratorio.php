<?php

namespace App\Http\Controllers;

use App\Models\Tampone;
use App\Models\TamponiProposti;
use Illuminate\Http\Request;

class ProfiloLaboratorio extends Controller
{
    /**
     * Ritorna la vista per modificare la lista dei tamponi offerti, insieme alla lista stessa
     * @param Request $request
     * @param null $message // messaggio eventuale
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function visualizzaListaTamponiOfferti(Request $request, $message=null)
    {
        $tamponi_offerti = TamponiProposti::getTamponiPropostiByLaboratorio($request->session()->get('LoggedUser'));
        return view('modifica-lista-tamponi', compact('tamponi_offerti', 'message'));
    }

    /**
     * Modifica la lista dei tamponi offerti da uno specifico laboratorio
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function modificaListaTamponiOfferti(Request $request)
    {
        // Ottenimento input
        $input = $request->all();
        $max_costo_tampone = 50.0;

        // Controllo sull'inserimento di almeno uno dei tamponi
        if (!isset($input['tamponeRapido']) and !isset($input['tamponeMolecolare'])) {
            $tampone_non_scelto = 'Non e\' stato scelto nessun tampone!';
            return $this->visualizzaListaTamponiOfferti($request, $tampone_non_scelto);
        }

        // Controllo sui prezzi del tampone
        $costo_tampone_non_consentito = 'Il costo del tampone inserito non è consentito';
        if (isset($input['tamponeRapido']) and ($input['costoTamponeRapido']<=0.0 or $input['costoTamponeRapido']>=$max_costo_tampone)) {
            return $this->visualizzaListaTamponiOfferti($request, $costo_tampone_non_consentito);
        }
        if (isset($input['tamponeMolecolare']) and ($input['costoTamponeMolecolare']<=0.0 or $input['costoTamponeMolecolare']>=$max_costo_tampone)) {
            return $this->visualizzaListaTamponiOfferti($request, $costo_tampone_non_consentito);
        }

        // aggiorna la lista tamponi
        if (isset($input['tamponeRapido'])) {
            $tampone = Tampone::getTamponeByNome('Tampone rapido');
            TamponiProposti::updateListaTamponiOfferti($request->session()->get('LoggedUser'), $tampone->id, $input['costoTamponeRapido']);
        }
        if(isset($input['tamponeMolecolare'])) {
            $tampone = Tampone::getTamponeByNome('Tampone molecolare');
            TamponiProposti::updateListaTamponiOfferti($request->session()->get('LoggedUser'), $tampone->id, $input['costoTamponeMolecolare']);
        }

        $modifica_lista_tamponi_successo = 'La modifica della lista dei tamponi offerti è avvenuta con successo!';
        return $this->visualizzaListaTamponiOfferti($request, $modifica_lista_tamponi_successo);
    }
}
