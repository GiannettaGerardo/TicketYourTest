<?php


namespace App\Http\Controllers\StoricoTamponi;


class StoricoTamponiCittadino extends StoricoTamponiGeneral
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
     * @return null
     */
    public function getStoricoPerTerzi() {
        return null;
    }
}
