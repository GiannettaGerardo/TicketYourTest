<?php

namespace App\Http\Controllers\StoricoTamponi;

use Illuminate\Database\QueryException;
use \Illuminate\Support\Collection;

class StoricoTamponiMedicoMG extends StoricoTamponiPersonaliPerTerzi
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
     * @return \Illuminate\Support\Collection
     * @throws QueryException
     */
    public function getStoricoPerTerzi() : Collection
    {
        // TODO: Implement getStorico() method.
    }
}
