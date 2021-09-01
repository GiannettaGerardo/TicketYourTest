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
     * Al primo accesso di un laboratorio al sistema, dopo essersi convenzionato,
     * esso compila il proprio calendario della disponibilità settimanale, questo metodo
     * quindi aggiunge il calendario delle disponibilità al database
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function fornisciCalendarioDisponibilita(Request $request)
    {
        $calendario_fallimento = null; // messaggio da visualizzare in caso di fallimento
        $calendario_successo = null;   // messaggio da visualizzare in caso di successo
        // formatta l'array calendario preso in input, in modo da essere memorizzato sul database
        $calendario = $this->formattaCalendario($request->input('calendario'));
        if ($calendario === null) {
            $calendario_fallimento = 'Errore. Orari inseriti non validi.';
            return view('login', compact('calendario_fallimento', 'calendario_successo'));
        }
        if (empty($calendario)) {
            $calendario_fallimento = 'Errore. Nessun giorno selezionato.';
            return view('login', compact('calendario_fallimento', 'calendario_successo'));
        }
        try {
            CalendarioDisponibilita::upsertCalendarioPerLaboratorio($request->input('id_laboratorio'), $calendario);
            Laboratorio::setFlagCalendarioCompilato($request->input('id_laboratorio'), 1);
        }
        catch(QueryException $ex) {
            abort(500, 'Il database non risponde.');
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
    public function getViewModifica(Request $request, $messaggio_errore=null, $messaggio_successo=null)
    {
        $id_laboratorio = $request->session()->get('LoggedUser');
        try {
            $calendario_disponibilita = CalendarioDisponibilita::getCalendarioDisponibilitaByIdLaboratorio($id_laboratorio);
            $lista_tamponi_offerti = TamponiProposti::getTamponiPropostiByLaboratorio($id_laboratorio);
        }
        catch(QueryException $ex) {
            abort(500, 'Il database non risponde.');
        }
        $fornisci_calendario = false;
        return view('profiloLab', compact('calendario_disponibilita', 'lista_tamponi_offerti', 'messaggio_errore', 'messaggio_successo', 'fornisci_calendario'));
    }


    /**
     * Modifica il prpfilo di un laboratorio di analisi, in particolare ne modifica la lista
     * dei tamponi offerti e il calendario di disponibilità settimanali
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function modificaLaboratorio(Request $request)
    {
        // ottenimento input
        $input = $request->all();
        $max_costo_tampone = 20.0;
        $id_laboratorio = $request->session()->get('LoggedUser');

        // LISTA TAMPONI OFFERTI
        // controllo sull'inserimento di almeno uno dei tamponi
        if (!isset($input['tamponeRapido']) and !isset($input['tamponeMolecolare'])) {
            return $this->getViewModifica($request, 'Non e\' stato scelto nessun tampone!', null);
        }
        // controllo sui prezzi del tampone
        $costo_tampone_non_consentito = 'Errore. Il costo del tampone inserito non è consentito';
        if (isset($input['tamponeRapido']) and ($input['costoTamponeRapido']<=0.0 or $input['costoTamponeRapido']>=$max_costo_tampone)) {
            return $this->getViewModifica($request, $costo_tampone_non_consentito, null);
        }
        if (isset($input['tamponeMolecolare']) and ($input['costoTamponeMolecolare']<=0.0 or $input['costoTamponeMolecolare']>=$max_costo_tampone)) {
            return $this->getViewModifica($request, $costo_tampone_non_consentito, null);
        }
        // aggiorna la lista dei tamponi offerti
        $this->modificaListaTamponiOfferti($id_laboratorio, $input);

        // CALENDARIO DISPONIBILITÀ
        // formatto il calendario preso in input
        $calendario = $this->formattaCalendario($request->input('calendario'));
        if ($calendario === null) {
            return $this->getViewModifica($request, 'Errore. Orari inseriti non validi.', null);
        }
        if (empty($calendario)) {
            return $this->getViewModifica($request, 'Errore. Nessun giorno selezionato.', null);
        }
        // aggiorno il calendario disponibilità
        $this->modificaCalendarioDisponibilita($id_laboratorio, $calendario);

        return $this->getViewModifica($request, null, 'Profilo modificato con successo.');
    }


    /**
     * Modifica la lista dei tamponi offerti sul database
     * @param $id_laboratorio
     * @param $input
     */
    private function modificaListaTamponiOfferti($id_laboratorio, $input) {
        try {
            // ottengo le liste di tamponi esistenti e tamponi offerti dal laboratorio
            $tamponi = Tampone::getTamponi();
            $lista_tamponi_offerti = TamponiProposti::getTamponiPropostiByLaboratorio($id_laboratorio);

            /* ottengo gli id delle varie tipologie di tampone e creo un array di
             * booleani per controllare quali tamponi erano precedentemente offerti */
            $id_tamponi = [];
            $id_tamponi_pre_esistenti = [];
            foreach ($tamponi as $tampone) {
                $id_tamponi[$tampone->nome] = $tampone->id;
                foreach ($lista_tamponi_offerti as $offerto) {
                    if ($offerto->id_tampone === $tampone->id) {
                        $id_tamponi_pre_esistenti[$tampone->nome] = $tampone->id;
                        break;
                    }
                }
            }

            /* Tampone Rapido:
             * se è stato preso in input, aggiorna il db, altrimenti, se non è stato preso ed esisteva già sul db, eliminalo */
            if (isset($input['tamponeRapido'])) {
                TamponiProposti::upsertListaTamponiOfferti($id_laboratorio, $id_tamponi['Tampone rapido'], $input['costoTamponeRapido']);
            }
            elseif (isset($id_tamponi_pre_esistenti['Tampone rapido'])) {
                TamponiProposti::deleteTamponeOfferto($id_laboratorio, $id_tamponi_pre_esistenti['Tampone rapido']);
            }
            /* Tampone Molecolare:
             * se è stato preso in input, aggiorna il db, altrimenti, se non è stato preso ed esisteva già sul db, eliminalo */
            if (isset($input['tamponeMolecolare'])) {
                TamponiProposti::upsertListaTamponiOfferti($id_laboratorio, $id_tamponi['Tampone molecolare'], $input['costoTamponeMolecolare']);
            }
            elseif (isset($id_tamponi_pre_esistenti['Tampone molecolare'])) {
                TamponiProposti::deleteTamponeOfferto($id_laboratorio, $id_tamponi_pre_esistenti['Tampone molecolare']);
            }
        }
        catch(QueryException $ex) {
            abort(500, 'Il database non risponde.');
        }
    }


    /**
     * Modifica il calendario disponibilità del laboratorio
     * @param $id_laboratorio
     * @param $calendario_input // array calendario ottenuto in input e già formattato
     */
    private function modificaCalendarioDisponibilita($id_laboratorio, $calendario_input) {
        try {
            // ottengo il calendario che già esiste sul db prima della modifica
            $calendario_pre_esistente = CalendarioDisponibilita::getCalendarioDisponibilitaByIdLaboratorio($id_laboratorio);
            // aggiorno il calendario sul database
            CalendarioDisponibilita::upsertCalendarioPerLaboratorio($id_laboratorio, $calendario_input);

            // raccolgo i giorni della settimana che prima erano nel calendario, ma ora non lo sono più
            $giorni_da_eliminare = [];
            foreach ($calendario_pre_esistente as $pre_esistente) {
                if (!isset($calendario_input[$pre_esistente->giorno_settimana])) {
                    array_push($giorni_da_eliminare, $pre_esistente->giorno_settimana);
                }
            }
            // se ho effettivamente raccolto dei giorni nell'apposito array, li elimino dalla tabella del db
            if (!empty($giorni_da_eliminare)) {
                CalendarioDisponibilita::deleteGiorniCalendario($id_laboratorio, $giorni_da_eliminare);
            }
        }
        catch(QueryException $ex) {
            abort(500, 'Il database non risponde.');
        }
    }


    /**
     * Formatta il calendario preso in input da una vista html
     * @param $calendario
     * @return null|array // se un orario di apertura è maggiore o uguale a uno di chiusura, ritorna null
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
