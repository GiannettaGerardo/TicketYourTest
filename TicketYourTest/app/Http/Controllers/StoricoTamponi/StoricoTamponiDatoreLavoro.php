<?php

namespace App\Http\Controllers\StoricoTamponi;

use \Illuminate\Support\Collection;
use App\Models\Prenotazione;
use Illuminate\Database\QueryException;

class StoricoTamponiDatoreLavoro extends StoricoTamponiPersonaliPerTerzi
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
     * @return \Illuminate\Support\Collection
     * @throws QueryException
     */
    public function getStoricoPerTerzi(): Collection
    {
        $pazienti = $this->getPazienti();
        // forse è possibile generalizzare con i metodi astratti gran parte di questo algoritmo
        // ad esempio controllare se il doppio for è sempre uguale e se la chiamata a prenotazione è simile
        $prenotazioni_dipendenti = Prenotazione::getStoricoDipendenti($this->getCodiceFiscale());

        // unisco prenotazioni dei dipendenti con il loro nome e cognome preso da altre tabelle
        foreach ($prenotazioni_dipendenti as $prenotazione) {
            if ($prenotazione->nome_dipendente === null) {
                foreach ($pazienti as $paziente) {
                    if ($paziente->cf_paziente === $prenotazione->cf_dipendente) {
                        $prenotazione->nome_dipendente = $paziente->nome_paziente;
                        $prenotazione->cognome_dipendente = $paziente->cognome_paziente;
                    }
                }
            }
        }
        return $prenotazioni_dipendenti;
    }
}
