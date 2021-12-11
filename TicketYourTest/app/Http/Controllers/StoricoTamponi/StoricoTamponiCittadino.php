<?php


namespace App\Http\Controllers\StoricoTamponi;


use App\Models\Prenotazione;
use Illuminate\Database\QueryException;
use Illuminate\Support\Collection;

class StoricoTamponiCittadino extends AbstractStoricoTamponi
{
    /**
     * StoricoTamponiCittadino constructor.
     * @param int $id dell'utente salvato in sessiome
     */
    public function __construct(int $id) {
        parent::__construct($id);
    }

    /**
     * Ritorna null per il cittadino perché non ha storici per terzi
     * @return \Illuminate\Support\Collection
     * @throws QueryException
     */
    public function getStoricoPerTerzi() : Collection
    {
        $prenotazioni_dipendenti = Prenotazione::getStoricoFamigliariCittadino($this->getCodiceFiscale());
        $this->mergePazientiInPrenotazioni($prenotazioni_dipendenti, $this->pazienti);
        return $prenotazioni_dipendenti;
    }
}
