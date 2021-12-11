<?php

namespace App\Http\Controllers\StoricoTamponi;

use App\Models\Paziente;
use App\Models\Prenotazione;
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
        $prenotazioni_pazienti = Prenotazione::getStoricoPazientiMedico($this->getCodiceFiscale());
        $this->mergePazientiInPrenotazioni($prenotazioni_pazienti, $this->pazienti);
        return $prenotazioni_pazienti;
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
