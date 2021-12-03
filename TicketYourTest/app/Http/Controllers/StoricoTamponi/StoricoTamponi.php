<?php

namespace App\Http\Controllers\StoricoTamponi;

use Illuminate\Support\Collection;

interface StoricoTamponi
{

    public function getStoricoPersonale();

    public function getStoricoPerTerzi();
}
