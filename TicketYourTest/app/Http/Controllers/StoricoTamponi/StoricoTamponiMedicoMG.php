<?php

namespace App\Http\Controllers\StoricoTamponi;

use App\Models\Prenotazione;
use Illuminate\Database\QueryException;
use \Illuminate\Support\Collection;

class StoricoTamponiMedicoMG extends StoricoTamponiPerTerzi
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
}
