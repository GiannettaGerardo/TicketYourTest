<?php

namespace App\Http\Controllers\StoricoTamponi;

use App\Models\Paziente;
use App\Models\Prenotazione;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Support\Collection;

abstract class AbstractStoricoTamponi implements StoricoTamponi
{
    /** @var String codice fiscale dell'utente salvato in sessione */
    private $codiceFiscale;

    /** @var \Illuminate\Support\Collection di prenotazioni personali già avvenute in precedenza */
    private $prenotazioniPersonali;

    /** @var \Illuminate\Support\Collection tutti i pazienti presi da tutte le tabelle */
    protected $pazienti;


    /**
     * AbstractStoricoTamponi constructor.
     * @param int $id dell'utente salvato in sessiome
     */
    function __construct(int $id) {
        try {
            $this->codiceFiscale = (User::getById($id))->codice_fiscale;
            $this->prenotazioniPersonali = Prenotazione::getStoricoPersonale($this->codiceFiscale);
            $this->pazienti = $pazienti = Paziente::getQueryForAllPazienti()->get();
        }
        catch (QueryException $e) { throw $e; }
    }


    /**
     * Ritorna il codice fiscale dell'utente loggato in sessione
     * @return string
     */
    public function getCodiceFiscale(): string
    {
        return $this->codiceFiscale;
    }


    /**
     * Ritorna lo storico personale
     * @return Collection
     */
    public function getStoricoPersonale(): Collection
    {
        return $this->prenotazioniPersonali;
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
     * @return \Illuminate\Support\Collection
     * @throws QueryException
     */
    public abstract function getStoricoPerTerzi() : Collection;

}
