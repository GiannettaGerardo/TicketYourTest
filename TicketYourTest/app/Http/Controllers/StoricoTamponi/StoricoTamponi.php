<?php

namespace App\Http\Controllers\StoricoTamponi;

use Illuminate\Database\QueryException;
use Illuminate\Support\Collection;

interface StoricoTamponi
{
    /**
     * Ritorna lo storico dei tamponi personali
     * @return Collection
     */
    public function getStoricoPersonale(): Collection;

    /**
     * Ritorna lo storico dei tamponi prenotati per terzi
     * @return \Illuminate\Support\Collection|null
     * @throws QueryException
     */
    public function getStoricoPerTerzi();
}
