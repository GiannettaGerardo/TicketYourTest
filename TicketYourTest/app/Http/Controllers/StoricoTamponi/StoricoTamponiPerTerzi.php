<?php


namespace App\Http\Controllers\StoricoTamponi;

use App\Models\Paziente;
use Illuminate\Database\QueryException;
use Illuminate\Support\Collection;


abstract class StoricoTamponiPerTerzi extends StoricoTamponiGeneral
{
    /** @var \Illuminate\Support\Collection tutti i pazienti presi da tutte le tabelle */
    protected $pazienti;

    /**
     * StoricoTamponiPersonaliPerTerzi constructor.
     * @param int $id
     */
    function __construct(int $id)
    {
        parent::__construct($id);
        try {
            $this->pazienti = $pazienti = Paziente::getQueryForAllPazienti()->get();
        } catch (QueryException $e) { throw $e; }
    }


    /**
     * Fonde i dati delle prenotazioni con i dati dei pazienti, aggiungendo
     * solo nome e cognome dei pazienti alle prenotazioni che non li hanno
     * @param Collection $prenotazioni
     * @param Collection $pazienti
     */
    protected function mergePazientiInPrenotazioni(Collection $prenotazioni, Collection $pazienti)
    {
        foreach ($prenotazioni as $prenotazione) {
            if ($prenotazione->nome_terzo === null) {
                foreach ($pazienti as $paziente) {
                    if ($paziente->cf_paziente === $prenotazione->cf_terzo) {
                        $prenotazione->nome_terzo = $paziente->nome_paziente;
                        $prenotazione->cognome_terzo = $paziente->cognome_paziente;
                    }
                }
            }
        }
    }


    /**
     * Ritorna lo storico dei tamponi prenotati per terzi
     * @return \Illuminate\Support\Collection|null
     * @throws QueryException
     */
    public abstract function getStoricoPerTerzi();
}
