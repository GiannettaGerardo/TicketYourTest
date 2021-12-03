<?php


namespace App\Http\Controllers\StoricoTamponi;


use App\Models\Paziente;
use Illuminate\Database\QueryException;
use Illuminate\Support\Collection;

abstract class StoricoTamponiPersonaliPerTerzi extends StoricoTamponiGeneral
{
    /** @var \Illuminate\Support\Collection tutti i pazienti presi da tutte le tabelle */
    private $pazienti;


    function __construct(int $id)
    {
        parent::__construct($id);
        try {
            $this->pazienti = $pazienti = Paziente::getQueryForAllPazienti()->get();
        } catch (QueryException $e) { throw $e; }
    }


    /**
     * Ritorna tutti i pazienti
     * @return \Illuminate\Support\Collection
     */
    public function getPazienti(): \Illuminate\Support\Collection
    {
        return $this->pazienti;
    }


    public abstract function getStoricoPerTerzi() : Collection;
}
