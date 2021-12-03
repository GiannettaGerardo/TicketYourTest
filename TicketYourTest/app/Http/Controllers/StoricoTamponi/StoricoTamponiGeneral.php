<?php

namespace App\Http\Controllers\StoricoTamponi;

use App\Models\Prenotazione;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Support\Collection;

abstract class StoricoTamponiGeneral implements StoricoTamponi
{
    /** @var String codice fiscale dell'utente salvato in sessione */
    private $codiceFiscale;

    /** @var \Illuminate\Support\Collection di prenotazioni personali già avvenute in precedenza */
    private $prenotazioniPersonali;


    /**
     * AbstractStoricoTamponi constructor.
     * @param int $id dell'utente salvato in sessiome
     */
    function __construct(int $id) {
        try {
            $this->codiceFiscale = (User::getById($id))->codice_fiscale;
            $this->prenotazioniPersonali = Prenotazione::getStoricoPersonale($this->codiceFiscale);
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

}
