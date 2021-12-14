<?php

namespace App\Http\Controllers\StoricoTamponi;

use App\Models\Laboratorio;
use App\Models\MedicoMG;
use App\Models\Paziente;
use App\Models\Prenotazione;
use App\Models\Referto;
use App\Models\Tampone;
use Illuminate\Database\QueryException;
use \Illuminate\Support\Collection;
use Illuminate\Http\Request;

class StoricoTamponiMedicoMG extends AbstractStoricoTamponi
{
    /**
     * StoricoTamponiMedicoMG constructor.
     * @param int $id
     */
    function __construct(int $id)
    {
        parent::__construct($id);
    }


    /**
     * Ritorna lo storico dei tamponi prenotati per terzi
     * @return \Illuminate\Support\Collection
     * @throws QueryException
     */
    public function getStoricoPerTerzi() : Collection
    {
        $medico = MedicoMG::getById($this->idUtente);
        $lista_pazienti = null;
        $elenco_referti = new Collection();

        try {
            $lista_pazienti = Paziente::getPazientiByEmailMedico($medico->email);

            /*
             * Si prende l'ultimo referto per ciascun paziente (il cui risultato non e' stato comunicato all'ASL)
             * e si aggiunge il risultato ottenuto in una collection che verra' restituita.
             */
            foreach($lista_pazienti as $paziente) {
                $referto = Referto::getUltimoRefertoPazienteByCodiceFiscale($paziente->cf_paziente);

                if(isset($referto)) {
                    $prenotazione = Prenotazione::getPrenotazioneById($referto->id_prenotazione);
                    $tampone = Tampone::getTamponeById($prenotazione->id_tampone);
                    $laboratorio = Laboratorio::getById($prenotazione->id_laboratorio);

                    $result = [
                        'id_prenotazione' => $prenotazione->id,
                        'cf_terzo' => $paziente->cf_paziente,
                        'nome_terzo' => $paziente->nome_paziente,
                        'cognome_terzo' => $paziente->cognome_paziente,
                        'data_tampone' => $prenotazione->data_tampone,
                        'tipo_tampone' => $tampone->nome,
                        'laboratorio_scelto' => $laboratorio->nome,
                        'id_referto' => $referto->id,
                        'risultato_comunicato' => $paziente->risultato_comunicato_ad_asl_da_medico
                    ];
                    $elenco_referti->push((object) $result);
                }
            }
        }
        catch(QueryException $ex) {
            abort(500, 'Il database non risponde.');
        }

        return collect($elenco_referti);
    }


    /**
     * Aggiorna ad un paziente del medico, l'attributo riguardante
     * l'invio del risultato all'ASL
     * @throws QueryException
     * @param Request $request
     */
    public function comunicaRisultatoASL(Request $request)
    {
        $cod_fiscale_paziente = $request->input('cf_terzo');
        Paziente::updateRisultatoComunicatoASL($cod_fiscale_paziente);
    }

}
