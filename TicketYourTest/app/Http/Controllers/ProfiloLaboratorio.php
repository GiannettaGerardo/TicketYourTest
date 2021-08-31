<?php

namespace App\Http\Controllers;

use App\Models\CalendarioDisponibilita;
use App\Models\Laboratorio;
use App\Models\Tampone;
use App\Models\TamponiProposti;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

/**
 * Class ProfiloLaboratorio
 * Controller per gestire le informazione del profilo di un laboratorio di analisi
 * @package App\Http\Controllers
 */
class ProfiloLaboratorio extends Controller
{
    /**
     * Aggiunge il calendario delle disponibilità al database al primo accesso del laboratorio
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function fornisciCalendarioDisponibilita(Request $request)
    {
        $calendario_fallimento = null; // messaggio da visualizzare in caso di fallimento
        $calendario_successo = null;   // messaggio da visualizzare in caso di successo
        $calendario = $request->input('calendario');
        $calendario = $this->formattaCalendario($calendario);
        if ($calendario === null) {
            $calendario_fallimento = 'Errore. Orari inseriti non validi.';
            return view('login', compact('calendario_fallimento', 'calendario_successo'));
        }
        //dd($calendario);
        try {
            CalendarioDisponibilita::upsertCalendarioPerLaboratorio($request->input('id_laboratorio'), $calendario);
            Laboratorio::setFlagCalendarioCompilato($request->input('id_laboratorio'), 1);
        }
        catch(QueryException $ex) {
            $calendario_fallimento = 'Errore. Calendario non creato.';
            return view('login', compact('calendario_fallimento', 'calendario_successo'));
        }
        $calendario_successo = 'Calendario creato con successo. Ora puoi accedere al tuo account.';
        return view('login', compact('calendario_fallimento', 'calendario_successo'));
    }


    /**
     * Ritorna la vista di modifica calendario disponibilità e tamponi offerti di un laboratorio, con annessi dati
     * @param Request $request
     * @param null $messaggio
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function getViewModifica(Request $request, $messaggio=null)
    {
        $id_laboratorio = $request->session()->get('LoggedUser');
        $calendario_disponibilita = CalendarioDisponibilita::getCalendarioDisponibilitaByIdLaboratorio($id_laboratorio);
        $lista_tamponi_offerti = TamponiProposti::getTamponiPropostiByLaboratorio($id_laboratorio);
        $fornisci_calendario = false;
        return view('profiloLab', compact('calendario_disponibilita', 'lista_tamponi_offerti', 'messaggio', 'fornisci_calendario'));
    }


    /*public function modificaLaboratorio(Request $request)
    {
        // Ottenimento input
        $input = $request->all();
        $max_costo_tampone = 50.0;

        // Controllo sull'inserimento di almeno uno dei tamponi
        if (!isset($input['tamponeRapido']) and !isset($input['tamponeMolecolare'])) {
            $tampone_non_scelto = 'Non e\' stato scelto nessun tampone!';
            return $this->getViewModifica($request, $tampone_non_scelto);
        }
        // Controllo sui prezzi del tampone
        $costo_tampone_non_consentito = 'Il costo del tampone inserito non è consentito';
        if (isset($input['tamponeRapido']) and ($input['costoTamponeRapido']<=0.0 or $input['costoTamponeRapido']>=$max_costo_tampone)) {
            return $this->getViewModifica($request, $costo_tampone_non_consentito);
        }
        if (isset($input['tamponeMolecolare']) and ($input['costoTamponeMolecolare']<=0.0 or $input['costoTamponeMolecolare']>=$max_costo_tampone)) {
            return $this->getViewModifica($request, $costo_tampone_non_consentito);
        }
    }*/


    /**
     * Ritorna la vista per modificare la lista dei tamponi offerti, insieme alla lista stessa
     * @param Request $request
     * @param null $message // messaggio eventuale
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    /*public function visualizzaListaTamponiOfferti(Request $request, $message=null)
    {
        $tamponi_offerti = TamponiProposti::getTamponiPropostiByLaboratorio($request->session()->get('LoggedUser'));
        return view('modifica-lista-tamponi', compact('tamponi_offerti', 'message'));
    }*/

    /**
     * Modifica la lista dei tamponi offerti da uno specifico laboratorio
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    /*public function modificaListaTamponiOfferti(Request $request)
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
    }*/

    /**
     * Formatta il calendario preso in input da una vista html
     * @param $calendario
     * @return null|$calendario // se un orario di apertura è maggiore o uguale a uno di chiusura, ritorna null
     */
    private function formattaCalendario($calendario) {
        foreach ($calendario as $giorno => $orari) {
            if (($orari['oraApertura'] === null) or ($orari['oraChiusura'] === null)) {
                unset($calendario[$giorno]);
            }
            else {
                $calendario[$giorno]['oraApertura'] = date('H:i:s', strtotime($orari['oraApertura'].':00'));
                $calendario[$giorno]['oraChiusura'] = date('H:i:s', strtotime($orari['oraChiusura'].':00'));
                if ($calendario[$giorno]['oraApertura'] >= $calendario[$giorno]['oraChiusura']) {
                    return null;
                }
            }
        }
        return $calendario;
    }
}
