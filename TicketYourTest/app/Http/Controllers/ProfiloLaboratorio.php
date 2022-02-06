<?php

namespace App\Http\Controllers;

use App\Models\LaboratorioModel\CalendarioDisponibilita;
use App\Models\LaboratorioModel\Laboratorio;
use App\Models\TamponeModel\Tampone;
use App\Models\LaboratorioModel\TamponiProposti;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

/**
 * Class ProfiloLaboratorio
 * Controller per gestire le informazione del profilo di un laboratorio di analisi
 * @package App\Http\Controllers
 */
class ProfiloLaboratorio extends Controller
{
    const CAPIENZA_MINIMA = 5;      // capienza minima di prenotazioni per un laboratorio
    const CAPIENZA_MASSIMA = 10000; // capienza massima di prenotazioni per un laboratorio


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

        $id_lab = $request->input('id_laboratorio');
        $capienza = $request->input('capienzaGiornaliera');

        // controllo sulla capienza minima di prenotazioni per un laboratorio
        if ($capienza < self::CAPIENZA_MINIMA) {
            $calendario_fallimento = 'Errore. La capienza minima deve essere almeno di '.self::CAPIENZA_MINIMA;
            return view('login', compact('calendario_fallimento', 'calendario_successo'));
        }
        // controllo sulla capienza massima di prenotazioni per un laboratorio
        if ($capienza > self::CAPIENZA_MASSIMA) {
            $calendario_fallimento = 'Errore. La capienza massima non deve superare '.self::CAPIENZA_MASSIMA;
            return view('login', compact('calendario_fallimento', 'calendario_successo'));
        }

        DB::beginTransaction();
        try {
            CalendarioDisponibilita::upsertCalendarioPerLaboratorio($id_lab, $calendario);
            Laboratorio::setFlagCalendarioCompilato($id_lab, 1);
            Laboratorio::setCapienzaById($id_lab, $capienza);

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
        $calendario_successo = 'Calendario creato con successo. Ora puoi accedere al tuo account.';
        return view('login', compact('calendario_fallimento', 'calendario_successo'));
    }


    /**
     * Ritorna la vista di modifica calendario disponibilità e tamponi offerti di un laboratorio, con annessi dati
     * @param Request $request
     * @param null $messaggio_errore
     * @param null $messaggio_successo
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function getViewModifica(Request $request, $messaggio_errore=null, $messaggio_successo=null)
    {
        $id_laboratorio = $request->session()->get('LoggedUser');
        $calendario_disponibilita = null;
        $capienza = null;
        $lista_tamponi_offerti = null;

        try {
            $calendario_disponibilita = CalendarioDisponibilita::getCalendarioDisponibilitaByIdLaboratorio($id_laboratorio);
            $capienza = Laboratorio::getCapienzaById($id_laboratorio);
            $lista_tamponi_offerti = TamponiProposti::getTamponiPropostiByLaboratorio($id_laboratorio);
        }
        catch(QueryException $ex) {
            abort(500, 'Il database non risponde.');
        }
        $fornisci_calendario = false;
        return view('profiloLab', compact(
            'calendario_disponibilita',
            'lista_tamponi_offerti',
            'messaggio_errore',
            'messaggio_successo',
            'fornisci_calendario',
            'capienza'
        ));
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
        $max_costo_tampone = 40.0;
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

        // CALENDARIO DISPONIBILITÀ
        // formatto il calendario preso in input
        $calendario = $this->formattaCalendario($request->input('calendario'));
        if ($calendario === null) {
            return $this->getViewModifica($request, 'Errore. Orari inseriti non validi.', null);
        }
        if (empty($calendario)) {
            return $this->getViewModifica($request, 'Errore. Nessun giorno selezionato.', null);
        }

        // CAPIENZA
        // controllo sulla capienza minima di prenotazioni per un laboratorio
        if ($input['capienzaGiornaliera'] < self::CAPIENZA_MINIMA) {
            $fallimento = 'Errore. La capienza minima deve essere almeno di '.self::CAPIENZA_MINIMA;
            return $this->getViewModifica($request, $fallimento, null);
        }
        // controllo sulla capienza massima di prenotazioni per un laboratorio
        if ($input['capienzaGiornaliera'] > self::CAPIENZA_MASSIMA) {
            $fallimento = 'Errore. La capienza massima non deve superare '.self::CAPIENZA_MASSIMA;
            return $this->getViewModifica($request, $fallimento, null);
        }

        DB::beginTransaction();
        try {
            // AGGIORNAMENTI CONSENTITI DOPO I CONTROLLI
            // aggiorna la lista dei tamponi offerti
            $this->modificaListaTamponiOfferti($id_laboratorio, $input);

            // aggiorno il calendario disponibilità
            $this->modificaCalendarioDisponibilita($id_laboratorio, $calendario);

            // aggiorno la capienza del laboratorio
            Laboratorio::setCapienzaById($id_laboratorio, $input['capienzaGiornaliera']);

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

        return $this->getViewModifica($request, null, 'Profilo modificato con successo.');
    }


    /**
     * Modifica la lista dei tamponi offerti sul database
     * @param $id_laboratorio
     * @param $input
     */
    private function modificaListaTamponiOfferti($id_laboratorio, $input) {
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


    /**
     * Modifica il calendario disponibilità del laboratorio
     * @param $id_laboratorio
     * @param $calendario_input // array calendario ottenuto in input e già formattato
     */
    private function modificaCalendarioDisponibilita($id_laboratorio, $calendario_input) {
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
            foreach ($giorni_da_eliminare as $giorno) {
                CalendarioDisponibilita::deleteGiorniCalendario($id_laboratorio, $giorno);
            }
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
                $calendario[$giorno]['oraApertura'] = date('H:i:s', strtotime($orari['oraApertura']));
                $calendario[$giorno]['oraChiusura'] = date('H:i:s', strtotime($orari['oraChiusura']));
                if ($calendario[$giorno]['oraApertura'] >= $calendario[$giorno]['oraChiusura']) {
                    return null;
                }
            }
        }
        return $calendario;
    }
}
