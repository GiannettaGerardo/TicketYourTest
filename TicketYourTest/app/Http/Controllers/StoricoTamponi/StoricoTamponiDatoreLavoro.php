<?php

namespace App\Http\Controllers\StoricoTamponi;

use \Illuminate\Support\Collection;
use App\Models\Prenotazione;
use Illuminate\Database\QueryException;

class StoricoTamponiDatoreLavoro extends StoricoTamponiPerTerzi
{
    /**
     * StoricoTamponiDatoreLavoro constructor.
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
    public function getStoricoPerTerzi(): Collection
    {
        $prenotazioni_dipendenti = Prenotazione::getStoricoDipendenti($this->getCodiceFiscale());
        $this->mergePazientiInPrenotazioni($prenotazioni_dipendenti, $this->pazienti);
        return $prenotazioni_dipendenti;
    }
}
